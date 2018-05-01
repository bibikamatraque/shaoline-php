<?php

class ShaOperationEditField extends ShaOperation
{

    protected $_daoClass = null;
    protected $_primary = array();
    protected $_field = array();
    protected $_type = "";



    const CONST_OPERATION_LIB = "OPERATION_EDIT_FIELD";

    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    /**
     * @param string $daoClass
     *
     * @return ShaOperationEditField
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getDaoClass()
    {
        return $this->_daoClass;
    }

    /**
     * @param array $field
     *
     * @return ShaOperationEditField
     */
    public function setField($field)
    {
        $this->_field = $field;
        return $this;
    }

    /**
     * @return array
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * @param array $primary
     *
     * @return ShaOperationEditField
     */
    public function setPrimary($primary)
    {
        $this->_primary = $primary;
        return $this;
    }

    /**
     * @return array
     */
    public function getPrimary()
    {
        return $this->_primary;
    }

    /**
     * @param string $type
     *
     * @return ShaOperationEditField
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Construct editing form
     *
     * @throws Exception
     * @return string
     */
    public function run(){

        //TODO use form

        /** @var ShaDao $instance */
        $instance = new ReflectionClass($this->_class);
        if (!$instance->load($this->getPrimary())) {
            throw new Exception(ShaContext::t("unknowObjectReference"));
        }

        $title = "";

        //// TEXT ///
        if ($this->getType() == ShaFormField::RENDER_TYPE_TEXT) {
            $content = "
					<div>
					    <textarea
					        class='simple_input'
					        [GCID]
					        id='cms_text'
					        rows=5
					        cols=45
					     >" .
                $instance->getValue($this->getField()) . "
                        </textarea>
					</div>";
        }

        //// RTF ////
        else if ($this->getType() == ShaFormField::RENDER_TYPE_RTF) {

            $content = "
					<div>
					    <textarea
					        class='simple_input'
					        [GCID]
					        id='cms_text'
					        rows=5
					        cols=45
					     >" .
                $instance->getValue($this->getField()) . "
                        </textarea>
					</div>";

        }

        //// PICTURE ////
        else if ($this->getType() == ShaFormField::RENDER_TYPE_PICTURE) {
            $content = "
					<div>
						<form enctype='multipart/form-data' action='cmsAsynchrone.php' method='GET'>
							<input type='hidden' value='2097152' name='MAX_FILE_SIZE'>
							<input type='file' id='cms_local_file' name='cms_local_file' style='cursor:pointer;opacity:0;width:0px;position:absolute;left:10px;' onchange='cms_pictureChanged()'>
							<div>
								<div style='margin-top:10px;width:100%;'>
									<input type='text' class='cms_input' [GCID] style='width:75%;float:left;' value='" . ShaContext::tj("yourFilPath") . "' id='cms_picture_path'>
									<div class='cms_upload_file'>
									</div>
								</div>
								<div style='clear:both'>
								</div>
								<div style='margin-top:2px;'>
									<p style='font-size:10px'>(" . ShaContext::t("maxFileSize500Ko") . ")</p>
								</div>
							</div>
						</form>
					</div>
					";

        }

        //// LINKEDPICTURE ////
        else if ($this->getType() == ShaFormField::RENDER_TYPE_LINKEDPICTURE) {

            $values = explode("|", $instance->getValue($this->_field));

            $content = "
					<div>
						" . ShaContext::t("link") . " : <input class='simple_input' type='text' id='cms_link' value='" . $values[0] . "'/>
						<br/>
						" . ShaContext::t("picture") . " :
						<br/>
						<img id='cms_apercu'  [GCID] style='height:50px;' alt='" . $values[0] . "'  src='" . $values[1] . "' />
						<br/>
						<form enctype='multipart/form-data' action='cmsAsynchrone.php' method='GET'>
							<input type='hidden' value='2097152' name='MAX_FILE_SIZE'>
							<input type='file' id='cms_local_file' name='cms_local_file' style='cursor:pointer;opacity:0;width:0px;position:absolute;left:10px;' onchange='cms_pictureChanged()'>
							<div>
								<div style='margin-top:10px;width:100%;'>
									<input type='text' class='cms_input' style='width:75%;float:left;' value='" . ShaContext::tj("yourFilPath") . "' id='cms_picture_path'>
									<div class='cms_upload_file'>
									</div>
								</div>
								<div style='clear:both'>
								</div>
								<div style='margin-top:2px;'>
									<p style='font-size:10px'>(" . ShaContext::t("maxFileSize500Ko") . ")</p>
								</div>
							</div>
						</form>
					</div>";

        } else if ($this->getType() == ShaFormField::RENDER_TYPE_LINK) {

            $content = "
                <div>
                    <textarea class='simple_input' [GCID] id='cms_text' rows=5 cols=45>" .
                        $instance->getValue($this->_field) . "
                    </textarea>
                </div>"
            ;

        } else {
            throw new Exception(
                __CLASS__."::".__FUNCTION__." : Render type uknonw : ".$this->getType()
            );
        }


        $ShaOperation = new ShaOperationEditField();
        $ShaOperation
            ->setClass($this->getClass())
            ->setField($this->getField())
            ->setPrimary($this->getPrimary())
            ->setType($this->getType())
          ;
        $gcId = $ShaOperation->save();
        $content = ShaUtilsString::replace($content, "[GCID]", " GcID='".$gcId."' ");

        return $content;
        //return Cms::popinConstructor($content, "cms-edit-".self::getNextContentId(), $title, "cms_updateValue('".$configuration['name']."','".ShaUtilsString::getASCII($configuration['type'])."','".$gcId."')", ShaDao::getTranslatorInstance()->t("updateValue"), "blue");

    }


    /**
     * Change the value of extended abstractCMO object field
     *
     * @param type $config Editing configuration
     *
     * @return New values of field
     * @throws Exception Error description
     */
    /*private static function _changeFieldValue($config){

        //Load CMO object
        $abstractCmoObject = new $config['class']();
        if (!$abstractCmoObject->load($config['primary'])) {
            throw new Exception(ShaContext::t("unknowObjectReference"));
        }

        //If picture load it ...
        if (isset($config['picture'])) {
            $index = strrpos($config['picture']['name'], ".");
            $extention = substr($config['picture']['name'], $index + 1, strlen($config['picture']['name']) - $index - 1);
            $newFileName = $config['class'] . "_" . $abstractCmoObject->getHashPrimaryValues();
            //Check if class directory exist
            if (!is_dir("shaoline/resources/img/upload/".$config['class'])) {
                if (!mkdir("shaoline/resources/img/upload/".$config['class'])) {
                    throw new Exception(Dao::getTranslatorInstance()->t("disableToCreateFolder"));
                }
            }
            $targetPath = "shaoline/resources/img/upload/" .$config['class']. "/" . $newFileName . "." . $extention;
            if (!move_uploaded_file($config['picture']['tmp_name'], $targetPath)) {
                throw new Exception(Dao::getTranslatorInstance()->t("disableToMoveUploadedFile"));
            }
            if ($config['type'] == AbstractCmo::RENDER_TYPE_PICTURE) {
                $config['value'] = $targetPath;
            } else {
                $config['value'] = $config['link'] . "|" . $targetPath;
            }
        }

        //Save new value
        $abstractCmoObject->setPersistentValue($config['field'], $config['value']);
        $width = (isset($config['width']))?$config['width']:0;
        $editMode = (!isset($config['editMode']))?$config['editMode']:false;
        $defaultValue = (isset($config['defaultValue']))?$config['defaultValue']:"";
        return "OK|" . $abstractCmoObject->internalDrawValue($config['field'], $config['type'], $editMode, $width, $defaultValue);

    }*/

}

?>