<?php

/**
 * Class ShaOperationType
 * This class is use to call sattic function of various classes
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOperationAction extends ShaOperation
{
    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var  string $_daoClass ShaDao extended class name */
    protected $_daoClass;
    /** @var  string $_daoMethod Name of class methode to call */
    protected $_daoMethod;
    /** @var array $_parameters Array with all methodes parameter*/
    protected $_parameters = array();
    /** @var  bool $_activeCconfirmation Define if activation actived or not */
    protected $_activeConfirmation = false;
    /** @var string $_confirmationMsg Message displayed in confirmation popin (if actived) */
    protected $_confirmationMsg = "Are you sure ?";
    /** @var string $_confirmationBtnOkLib Lib of OK button in confirmation popin (if actived) */
    protected $_confirmationBtnOkLib = "Ok";
    /** @var string $_confirmationTitle Title of confirmation popin (if actived) */
    protected $_confirmationTitle = "Confirmation";
    /** @var string $_confirmationBtnCancelLib Lib of CANCEL button in confirmation popin (if actived) */
    protected $_confirmationBtnCancelLib = "Cancel";
    /** @var string $_confirmationColor Color of confirmation popin */
    protected $_confirmationColor = "blue";
    /** @var bool $_doNothing true for doing nothing*/
    protected $_doNothing = false;
    

    /********************/
    /* REQUIRED METHODS */
    /********************/

    const CONST_OPERATION_LIB = "OPERATION_ACTION";

    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/


    /**
     * Return name of dao class concerned by ShaOperation
     *
     * @return string
     */
    public function getDaoClass()
    {
        return $this->_daoClass;
    }

    /**
     * Define name of dao class concerned by ShaOperation
     *
     * @param string $daoClass
     *
     * @return ShaOperationAction
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }


    /**
     * Return the dao class method name to call
     *
     * @return string
     */
    public function getDaoMethod()
    {
        return $this->_daoMethod;
    }

    /**
     * Define the dao class method name to call
     *
     * @param string $daoMethod
     *
     * @return ShaOperationAction
     */
    public function setDaoMethod($daoMethod)
    {
        $this->_daoMethod = $daoMethod;
        return $this;
    }

    /**
     * Return the dao class method name to call
     *
     * @return string
     */
    public function getConfirmationColor()
    {
        return $this->_confirmationColor;
    }

    /**
     * Define the dao class method name to call
     *
     * @param string $confirmationColor
     *
     * @return ShaOperationAction
     */
    public function setConfirmationColor($confirmationColor)
    {
        $this->_confirmationColor = $confirmationColor;
        return $this;
    }

    /**
     * Return parameters to use when call dao class methode
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Add parameter for dao class methode
     *
     * @param string $key Parameter name
     * @param string $value Parameter value
     *
     * @return ShaOperationAction
     */
    public function addParameter($key, $value)
    {
        $this->_parameters[$key] = $value;
        return $this;
    }

    /**
     *  Define parameters to use when call dao class methode
     *
     * @param array $parameters
     *
     * @return ShaOperationAction
     */
    public function setParameters($parameters)
    {
        $this->_parameters = $parameters;
        return $this;
    }



    /**
     * Return true if confirmation system is actived
     *
     * @return boolean
     */
    public function isActiveConfirmation()
    {
        return $this->_activeConfirmation;
    }

    /**
     * Active or not the confirmation system
     *
     * @param boolean $activeConfirmation
     *
     * @return ShaOperationAction
     */
    public function setActiveConfirmation($activeConfirmation)
    {
        $this->_activeConfirmation = $activeConfirmation;
        return $this;
    }

    /**
     * Return confirmation message
     *
     * @return string
     */
    public function getConfirmationMsg()
    {
        return $this->_confirmationMsg;
    }

    /**
     * Set confirmation message
     *
     * @param string $confirmationMsg
     *
     * @return ShaOperationAction
     */
    public function setConfirmationMsg($confirmationMsg)
    {
        $this->_confirmationMsg = $confirmationMsg;
        return $this;
    }

    /**
     * Return lib of CANCEL button in confirmation popin
     *
     * @return string
     */
    public function getConfirmationBtnCancelLib()
    {
        return $this->_confirmationBtnCancelLib;
    }

    /**
     * Define lib of CANCEL button in confirmation popin
     *
     * @param string $confirmationBtnCancelLib
     *
     * @return ShaOperationAction
     */
    public function setConfirmationBtnCancelLib($confirmationBtnCancelLib)
    {
        $this->_confirmationBtnCancelLib = $confirmationBtnCancelLib;
        return $this;
    }

    /**
     * Return lib of OK button in confirmation popin
     *
     * @return string
     */
    public function getConfirmationBtnOkLib()
    {
        return $this->_confirmationBtnOkLib;
    }

    /**
     * Define lib of OK button in confirmation popin
     *
     * @param string $confirmationBtnOkLib
     *
     * @return ShaOperationAction
     */
    public function setConfirmationBtnOkLib($confirmationBtnOkLib)
    {
        $this->_confirmationBtnOkLib = $confirmationBtnOkLib;
        return $this;
    }


    /**
     * Return title of confirmation popin
     *
     * @return string
     */
    public function getConfirmationTitle()
    {
        return $this->_confirmationTitle;
    }

    /**
     * Define if the operation launch process or not
     *
     * @param bool $value
     *
     * @return ShaOperationAction
     */
    public function setDoNothing($value)
    {
        $this->_doNothing = $value;
        return $this;
    }
    
    
    /**
     * Return true if the operation does not have to launch process
     *
     * @return bool
     */
    public function getDoNothing()
    {
        return $this->_doNothing;
    }
    
    
    /**
     * Define title of confirmation popin
     *
     * @param string $confirmationTitle
     *
     * @return ShaOperationAction
     */
    public function setConfirmationTitle($confirmationTitle)
    {
        $this->_confirmationTitle = $confirmationTitle;
        return $this;
    }

    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * Do action opoeration and return ShaResponse
     *
     * @return ShaResponse
     * @throws Exception
     */
    public function run(){

        //If confirmation active
        if ($this->_activeConfirmation){
        	
            $ShaOperationAction = new ShaOperationAction();
            $ShaOperationAction
                ->setDaoClass($this->getDaoClass())
                ->setDaoMethod($this->getDaoMethod())
                ->setParameters($this->getParameters())
            	->setShaResponse($this->getShaResponse())
            ;
           
            if ($this->getConfirmationBtnOkLib() == null){
                $this->setConfirmationBtnOkLib(ShaContext::t("Ok"));
            }
            if ($this->getConfirmationTitle() == null){
                $this->setConfirmationTitle(ShaContext::t("Confirmation"));
            }

            $ShaOperationAction->save();

			$onclick = $ShaOperationAction->getDomEvent();
			$onclick = ShaUtilsString::replace($onclick, "onclick='", "onclick='UtilsWindow.closeWindow([SHA_POPIN_ID]);");
			
            $sContent = "
				".$this->_confirmationMsg."
				<br/>
				<br/>
				<div class='cms_button cmsConfirmationBtn' ".$onclick."\">"
                    .$this->getConfirmationBtnOkLib().
                "</div>
			";

            $shaResponse = new ShaResponse();
            $shaResponse
                ->setContent($sContent)
                ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
                ->getPopin()
                    ->setTitle($this->getConfirmationTitle())
                    ->setColor($this->getConfirmationColor())
            ;

            $shaResponse->render();
            return;
        }

        $response = "";
        if ($this->_doNothing == false){
            //Call class method if exist
            if (!method_exists($this->getDaoClass(), $this->getDaoMethod())) {
                throw new Exception(ShaContext::t("You probably lost your session, please reload this page !"));
            }
            $class = $this->getDaoClass();
            $method = $this->getDaoMethod();
            
            $response = $class::$method($this->getParameters());
        }
       
        if ($this->_shaResponse != null) {
        	     	
        	if ($response != "") {
        		$this->_shaResponse->setContent($response);
        	}

        	$this->_shaResponse->render();
        	return;
        	
        } else {
        	return $response;
        }

    }

}

?>