<?php

class ShaOperationEdit extends ShaOperation {

    /** @type string $_daoClass  */
    protected $_daoClass = null;
    /** @type array $_daoKeys */
    protected $_daoKeys = null;

    const CONST_OPERATION_LIB = "OPERATION_EDIT";

    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    /**
     * @param null $daoClass
     *
     * @return ShaOperationEdit
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }

    /**
     * @return null
     */
    public function getDaoClass()
    {
        return $this->_daoClass;
    }

    /**
     * @param null $daoKeys
     *
     * @return ShaOperationEdit
     */
    public function setDaoKeys($daoKeys)
    {
        $this->_daoKeys = $daoKeys;
        return $this;
    }

    /**
     * @return null
     */
    public function getDaoKeys()
    {
        return $this->_daoKeys;
    }



    /**
     * Return array with typical 'add form' button configurati
     *
     * @return ShaResponse|void
     * @throws Exception
     */
    public function run() {

        $class = $this->getDaoClass();
        /** @type ShaCmo $instance */
        $instance = new $class();
        if (!$instance->load($this->getDaoKeys())){
            throw new Exception (
                __CLASS__."::".__FUNCTION__." : Disabled load class $class"
            );
        }

        /** @var ShaForm $form */
        $form = $instance->defaultEditRender();

        if ($form == null){
            ShaUtilsLog::fatal("defaultEditRender() return null (class = ".get_class($instance).") !");
            echo ShaContext::t("unknown_error");
        } else {
        	
        	if ($form instanceof ShaForm){
        		$form
        		->setSaveDao(true)
        		->setDaoPrimaryKeys($instance->getPrimaryKeysAsArray())
        		->addField()->setRenderer(ShaFormField::RENDER_TYPE_SUBMIT)->setValue(ShaContext::t("Save"))
        		;
        		
        		$reponse = new ShaResponse();
        		$reponse
        		->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
        		->setRenderDomId(ShaContext::getNextContentId())
        		->setContent($form->renderDao($instance))
        		->getPopin()
        		->setColor("blue")
        		->setTitle(ShaContext::tt("Edition : %0%", $class))
        		;
        		$reponse->render();
        	}
            
        	if ($form instanceof ShaResponse){
        		$form->render();
        	}
        }


        return;
    }



}



?>