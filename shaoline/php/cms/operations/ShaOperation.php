<?php

/**
 * Abstract class ShaOperation
 * This class is base class for all ShaOperation* classes
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
abstract class ShaOperation extends ShaSerializable
{

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var  string $_ShaOperation ShaOperation lib */
    protected $_shaOperation;
    /** @var bool $_garbadgeOnlyOneHit Set to garbage that action can be hit only one time */
    protected $_gcOnlyOneHit = false;
    /** @var string $_gbKey Define unic name in garbadge  */
    protected $_gcKey = null;
    /** @var  int $_gcId Id in garbadge after calling "save()" function*/
    protected $_gcId = 0;
    /** @var ShaOperation $_refererShaOperation Parent ShaOperation  */
    protected $_refererShaOperation = null;
    /** @var  ShaResponse $_shaResponse ShaOperation ShaResponse */
    protected $_shaResponse = null;
    /** @var array _refreshActions */
    protected $_refreshActions = array();
    /** @var array _postParameters Additional field to add in POST request */
    protected $_postParameters = array();

    /********************/
    /* ABSTRACT METHODS */
    /********************/

    /**
     * Return ShaOperation lib
     *
     * @return string
     */
    abstract public function getShaOperationLib();

    /**
     * run
     * Do ShaOperation and return ShaResponse
     *
     * @return ShaResponse
     */
    abstract public function run();

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * Return ShaOperation ShaResponse
     *
     * @return ShaResponse
     */
    public function getShaResponse()
    {
        return $this->_shaResponse;
    }

    /**
     * Set ShaResponse to this
     *
     * @param ShaResponse $ShaResponse
     *
     * @return ShaOperationList
     */
    public function setShaResponse($ShaResponse)
    {
        $this->_shaResponse = $ShaResponse;
        return $this;
    }

    /**
     * Return true if ShaOperation is available for only one hit
     *
     * @return bool
     */
    public function getGcOnlyOneHit()
    {
        return $this->_gcOnlyOneHit;
    }

    /**
     * Define if ShaOperation is available for only one hit
     *
     * @param bool $gcOnlyOneHit
     *
     * @return ShaOperation
     */
    public function setGcOnlyOneHit($gcOnlyOneHit)
    {
        $this->_gcOnlyOneHit = $gcOnlyOneHit;
        return $this;
    }


    /**
     * Return unic name in garbage if defined
     *
     * @return mixed
     */
    public function getGcKey()
    {
        return $this->_gcKey;
    }

    /**
     * Define an unic name in garbage
     *
     * @param string $gcKey
     *
     * @return ShaOperation
     */
    public function setGcKey($gcKey)
    {
        $this->_gcKey = $gcKey;
    }

    /**
     * Return id in garbage (setted after save() function)
     *
     * @return int
     */
    public function getGcId()
    {
        return $this->_gcId;
    }

    /**
     * Set garbage id
     *
     * @param int $gcId
     *
     * @return ShaOperation
     */
    public function setGcId($gcId)
    {
        $this->_gcId = $gcId;
        return $this;
    }

    /**
     * Return referer (parent ShaOperation)
     *
     * @return ShaOperation
     */
    public function getRefererShaOperation()
    {
        return $this->_refererShaOperation;
    }

    /**
     * Set referer (parent ShaOperation)
     *
     * @param ShaOperation $refererShaOperation
     *
     * @return ShaOperation
     */
    public function setRefererShaOperation($refererShaOperation)
    {
        $this->_refererShaOperation = $refererShaOperation;
        return $this;
    }


    /**
     * Return ShaOperation type name
     *
     * @return string
     */
    public function getShaOperation()
    {
        return $this->_shaOperation;
    }

    /**
     * Set ShaOperation type name
     *
     * @param string $ShaOperation
     *
     * @return ShaOperation
     */
    public function setShaOperation($ShaOperation)
    {
        $this->_ShaOperation = $ShaOperation;
        return $this;
    }

    /**
     * @param ShaOperation $parent
     *
     * @return ShaOperation
     */
    public function addRefreshAction($refreshAction)
    {
        $this->_refreshActions[] = $refreshAction;
        return $this;
    }

    /**
     * @param array $parent
     *
     * @return ShaOperation
     */
    public function setRefreshActions($refreshActions)
    {
        $this->_refreshActions = $refreshActions;
        return $this;
    }

    /**
     * @return \ShaOperation
     */
    public function getRefreshActions()
    {
        return $this->_refreshActions;
    }

    /**
     * Return parameters to use when call dao class methode
     *
     * @return array
     */
    public function getPostParam()
    {
        return $this->_postParameters;
    }

    /**
     *
     * @param array $parameters
     * @return ShaOperationAction
     */
    public function setPostParam($parameters){
        $this->_postParameters = $parameters;
        return $this;
    }

    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    public function __construct(){
        $this->setShaOperation($this->getShaOperationLib());
    }

    /**
     * getDomEvent
     * Return HTML part need to implement JS doAction event
     *
     * @return string
     */
    public function getGcAtt() {
        return " gcid='".$this->_gcId."' ";
    }


    /**
     * Return encode POST parameters
     * @return string
     */
    public function getEncodedPostParameters(){
        return base64_encode(json_encode($this->_postParameters));
    }

    /**
     * getDomEvent
     * Return HTML part need to implement JS doAction event
     *
     * @param string $sJsEvent JS event name (Default : 'onclick')
     *
     * @return string
     */
    public function getDomEvent($sJsEvent = 'onclick') {

        if (count($this->_postParameters) > 0){
            return " gcid='".$this->_gcId."' $sJsEvent='Shaoline.doExtendedAction(".$this->_gcId.", '".$this->getEncodedPostParameters()."')' ";
        } else {
            return " gcid='".$this->_gcId."' $sJsEvent='Shaoline.doAction(".$this->_gcId.")' ";
        }

    }
    /**
     * save
     * Save ShaOperation in garbadge and set garbage id in $this->_gcId
     *
     * @return int
     */
    public function save(){
    	$this->_gcId = ShaGarbageCollector::addItem(ShaOperation::stringify($this), $this->_gcOnlyOneHit, $this->_gcKey);
        return $this->_gcId;
    }


    /**
     * load
     * Load ShaOperation in garbage using garbage id
     *
     * @param int $id
     *
     * @return ShaOperation
     * @throws Exception
     */
    public static function load($gcId){
        $code = ShaGarbageCollector::getItem($gcId);
        if ($code == null) {
            throw new Exception(
                ShaContext::t("You probably lost your session, please reload this page !")
            );
        }
        if ($code == "") {
            return "";
        }

        $object = ShaOperation::unstringify($code);
        if (is_object($object) && method_exists($object, "setGcId")){
            $object->setGcId($gcId);
        }
        return $object;
    }

}
