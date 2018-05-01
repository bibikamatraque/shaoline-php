<?php

/**
 * Class ShaOperationAddMapping
 * This manage cmo add mapping ShaOperations in back office
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOperationAddMapping extends ShaOperation {

    /** @type string $_daoClass  */
    protected $_daoClass;
    /** @type array $_daoKeys  */
    protected $_daoKeys;
    /** @type ShaRelation $_relation  */
    protected $_relation;

    const CONST_OPERATION_LIB = "OPERATION_ADD_MAPPING";

    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    /**
     * @return mixed
     */
    public function getDaoClass()
    {
        return $this->_daoClass;
    }

    /**
     * @param string $daoClass
     *
     * @return ShaOperationAddMapping
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDaoKeys()
    {
        return $this->_daoKeys;
    }


    /**
     * @param array $daoKeys
     *
     * @return ShaOperationAddMapping
     */
    public function setDaoKeys($daoKeys)
    {
        $this->_daoKeys = $daoKeys;
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
     * @param mixed $relation
     *
     * @return ShaOperationAddMapping
     */
    public function setRelation($relation)
    {
        $this->_relation = $relation;
        return $this;
    }


    /**
     * Add mapping entry in BDD
     *
     * @return ShaResponse
     */
    public function run(){

        $response = new ShaResponse();
        $response->setRenderer(ShaResponse::CONST_RENDERER_NOTHING);

        $relation = $this->_relation;
        $relationType = $relation->getType();
        if ($relation->isTriplet())
        {

            $tableToFill = $relation->getClassB()->getTable();
            $keysValues = array_merge($relation->getClassA()->getPrimaryKeysValues(), $this->_daoKeys);
            $keys = array_keys($keysValues);
            $values = array_values($keysValues);

            $query = "
                INSERT INTO $tableToFill
                (".implode(",", $keys).")
                VALUES
                ('".implode("','", $values)."')
            ";
            ShaContext::bddInsert($query);

            /** @type ShaOperation $actions */
            foreach ($this->getRefreshActions() as $actions){
                $response->addPhpActions($actions->getGcId());
            }
            $response->render();
            return;
        }

    }

}


?>