<?php

/**
 * Class ShaForm
 *
 * PUT HERE YOUR DESCRIPTION
 *
 * @author      Bastien DUHOT <bastien.duhot@free.fr>
 * @version     1.0.0
 * @category    ?
 * @namespace   ${NAMESPACE}
 * @licence     Please contact Bastien DUHOT
 */
class ShaForm extends ShaSerializable{


    //Form ShaOperation
    const ShaOperation_ADD 	= 0;
    const ShaOperation_UPDATE = 1;


    /**************/
    /* ATTRIBUTES */
    /**************/

    //Fields attributes
    protected $_fields = array();

    //Submit attribute
    protected $_beforeSave = "";
    protected $_afterSave = "";
    protected $_submitFunction = "";
    protected $_autoClear = false;
    protected $_redirect = "";
    protected $_successMessage = "";
    protected $_errorMessage = "";

    //Form attributes
    protected $_domId = null;
    protected $_isSubmitable = true;
    protected $_gcId = null;
    protected $_gcOnlyOneHit = false;
    protected $_cssClass = "";

    //Internal attributes
    protected $_gcKey = "";
	protected $_currentFieldIndex = 0;

    //Dao attributes
    protected $_daoClass = null;
    protected $_daoPrimaryKeys = null;
    protected $_daoAdditionalDatas = null;
    protected $_saveDao = false;

    protected $_relation = null;

    protected $_lineHeight = null;

    protected $_inputWidth = null;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * @param mixed $afterSave
     *
     * @return ShaForm
     */
    public function setAfterSave($afterSave)
    {
        $this->_afterSave = $afterSave;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAfterSave()
    {
        return $this->_afterSave;
    }

    /**
     * @param mixed $autoClear
     *
     * @return ShaForm
     */
    public function setAutoClear($autoClear)
    {
        $this->_autoClear = $autoClear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoClear()
    {
        return $this->_autoClear;
    }

    /**
     * @param mixed $beforeSave
     *
     * @return ShaForm
     */
    public function setBeforeSave($beforeSave)
    {
        $this->_beforeSave = $beforeSave;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInputWidth()
    {
    	return $this->_inputWidth;
    }

    /**
     * @param mixed $inputWidth
     *
     * @return ShaForm
     */
    public function setInputWidth($inputWidth)
    {
    	$this->_inputWidth = $inputWidth;
    	return $this;
    }

    /**
     * @return mixed
     */
    public function getBeforeSave()
    {
        return $this->_beforeSave;
    }

    /**
     * @param mixed $cssClass
     *
     * @return ShaForm
     */
    public function setCssClass($cssClass)
    {
        $this->_cssClass = $cssClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCssClass()
    {
        return $this->_cssClass;
    }

    /**
     * @param mixed $domId
     *
     * @return ShaForm
     */
    public function setDomId($domId)
    {
        $this->_domId = $domId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomId()
    {
        return $this->_domId;
    }

    /**
     * @param mixed $errorMessage
     *
     * @return ShaForm
     */
    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @param $id
     *
     * @return ShaFormField
     */
    public function addField($id = null)
    {
    	
        $field = new ShaFormField($this);
        if (isset($id)) {
            $field->setId($id);
        } else {
        	$field->setId($this->_currentFieldIndex);
        }
        $this->_fields[] = $field;
        $this->_currentFieldIndex++;
        return $field;
    }

    /**
     * @param $id
     *
     * @return ShaFormField
     */
    public function insertFieldBefore($referredFieldId, $newFieldId){
        $newField = new ShaFormField($this);
        $newField->setId($newFieldId);

        $index = -1;
        foreach ($this->_fields as $key => $field){
            if ($field->getId() == $referredFieldId) {
                $index = $key;
            }
        }

        $position = ShaUtilsArray::insert($this->_fields, $index, array($newField));
        return $this->_fields[$position];
    }
    
    /**
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Return field by field id
     *
     * @param string $id
     *
     * @return ShaFormField
     */
    public function getField($id) {
        $fields = $this->getFields();
        /** @type ShaFormField $field*/
        foreach ($fields as $field) {
            if ($field->getId() === $id) {
                return $field;
            } 
        }
        return null;
    }

    /**
     * @param mixed $redirect
     *
     * @return ShaForm
     */
    public function setRedirect($redirect)
    {
        $this->_redirect = $redirect;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->_redirect;
    }

    /**
     * @param mixed $successMessage
     *
     * @return ShaForm
     */
    public function setSuccessMessage($successMessage)
    {
        $this->_successMessage = $successMessage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuccessMessage()
    {
        return $this->_successMessage;
    }

    /**
     * @param mixed $gcKey
     *
     * @return ShaForm
     */
    public function setGcKey($gcKey)
    {
        $this->_gcKey = $gcKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGcKey()
    {
        return $this->_gcKey;
    }

    /**
     * @param mixed $gcOnlyOneHit
     *
     * @return ShaForm
     */
    public function setGcOnlyOneHit($gcOnlyOneHit)
    {
        $this->_gcOnlyOneHit = $gcOnlyOneHit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGcOnlyOneHit()
    {
        return $this->_gcOnlyOneHit;
    }

    /**
     * @return mixed
     *
     * @return ShaForm
     */
    public function getCurrentFieldIndex()
    {
        return $this->_currentFieldIndex;
    }

    /**
     * @return mixed
     */
    public function getDaoClass()
    {
        return $this->_daoClass;
    }

    /**
     * @param mixed $daoClass
     *
     * @return ShaForm
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }

    /**
     * @return null
     */
    public function getGcId()
    {
        return $this->_gcId;
    }

    /**
     * @return null
     */
    public function setGcId($gcId)
    {
        return $this->_gcId = $gcId;
    }

    /**
     * @return boolean
     */
    public function isSubmitable()
    {
        return $this->_isSubmitable;
    }

    /**
     * @param boolean $isSubmitable
     *
     * @return ShaForm
     */
    public function setSubmitable($isSubmitable)
    {
        $this->_isSubmitable = $isSubmitable;
        return $this;
    }

    /**
     * @param null $daoPrimaryKeys
     *
     * @return ShaForm
     */
    public function setDaoPrimaryKeys($daoPrimaryKeys)
    {
        $this->_daoPrimaryKeys = $daoPrimaryKeys;
        return $this;
    }

    /**
     * @return null
     */
    public function getDaoPrimaryKeys()
    {
        return $this->_daoPrimaryKeys;
    }

    /**
     * @param null $additionalDatas
     *
     * @return ShaForm
     */
    public function setDaoAdditionalDatas($daoAdditionalDatas)
    {
        $this->_daoAdditionalDatas = $daoAdditionalDatas;
        return $this;
    }

    /**
     * @return null
     */
    public function getDaoAdditionalDatas()
    {
        return $this->_daoAdditionalDatas;
    }

    /**
     * @param string $submitFunction
     *
     * @return ShaForm
     */
    public function setSubmitFunction($submitFunction)
    {
        $this->_submitFunction = $submitFunction;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubmitFunction()
    {
        return $this->_submitFunction;
    }

    /**
     * @param boolean $saveDao
     *
     * @return ShaForm
     */
    public function setSaveDao($saveDao)
    {
        $this->_saveDao = $saveDao;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSaveDao()
    {
        return $this->_saveDao;
    }

    /**
     * @param mixed $relation
     *
     * @return ShaForm
     */
    public function setRelation($relation)
    {
        $this->_relation = $relation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return $this->_relation;
    }

    /**
     * @param mixed $lineHeight
     *
     * @return ShaForm
     */
    public function setLineHeight($lineHeight)
    {
        $this->_lineHeight = $lineHeight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineHeight()
    {
        return $this->_lineHeight;
    }

    
    public function getAdditionalJs(){
    	$result = array();
    	foreach ($this->_fields as $field){
    		$result = array_merge($result, $field->getAdditionalJs());
    	}
    	return $result;
    }

    /*********************/
    /* SPECIFIC FUNCTION */
    /*********************/

    /**
     * Calculate fields index
     */
    public function autoSetIndex(){

    	$index = 0;
    	/** @var ShaFormField &$field */
    	foreach ($this->_fields as &$field) {
    		if ($field->getMustSubmit()) {
    			$field->setIndex($index);
    			$index++;
    		}
    	}
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function render() {

        /** @type ShaCmo $instance */
        $instance = null;
        if (isset($this->_daoClass) && is_array($this->_daoPrimaryKeys)) {
            $class = $this->_daoClass;
            $instance = new $class();
            if (!$instance->load($this->_daoPrimaryKeys)) {
            	ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Unable to load ShaCmo '$class'");
            	throw new Exception(ShaContext::t("Fatal error occured"));
            }
        }

        if (!isset($this->_gcId)) {
            $this->save();
        }


        //Construct form name
        $formName = "form_".$this->_domId;

        if ($this->_isSubmitable) {

            $render = "
                <div id='".$this->_domId."' onsubmit='Shaoline.submitForm(".$this->_gcId.", \"".$this->_domId."\")' >
                    <table class='cms_formulaire' id='$formName'>
                        [DATAS]
                    </table>
                </div>"
            ;

        } else {

            $render = "
                <table class='cms_formulaire' id='$formName'>
                    [DATAS]
                </table>"
            ;

        }

        $lineIndex = 0;
        $strField="";

        /** @var ShaFormField &$field */
        foreach ($this->_fields as &$field) {

        	if ($this->_inputWidth != null && $field->getWidth() == null)
        		$field->setWidth($this->_inputWidth);

            //Width and height
            $styleWidth = ($field->getWidth() != null) ? "style='width: ".$field->getWidth()."px;'" : "";
            $styleHeight = (isset($this->_lineHeight)) ? "style='height: ".$this->_lineHeight."px;'" : "";

            //Force no lib if submit button
            if ($field->getRenderer() == ShaFormField::RENDER_TYPE_SUBMIT) {
                $field->setLibEnable(false);
            }

            //Default  renderer = TEXT
            if ($field->getRenderer() == null) {
                $field->setRenderer(ShaFormField::RENDER_TYPE_TEXT);
            }

            // Force not editable if not submittable
            if (!$this->isSubmitable()) {
                $field->setEditable(false);
            }

            //If TD width for picture renderer
            if ($field->getRenderer() == ShaFormField::RENDER_TYPE_PICTURE ){
                $styleWidth = '';
            }

            //Lines
            $strField .= "<tr class='shaoline_list_item ".$field->getCssClass()." ".$formName."_".$field->getId()."_line' $styleWidth>";

            //Colspan
            $keyLineSize = ($field->isInputEnable()) ? 1 : 2;
            $valueLineSize = ($field->isLibEnable()) ? 1 : 2;

            //Align text for not lib field
            $textAlign = "";
            if ($field->isInputEnable() && !$field->isLibEnable()) {

                if ($field->getTextAlign() != ""){
                    $textAlign = " style='text-align:".$field->getTextAlign().";' ";
                } else {
                    $textAlign = "";//" style='text-align:center;' ";
                }
            }

            //Construct 'lib' part
            if ($field->isLibEnable() && $field->getRelation() == null) {
                $strField .= "<td $styleHeight colspan='$keyLineSize' class='cms_form_key ".$formName."_".$field->getId()."_key'>".$field->getLib()."</td>";
            }

            //If relation button
            if ($field->getRelation() != null && isset($instance)) {
            	
                /** @type ShaOperation $operation */
                $operation = ShaForm::getReferenceOperation($instance, $field->getRelation());
                $strField .= "<td $styleHeight colspan='2' ".$operation->getDomEvent().">".$field->getLib()."</td>";
                
            } else {

                if ($field->isInputEnable()) {

                    //Draw value
                    $strField .= "<td $styleHeight colspan='$valueLineSize' $textAlign class='cms_form_value ".$formName."_".$field->getId()."_value'>";

                    //Dynamic renderer
                    //TODO : finish it
                    /*$dynamicRenderer = explode("[FIELD]", $item['renderer']);
                    if (count($dynamicRenderer)==2) {
                        $item['renderer'] = $cmsObject->getValue($dynamicRenderer[1]);
                    }*/

                    //Draw field
                    $strField .= $field->render();

	                if ($field->hasChecker()){
	                   	$strField .= "</td><td class='cms_form_checker' id='form_checker_".$this->_domId."_".$field->getId()."'>";
	                } else {
	                    $strField .= "</td><td>";
	                }
                }
            }

            $strField .= "</td></tr>";
            $lineIndex++;
        }

        return ShaUtilsString::replace($render, "[DATAS]", $strField);
    }

    /**
     * Save from into garbadge
     *
     * @return int
     */
    public function save() {

    	$tokenField = $this->getField("_token");
    	if ($tokenField == null) {
    		$this->addField("_token")->setRenderer(ShaFormField::RENDER_HIDDEN)->setFormat(ShaUtilsString::PATTERN_ALPHANUM);
    	} else {
    		$tokenField->setValue(ShaUtilsCapcha::getRandomCode(50));
    	}
    	
    	$this->autoSetIndex();

    	//TODO delete that, make dom id necessary
        if (!isset($this->_domId)) {
            $this->_domId = ShaContext::getNextContentId();
        }
        
        $this->_gcId = ShaGarbageCollector::addItem(ShaForm::stringify($this), $this->_gcOnlyOneHit, $this->_gcKey);
        return $this->_gcId;
    }

    public function update(){
    	ShaGarbageCollector::updateItem(ShaForm::stringify($this), $this->_gcId);
    }

    /**
     * Add css class to all fileds
     *
     * @param $class
     */
    public function addCssToFields($class) {
        /** @type ShaFormField $field */
        foreach ($this->_fields as $field) {
            $field->setCssClass($this->getCssClass() . " " . $class);
        }
    }


    /**
     * Return form for ShaDao object
     *
     * @param ShaDao $instance ShaDao object used to fill form
     *
     * @return string
     */
    public function renderDao($instance) {

        //Scann all key
        $this->getDaoClass(get_class($instance));
        $this->getDaoPrimaryKeys($instance->getPrimaryKeysAsArray());
        $fields = $this->_fields;
        /** @type ShaFormField $field */
        foreach ($fields as &$field) {

            $fieldName = $field->getDaoField();

            if ($fieldName != null) {

                //search in field and in relation
                if ($instance->hasField($fieldName)) {
                    $field->setValue($instance->getValue($fieldName));
                }

                //search in relation
               /* if (array_key_exists($item['key'], $directRelations)) {
                    $item['selectedValues'] = array();
                    $groupDao = null;
                    $values = $cmsObject->getRelation($item['key']);
                    foreach ($values as $value) {
                        $groupDao = get_class($value);
                        $item['selectedValues'][] = implode("#", $value->getPrimaryArray());
                    }
                    $item['values'] = AbstractCmo::getPrimaryValuesForFormulaire($groupDao, $item['valueKeyFieldName']);
                }*/
            }

        }

        return $this->render();

    }

    /**
     * Threat formulaire submit
     *
     * @param array $gcId ShaFormulaire config
     * @param array $values ShaFormulaire POST values
     *
     * @return string
     */
    public static function submitForm($gcId, $values) {

        //Get form description from garbage and unstringify it
        $code = ShaGarbageCollector::getItem($gcId);
        /** @type ShaForm $form */
        $form = self::unstringify($code);

        //If not ShaForm => fatal error
        if (get_class($form) != "ShaForm") {
            return self::_errorFormatter(ShaContext::t("You propably lost your session. Please reload the page ! "));
        }
        $form->setGcId($gcId);

        //Getting all value
        try {

            $values = json_decode($values);

        } catch (Exception $e) {
            return self::_errorFormatter(ShaContext::t("Unknow error during decoding form datas !"));
        }

        //Does form concerne existing DaoObject
        /** @type ShaDao $instance */
        $instance=null;
        if ($form->getDaoClass() != null && class_exists($form->getDaoClass())) {

            $class = $form->getDaoClass();
            $instance = new $class();

            //Load ShaDao if update mode
            if ($form->getDaoPrimaryKeys() != null) {
                if (!$instance->load($form->getDaoPrimaryKeys())) {
                    return self::_errorFormatter(ShaContext::t("Uknown ShaDao object !"));
                }
            }

            //Set additional informations if necessary
            $daoAdditionalDatas = $form->getDaoAdditionalDatas();
            if (isset($daoAdditionalDatas)) {
                foreach ($daoAdditionalDatas as $key => $value) {
                    if (!$instance->hasField($key)) {
                        return self::_errorFormatter(ShaContext::tt("The field '%0%' does not exist for dao '%1%'", array($key, $class)));
                    }
                    $instance->setValue($key, $value);
                }
            }
        }

        //Read all form input
        $index = 0;
        $lastFieldValue = "";
        $lastFieldHumanName = "";
        $fields = $form->getFields();
        
		if (ShaContext::rsaActivated()){
			 $rsaKey = ShaSession::get("rsaKeyId");
		}
       
        //Check all fields
        /** @type ShaFormField $field */
        foreach ($fields as &$field) {

            //Determine humain readable name for field
            $fieldHumanName = ($field->getLib() != null) ? $field->getLib() : $field->getPlaceholder();
            $fieldHumanName = (isset($fieldHumanName)) ? $fieldHumanName : $field->getDaoField();

            //Check if field must be controlled
            if ($field->getMustSubmit()) {

            	//If TD width for picture renderer
            	if ( $field->getRenderer() == ShaFormField::RENDER_TYPE_PICTURE ){
            		try {
            			
                        if (!isset( $_FILES['FILE_' . $index])){
                            $values[$index] = array("");
                        } else {

                            $file = $_FILES['FILE_' . $index];

                            if (!isset($file['tmp_name'])) {
                                return self::_errorFormatter(ShaContext::t("no_uploaded_file_found"));
                            }
                            if (!isset($file['name'])) {
                                return self::_errorFormatter(ShaContext::t("no_uploaded_file_name_found"));
                            }
                            if (
                                (!isset($file['size'])) ||
                                (!ShaUtilsString::isRegexPositiveInteger($file['size'])) ||
                                $file['size'] < 10
                            ) {
                                ShaContext::deleteTmpFile($file);
                                return self::_errorFormatter(ShaContext::t("uploaded_file_size_too_small"));
                            }

                            $ext = ShaPicture::getFormat($file['name']);
                            if (!$field->isImageTypeAllowed($ext)){
                                ShaContext::deleteTmpFile($file);
                                return self::_errorFormatter(ShaContext::t("bad_uploaded_file_extension"));
                            }

                            if ($field->getFileMaxSize() < $file['size']){
                                ShaContext::deleteTmpFile($file);
                                return self::_errorFormatter(ShaContext::tt("Image to big (max %size%)", $field->getFileMaxSize()));
                            }

                            $newPath = ShaContext::uploadFile($file);
                            if ($newPath == ""){
                                return self::_errorFormatter(ShaContext::t("error_during_file_upload"));
                            }
                            $values[$index] = array($newPath);
                        }

            		} catch (Exception $e){
                            return self::_errorFormatter($e->getMessage());
            		}
            	}
            	
                //Check if data exist
                if (!isset($values[$index])) {
                    return self::_errorFormatter(ShaContext::tt("no_value_found_for_field_%0%", $fieldHumanName));
                }

                //Sepcific check and radio fields
                $valueFound = true;
                if ($field->getRenderer() == ShaFormField::RENDER_TYPE_RADIOBOX || $field->getRenderer() == ShaFormField::RENDER_TYPE_CHECKBOX){

                    $value = null;
                    $valueFound = false;
                    $options = $values[$index];
                    foreach ($options as $k => $v){
                        if ($v){
                            $value = $k;
                            $valueFound = true;
                            break;
                        }
                    }
                } else {
                    $value = $values[$index][0];
                }

                //Force max length security
                if ($field->getMaxLength() == null) {
                    $field->setMaxLength(1000);
                }


                if (is_array($value)){
                	
                    //Check if value is necessary
                    if (!$field->isAllowEmpty() && (count($value) == 0)) {
                    	return self::_errorFormatter(ShaContext::tt("field_%0%_is_required", "'".$fieldHumanName."'"));
                    }
                    if (!$field->isMultipleSelection() && count($value) > 1){
                        return self::_errorFormatter(ShaContext::tt("only_one_value__for_field_%0%", "'".$fieldHumanName."'"));
                    }
                    
                } else {

                	if ($field->isRsaPretected() && ShaContext::rsaActivated()){
                            $value = $rsaKey->decrypt($value);
                	}
                	
                    //Check max value
                    if ((strlen($value) > 0) && $field->getMaxLength() != null && strlen($value) > $field->getMaxLength()) {
                    	return self::_errorFormatter(ShaContext::tt("field_%0%_is_too_long_%1%", array("'".$fieldHumanName."'", $field->getMaxLength())));
                    }
                    //Check min length
                    if ((strlen($value) > 0) && $field->getMinLength() != null && strlen($value) < $field->getMinLength()) {
                    	return self::_errorFormatter(ShaContext::tt("field_%0%_is_too_short_%1%", array("'".$fieldHumanName."'", $field->getMinLength())));
                    }
                    //Check max value
                    if ((strlen($value) > 0) && $field->getMaxValue() && ($value > $field->getMaxValue())) {
                    	return self::_errorFormatter(ShaContext::tt("field_%0%_contains_to_hight_value_%1%", array("'".$fieldHumanName."'", $field->getMaxValue())));
                    }
                    //Check min value
                    if ((strlen($value) > 0) && $field->getMinValue() && ($value < $field->getMinValue())) {
                    	return self::_errorFormatter(ShaContext::tt("field_%0%_contains_to_low_value_%1%", array("'".$fieldHumanName."'", $field->getMinValue())));
                    }
                    //Check if value is necessary
                    if (!$field->isAllowEmpty() && ($value === "" || !$valueFound)) {
                    	return self::_errorFormatter(ShaContext::tt("field_%0%_is_required", "'".$fieldHumanName."'"));
                    }
                    //Check comlexity
                    $complexity = $field->getComplexity();
                    if ($complexity != null && is_array($complexity)) {
                    	$passwordManager = new ShaUtilsPassword($complexity);
                    	if (!$passwordManager->isValidePassword($value)){
                    		return self::_errorFormatter(ShaContext::tt("field_%0%_not_enouth_complexe", "'".$fieldHumanName."'"));
                    	}
                    }

                    //Check input pattern
                    if ($field->getFormat() != null && $value !== "" && !ShaUtilsString::isRegex($value, $field->getFormat())) {
                    	return self::_errorFormatter(ShaContext::tt("bad_value_for_field_%0%", "'".$fieldHumanName."'"));
                    }
                    //Bis mode
                    if ($field->isBis() && $value != $lastFieldValue) {
                    	return self::_errorFormatter(ShaContext::tt("files_%0%_and_%1%_must_have_the_same_value", array("'".$lastFieldHumanName."'", "'".$fieldHumanName."'")));
                    }
                    
                }
                

                //Check if value allowed for list component
                $renderer = $field->getRenderer();
                if ($renderer == ShaFormField::RENDER_TYPE_COMBOBOX ||
                    $renderer == ShaFormField::RENDER_TYPE_CHECKBOX ||
                    $renderer == ShaFormField::RENDER_TYPE_CHECKBOX_LIST ||
                    $renderer == ShaFormField::RENDER_TYPE_RADIOBOX_LIST ||
                    $renderer == ShaFormField::RENDER_TYPE_RADIOBOX
                ) {
                    $data = $field->getDatas();

                    if ($field->isAutoFilled()){
                        $data = $field->getAutoFillValues($form);
                    }

                    if ($value != null && !array_key_exists($value, $data)) {
                        return self::_errorFormatter(ShaContext::tt("Bad value for field '%0%'", $fieldHumanName));
                    }
                }


                //Cryptage
                $valueToSave = $value;
                if ($field->getCrypt() != null) {
                    $cryptFunction = ShaUtilsString::replace($field->getCrypt(), "[VALUE]", $value);
                    eval("\$valueToSave = $cryptFunction;");
                }

                //Only if felt
                $bSaveDatas = true;
                if ($field->isSaveOnlyIfNotEmpty()) {
                    //TODO : it may have some trouble during checkings datas on not necessary value
                    if ($value == "") {
                        $bSaveDatas = false;
                    }
                }

                //Specific capcha
                if ($field->getRenderer() == ShaFormField::RENDER_TYPE_CAPCHA) {
                    $value = $values[$index][0];
                    $capcha = $field->getCapcha();
                    if ( !isset($capcha) || $capcha['capchaValue'] != $value ) {
                        return self::_errorFormatter(ShaContext::t("Bad security code"));
                    }
                }

                //Save submitted value
                $field->setSubmitValue($value);

                $lastFieldValue = $value;
                $lastFieldHumanName = $fieldHumanName;

                //If it is ShaDao field, check format
                if ($bSaveDatas) {
                    if ($field->getDaoField() != null && $instance != null) {
                        $instance->setValue($field->getDaoField(), $valueToSave);
                    } else {
                        $field->setSubmitValue($value);
                    }
                }

                $index++;
            }
        }
        try{
            $sShaResponse = "";

            //Last custom checking
            if ($form->getBeforeSave() != null) {
                eval("\$sShaResponse = ".$form->getBeforeSave()."(\$form, \$instance);");
            }

            //If no error
            if ($sShaResponse=="") {

                if ($form->getSaveDao() && isset($instance)) {
                    $instance->save();
                }
                if ($form->getAfterSave() != null) {
                    eval($form->getAfterSave()."(\$form, \$instance);");
                }
                if ($form->getAutoClear()) {
                    ShaGarbageCollector::deleteEntry($form->getGcId());
                }
                if ($form->getRedirect()) {
                    ShaPage::redirect($form->getRedirect());
                }
                if ($form->getSubmitFunction()) {
                    $ShaResponse = null;
                    eval("\$shaResponseRender = ".$form->getSubmitFunction()."(\$form, \$instance);");
                    return $shaResponseRender;
                }
                else {
                    if ($form->getSuccessMessage()) {
                        return self::_goodFormatter(ShaContext::t($form->getSuccessMessage()));
                    } else {
                        return self::_goodFormatter(ShaContext::t("Your form has been successfully saved !"));
                    }
                }
            } else {
                return self::_errorFormatter($sShaResponse);
            }

        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * ShaFormat msg adding css class for 'error'
     *
     * @param string $msg Message to format
     *
     * @return string
     */
    protected static function _errorFormatter($msg) {

        ShaUtilsLog::info("Form validation error : ".$msg);

        $ShaResponse = new ShaResponse();
        $ShaResponse
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($msg)
            ->getPopin()
                ->setColor("red")
                ->setTitle(ShaContext::t("Error"))
        ;

        $ShaResponse->render();
    }

    /**
     * ShaFormat msg adding css class for 'all is good'
     *
     * @param string $msg Message to format
     *
     * @return string
     */
    protected static function _goodFormatter($msg) {
        $ShaResponse = new ShaResponse();
        $ShaResponse
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($msg)
            ->getPopin()
            ->setColor("blue")
            ->setTitle(ShaContext::t("Validation"))
        ;

        $ShaResponse->render();
    }

    /**
     * Draw 'goto' reference from line
     *
     * @param ShaCmo $instance cmo Instance
     * @param ShaReference $reference
     *
     * @return string
     */
    public static function getReferenceOperation($instance, $referenceName)
    {
        $reference = $instance->getReference($referenceName);
        $relation = $reference->generateRelation($instance);
        $relationType = $relation->getType();

        $shaResponse = new ShaResponse();
        $shaResponse
        	->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
        	->getPopin()
        		->setTitle(ShaContext::t("relation"))
        		->setColor("blue")
        ;
        
        //1>1 (One A to one B, A key into B table)   (no through)
        if ($relationType == "1>1") {

            $classB = $relation->getClassB()->getClass();
            $commonKey = $instance->getArrayWithValues($relation->getLinkAToB()->getCommonKeys());
            /** @type ShaCmo  $instanceB */
            $instanceB = new $classB();

            if (!$instanceB->load($commonKey)){
                $instanceB->fillKeysWithValues($commonKey);
                $instanceB->save();
            }

            $ShaOperation = new ShaOperationEdit();
            $ShaOperation
                ->setDaoClass($classB)
                ->setDaoKeys($commonKey)
                ->setShaResponse($shaResponse)
                ->save()
            ;

            return $ShaOperation;
        }

        //1<1 (One A to one B, B key into A table)   (no through)
        if ($relationType == "1<1") {

            $isAllFieldFilled = true;
            $classB = $relation->getClassB()->getClass();
            $commonKey = $instance->getArrayWithValues($relation->getLinkAToB()->getCommonKeys());
            /** @type ShaCmo  $instanceB */
            $instanceB = new $classB();

            foreach ($commonKey as $key => $value){
                if (empty($value)){
                    $isAllFieldFilled = false;
                }
            }
            $ShaOperation = null;
            if ($isAllFieldFilled && $instanceB->load($commonKey)){
                $ShaOperation = new ShaOperationEdit();
                $ShaOperation
                    ->setDaoClass($classB)
                    ->setDaoKeys($commonKey)
                    ->save()
                ;
            } else {
                $ShaOperation = new ShaOperationList();
                $ShaOperation
                    ->setDaoClass($classB)
                    ->setRelation($relation)
                    ->setActiveAddButton(true)
                    ->setActiveDeleteButton(true)
                	->setShaResponse($shaResponse)
                    ->save()
                ;
            }
            return $ShaOperation;
        }

        //1>n (One A for some B, A key into B tables)    (no through)
        if ($relationType == "1>n") {

        	$classB = $relation->getClassB()->getClass();
        	$commonKey = $instance->getArrayWithValues($relation->getLinkAToB()->getCommonKeys());

        	$ShaOperation = new ShaOperationList();
        	$ShaOperation
	        	->setDaoClass($classB)
	        	->setRelation($relation)
	        	->setActiveAddButton(true)
	        	->setActiveDeleteButton(true)
                ->setShaResponse($shaResponse)
	        	->setCondition(ShaUtilsArray::arrayToSqlCondition($commonKey))
	        	->save()
        	;
        	
        	return $ShaOperation;
        	
        }

        //n<1 (One B for some A, B key into A tables)    (no through)
        if ($relationType == "n<1") {

        }

        //1:n (One A for some B, A/B key couples into C table)    (need through)
        if ($relation->isTriplet()) {

            $shaOperation = new ShaOperationList();
            $shaOperation
                ->setDaoClass($relation->getClassA()->getClass())
                ->setRelation($relation)
                ->setActiveAddButton(true)
                ->setActiveDeleteButton(true)
                ->setShaResponse($shaResponse)
                ->save();
            ;

           /* $operation = new ShaOperationAction();
            $operation
                ->setDaoClass("ShaController")
                ->setDaoMethod("popinAction")
                ->setParameters(
                    array(
                        "gcId" =>  $shaOperation->getGcId(),
                        "title" => ShaContext::t("Adding")
                    )
                )
                ->save()
            ;*/
            return $shaOperation;

        }

    }
    
    public static function updateCapcha($param){
    	
    	
    	if (
    		(!isset($param['fieldId'])) ||
    		(!isset($param['gcId'])) || 
    		(!isset($param['capchaDivDomId']))
    	){
    		return self::_errorFormatter(ShaContext::t("Bad parameters !"));
    	}
    	
    	$code = ShaGarbageCollector::getItem((int)$param['gcId']);
    	if ($code == null){
    		return self::_errorFormatter(ShaContext::t("Form not found (1)"));
    	}
    	$form = self::unstringify($code);
    	if (get_class($form) != "ShaForm") {
    		return self::_errorFormatter(ShaContext::t("Form not found (2)"));
    	}
    	$form->setGcId((int)$param['gcId']);
    	
    	$field = $form->getField($param['fieldId']);
    	if ($field != null) {
    		$capcha = $field->getCapcha();
    		$capcha['capchaValue'] = ShaUtilsCapcha::getRandomCode( $capcha['capchaLength']);
    		$field->setCapcha($capcha);
    		$form->update();
    		
    		$sCapcha = ShaFormField::generateCapchaField($field, $param['gcId'], $param['capchaDivDomId']);
    		
    		$response = new ShaResponse();
    		$response
    			->setRenderer(ShaResponse::CONST_RENDERER_DIV)
    			->setContent($sCapcha)
    			->setRenderDomId($param['capchaDivDomId'])
    			->render()
    		;
    		return;
    			
    	} else {
    		return self::_errorFormatter(ShaContext::t("Field not found"));
    	}
    	
    	
    }


}

?>