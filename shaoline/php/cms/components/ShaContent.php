<?php
/**
 * This class is a Content Managed Object (Cmo) used to store various contents with their types
 * ShaBddTable : shaoline_contact
 *
 * Ex :
 *
 * $content = new ShaContent();
 * $content->setValue('content_key', 'my_logo');
 * $content->setValue('language_id', '1');
 * $content->setValue('content_value', './img/logo.png');
 * $content->setValue('content_type', ShaFormField::RENDER_TYPE_PICTURE);
 * $content->save();
 *
 * $contextContent = new ShaContent();
 * echo $contextContent->draw('my_logo');
 * ...
 *
 * @package    Shaoline/Cms/Components
 * @category   ShaCmo class
 *
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <bastien.duho@free.fr>
 *
 * @version    1.0
 */
class ShaContent  extends ShaCmoTranslating
{ 

    const CONTENT_SEPARATOR = '[SHA_SEPARATOR]';
    
    const RENDER_TYPE_TEXT              = 0;
    const RENDER_TYPE_PICTURE 		= 1;
    const RENDER_TYPE_LINK 		= 2;
    const RENDER_TYPE_LINKEDPICTURE     = 3;

    /**
     * Return table name concerned by object
     *
     * @return string
     */
    public static function getTableName(){
        return "shaoline_content";
    }


    /**
     * Return SQL crating request
     *
     * @return string
     */
    public static function getTableDescription(){

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("content_key")->setType("VARCHAR(100)")->setPrimary()->end()
            ->addField("language_id")->setType("SMALLINT UNSIGNED")->setPrimary()->end()
            ->addField("content_value")->setType("TEXT")->end()
            ->addField("content_type")->setType("SMALLINT UNSIGNED")->end();

        return $table;

    }

    /**
     * Return array of field type descriptions
     *
     * @return array
     */
    public function defaultLineRender(){

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("language_id")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(ShaLanguage::getValuesMapping("language_id", "language_flag"))->setWidth(20)->end()
            ->addField()->setDaoField("content_key")->setLibEnable(false)->setWidth(150)->end()
            ->addField()->setDaoField("content_value")->setLibEnable(false)->setWidth(600)->end()
        ;
        return $form;

    }

    /**
     * Return array of field type descriptions for formulaire
     *
     * @return array
     */
    public function defaultEditRender(){

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->addField()->setDaoField("language_id")->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(ShaLanguage::getValuesMapping("language_id", "language_flag"))->setWidth(20)->end()
            ->addField()->setDaoField("content_key")->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setLib(ShaContext::t("Id"))->setWidth(600)->end()
            ->addField()->setDaoField("content_value")->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA)->setLib(ShaContext::t("Content"))->setWidth(600)->setHeight(300)->end()
        ;
        return $form;
        
    }

    /**
     * Return if add button is allowed in list mode
     *
     * @return bool
     */
    function allowAddBtn(){
        return false;
    }

    /**
     * Draw the translation of a content (if not existe, creat an empty entry in the datase)
     *
     * @param string $contentKey Id of content to translate
     * @param int $type Display mode
     * @param string $editMode Is edit mode
     *
     * @return string
     */
    public function draw($contentKey, $editMode = true) {
        $this->_checkingInsertion($contentKey, $editMode);
        $type = $this->getValue("content_type");
        return $this->internalDrawValue('content_value', $type, $editMode);
    }

    /**
     * Update sha_content entry using form 
     * 
     * @param ShaForm $form
     * 
     * @return ShaResponse
     */
    public static function updateFieldFromForm($form){
        
        $reponse = new ShaResponse();
        $reponse
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setRenderDomId(ShaContext::getNextContentId())
            ->getPopin()
            ->setColor("red")
            ->setTitle(ShaContext::t("error"))
        ;
        
        $data = $form->getDaoAdditionalDatas();
        if (!isset($data['type']) || !isset($data['primaryKey'])){
            $reponse->setContent(ShaContext::t('missing_content_info'));
            return $reponse->render();
        }
        
        $content = new ShaContent();
        if (!$content->load($data['primaryKey'])){
            $reponse->setContent(ShaContext::t('content_not_found'));
            return $reponse->render();
        }
        
        switch ($data['type']){
            
            case ShaContent::RENDER_TYPE_TEXT :
                $value = $form->getField('text')->getSubmitValue();
                $content->setValue('content_value', $value);
		break;                

            case ShaContent::RENDER_TYPE_LINK :
                $href = $form->getField('href')->getSubmitValue();
                $text = $form->getField('text')->getSubmitValue();
                $content->setValue('content_value', $href.ShaContent::CONTENT_SEPARATOR.$text);
		break;                

            case ShaContent::RENDER_TYPE_PICTURE :
                $alt = $form->getField('alt')->getSubmitValue();
                $picture = $form->getField('picture')->getSubmitValue();
                $ext = ShaUtilsFile::getExtension($picture);
                $filePath = "./uploads/sha_content_".$content->getValue('content_key').'_'.$content->getValue('language_id').'.'.$ext;
                rename ($picture, $filePath);
                $content->setValue('content_value', $alt.ShaContent::CONTENT_SEPARATOR.$filePath);
		break;                
                
            case ShaContent::RENDER_TYPE_LINKEDPICTURE :
                $href = $form->getField('href')->getSubmitValue();
                $alt = $form->getField('alt')->getSubmitValue();
                $picture = $form->getField('picture')->getSubmitValue();
                $ext = ShaUtilsFile::getExtension($picture);
                $filePath = "./uploads/sha_content_".$content->getValue('content_key').'_'.$content->getValue('language_id').'.'.$ext;
                rename ($picture, $filePath);
                $content->setValue('content_value', $href.ShaContent::CONTENT_SEPARATOR.$alt.ShaContent::CONTENT_SEPARATOR.$filePath);
		break;                
            
            default:
                $reponse->setContent(ShaContext::t('bad_content_type'));
                return $reponse->render();
        }
        
        $content->save();
        
        $type = $content->getValue('content_type');
        $newContentHtml = $content->internalDrawValue('content_value', $type, true);
        $updateContentWithJS = "Shaoline.currentEditedElement.html( UtilsString.decodeBase64('". base64_encode($newContentHtml)."') );";
        
        $reponse
            ->setContent(ShaContext::t('update_successfull'))
            ->addJsActions($updateContentWithJS)
            ->getPopin()
            ->setColor("blue")
            ->setTitle(ShaContext::t("update_ok"))
        ;
        
        return $reponse->render();
    }

    /**
     * Return  edit btn HTML
     *
     * @param field:string  field name
     * @param type:string   Render type
     *
     * @return HTML
     */
    private function _getEditBtnAction($field, $type, $value)
    {
        $form = new ShaForm();
        $form
            ->setSubmitFunction('ShaContent::updateFieldFromForm')
            ->setDaoAdditionalDatas(
                array(
                    'type' => $type, 
                    'primaryKey' => $this->getPrimaryKeysAsArray()
                )
            )
        ;

        switch ($type){
            
            case ShaContent::RENDER_TYPE_TEXT :

                $form->addField('text')->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA)->setWidth(350)->setHeight(100)->setValue($value)->end();
		break;                

            case ShaContent::RENDER_TYPE_LINK :
                
                $valuePart = explode(ShaContent::CONTENT_SEPARATOR, $value);
                $form->addField('href')->setLib('href_link_value')->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setValue($valuePart[0])->end();
                $form->addField('text')->setLib('wording_link_value')->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setValue($valuePart[1])->end();
                break;
                
            case ShaContent::RENDER_TYPE_PICTURE :
                
                $valuePart = explode(ShaContent::CONTENT_SEPARATOR, $value);
                $form->addField('alt')->setLib('picture_alt_value')->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setValue($valuePart[0])->end();
                $form->addField('picture')->setLib('picture_url_value')->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setValue($valuePart[1])->setFileTypeAllowed(array('png', 'jpg', 'bmp', 'ico', 'gif', 'svg', 'jpeg'))->end();
		break;
                
            case ShaContent::RENDER_TYPE_LINKEDPICTURE :

                $valuePart = explode(ShaContent::CONTENT_SEPARATOR, $value);
                $form->addField('href')->setLib('href_link_value')->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setValue($valuePart[0])->end();
                $form->addField('alt')->setLib('picture_alt_value')->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setValue($valuePart[1])->end();
                $form->addField('picture')->setLib('picture_src_value')->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setValue($valuePart[2])->setFileTypeAllowed(array('png', 'jpg', 'bmp', 'ico', 'gif', 'svg', 'jpeg'))->end();
                break;
        }      

        $form
            ->addField()->setRenderer(ShaFormField::RENDER_TYPE_SUBMIT)->setValue(ShaContext::t("update"))->end()
            ->save()
        ;
        
        $reponse = new ShaResponse();
        $reponse
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setRenderDomId(ShaContext::getNextContentId())
            ->setContent($form->renderDao($this))
            ->getPopin()
            ->setColor("blue")
            ->setTitle(ShaContext::t("update"))
        ;
        
        $operation = new ShaOperationAction();
        $operation
            ->setDoNothing(true)
            ->setShaResponse($reponse)
            ->save()
        ;
        return "gcid='".$operation->getGcId()."' onmouseover='Shaoline.manageEditPicto(event, \"".$operation->getGcId()."\")' ";
    }
    
    /**
     * Render value depending of the type
     * Add edit btn if wanted
     * 
     * @param field:string  field name
     * @param type:string   Render type
     * @param editMode:bool Add edit btn
     * 
     * @return HTML
     */
    protected function internalDrawValue($field, $type, $editMode){
        
        $resultHtml = "";
        $value = $this->getValue($field);
        $adminBtnHtml = ($editMode) ? $this->_getEditBtnAction($field, $type, $value) : "";
        switch ($type){
            case ShaContent::RENDER_TYPE_TEXT :
                if ($editMode && $value==null || $value=="") { $value="[NO_VALUE]"; }
                $resultHtml = "<p [SHA_ADMIN]>".$value."</p>";
                break;
                
            case ShaContent::RENDER_TYPE_LINK :
	
                if ($editMode && $value==null || $value=="") { $value="[NO_VALUE]".ShaContent::CONTENT_SEPARATOR."[NO_VALUE]"; }
                $values = explode(self::CONTENT_SEPARATOR, $value);
                if (count($values) != 2){
                    ShaUtilsLog::error("Bad LINK value (href, text) for field '$field' of class " . $this->getTableName() . " where " . $this->getPrimaryAsSql());
                    $resultHtml = "content bad value ! (see logs for more informations)";
                    break;
                }
                $resultHtml = "<a [SHA_ADMIN] href='".$values[0]."'>".$values[1]."</a>";
                break;
                
            case ShaContent::RENDER_TYPE_PICTURE :
                if ($editMode && $value==null || $value=="") { $value="[NO_VALUE]".ShaContent::CONTENT_SEPARATOR."[NO_VALUE]"; }
                $values = explode(self::CONTENT_SEPARATOR, $value);
                if (count($values) != 2){
                    ShaUtilsLog::error("Bad PICTURE value (alt, src) for field '$field' of class " . $this->getTableName() . " where " . $this->getPrimaryAsSql());
                    $resultHtml = "content bad value ! (see logs for more informations)";
                    break;
                }
                $resultHtml = "<img [SHA_ADMIN] alt='".$values[0]."' src='".$values[1]."?nocache=".uniqid()."' />";
                break;
                
            case ShaContent::RENDER_TYPE_LINKEDPICTURE :
                if ($editMode && $value==null || $value=="") { $value="[NO_VALUE]".ShaContent::CONTENT_SEPARATOR."[NO_VALUE]".ShaContent::CONTENT_SEPARATOR."[NO_VALUE]"; }
                $values = explode(self::CONTENT_SEPARATOR, $value);
                if (count($values) != 3){
                    ShaUtilsLog::error("Bad LINKEDPICTURE value (href, alt, src) for field '$field' of class " . $this->getTableName() . " where " . $this->getPrimaryAsSql());
                    $resultHtml = "content bad value ! (see logs for more informations)";
                    break;
                }
                $resultHtml = "<a [SHA_ADMIN] href='".$values[0]."'><img alt='".$values[1]."' src='".$values[2]."?nocache=".uniqid()."' /></a>";
                break;
        }        
        
        $result = str_replace("[SHA_ADMIN]", $adminBtnHtml, $resultHtml);
        return $result;
    }

    /**
     * Return HTML code specific field value with editor interface for a text type value
     *
     * @param string $contentKey Id of content to translate
     * @param boolean $editMode If true, add the javascript listener to editing value
     *
     * @return string code
     * @throws Exception Error description
     */
    public function drawText($contentKey, $editMode = false)
    {
        $this->_checkingInsertion($contentKey, $editMode);
        return $this->internalDrawValue('content_value', ShaContent::RENDER_TYPE_TEXT, $editMode);
    }

    /**
     * Return HTML code specific field value with editor interface for a text type value
     *
     * @param string $contentKey Id of content to translate
     * @param boolean $editMode If true, add the javascript listener to editing value
     *
     * @return string code
     * @throws Exception Error description
     */
    public function drawLink($contentKey, $editMode = false)
    {
        $this->_checkingInsertion($contentKey, $editMode);
        return $this->internalDrawValue('content_value', ShaContent::RENDER_TYPE_LINK, $editMode);
    }

    /**
     * Return HTML code specific field value with editor interface for a text type value
     *
     * @param string $contentKey Id of content to translate
     * @param boolean $editMode If true, add the javascript listener to editing value
     *
     * @return string code
     * @throws Exception Error description
     */
    public function drawPicture($contentKey, $editMode = false)
    {
        $this->_checkingInsertion($contentKey, $editMode);
        return $this->internalDrawValue('content_value', ShaContent::RENDER_TYPE_PICTURE, $editMode);
    }

    /**
     * Return HTML code specific field value with editor interface for a linked picture type value
     *
     * @param string $contentKey Id of content to translate
     * @param boolean $editMode If true, add the javascript listener to editing value
     *
     * @return string code
     * @throws Exception Error description
     */
    public function drawLinkedPicture($contentKey, $editMode = false)
    {
        $this->_checkingInsertion($contentKey, $editMode);
        return $this->internalDrawValue('content_value', ShaContent::RENDER_TYPE_LINKEDPICTURE, $editMode);
    }

    /**
     * Check if the translation of a content exist or not (if not existe, create an empty entry in the database)
     *
     * @param string $contentKey
     * @param string $type
     *
     * @throws Exception
     */
    private function _checkingInsertion($contentKey, $type) {
        if (!$this->load(array('content_key'=>$contentKey))) {
            ShaContext::bddInsert(
                "INSERT INTO shaoline_content
                (content_key,content_value,content_type,language_id)
                VALUES
                ('".$contentKey."','[no translation]',".$type.",".ShaContext::getLanguageId().")"
            );
            if (!$this->load(array('content_key' => $contentKey))) {
                ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Disable find content : $contentKey");
                throw new Exception(ShaContext::t("Fatal error occured"));
            }
        }
    }
	
}

?>