<?php


/**
 * Class ShaOperationDelete
 * This manage cmo delete ShaOperations in back office
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOperationDelete extends ShaOperation {

    /** @type string $_daoClass  */
    protected $_daoClass = null;
    /** @type array $_daoKeys */
    protected $_daoKeys = null;
    /** @type ShaRelation $_relation */
    protected $_relation = null;

    const CONST_OPERATION_LIB = "OPERATION_DELETE";

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
     * @return ShaOperationDelete
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
     * @param array $daoKeys
     *
     * @return ShaOperationDelete
     */
    public function setDaoKeys($daoKeys)
    {
        $this->_daoKeys = $daoKeys;
        return $this;
    }

    /**
     * @param \ShaRelation $relation
     *
     * @return ShaOperationDelete
     */
    public function setRelation($relation)
    {
        $this->_relation = $relation;
        return $this;
    }

    /**
     * @return \ShaRelation
     */
    public function getRelation()
    {
        return $this->_relation;
    }

    /**
     * @return null
     */
    public function getDaoKeys()
    {
        return $this->_daoKeys;
    }

    /**
     * @return ShaResponse
     */
    public function run(){

        $response = new ShaResponse();
        $response->setRenderer(ShaResponse::CONST_RENDERER_NOTHING);

        if ($this->getRelation() == null || !$this->getRelation()->isTriplet()){
            $class = $this->_daoClass;
            /** @type ShaCmo $instance */
            $instance = new $class();
            if ($instance->load($this->_daoKeys)){
                $instance->delete();
            }

        } else {

            $relation = $this->getRelation();

            $valueAB = $relation->getClassA()->getPrimaryKeysValues();
            $valueBC = $this->_daoKeys;

            $query = "
                DELETE FROM
                ".$relation->getClassB()->getTable()."
                WHERE
                ".ShaUtilsArray::arrayToSqlCondition($valueAB)." AND
                ".ShaUtilsArray::arrayToSqlCondition($valueBC)."
                ;
            ";
            ShaContext::bddDelete($query);
        }

        /** @type ShaOperation $action */
        foreach ($this->getRefreshActions() as $action){
            $response->addPhpActions($action->getGcId());
        }

        $response->render();

    }

}


?>