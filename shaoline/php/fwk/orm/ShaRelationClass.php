<?php

/**
 * Class ShaRelationClass
 * Claases used by ShaRelation class
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaRelationClass {

    /**************/
    /* ATTRIBUTES */
    /**************/

    private $_class;
    private $_table;
    private $_primaryKeysFields;
    private $_primaryKeysValues;
    private $_isBinary;
    private $_parent;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @param mixed $class
     *
     * @return ShaRelationClass
     */
    public function setClass($class)
    {
        $this->_class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKeysFields()
    {
        return $this->_primaryKeysFields;
    }

    /**
     * @param mixed $primaryKeysFields
     *
     * @return ShaRelationClass
     */
    public function setPrimaryKeysFields($primaryKeysFields)
    {
        $this->_primaryKeysFields = $primaryKeysFields;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKeysValues()
    {
        return $this->_primaryKeysValues;
    }

    /**
     * @param mixed $primaryKeysValues
     *
     * @return ShaRelationClass
     */
    public function setPrimaryKeysValues($primaryKeysValues)
    {
        $this->_primaryKeysValues = $primaryKeysValues;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @param mixed $table
     *
     * @return ShaRelationClass
     */
    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsBinary()
    {
        return $this->_isBinary;
    }

    /**
     * @param mixed $isBinary
     *
     * @return ShaRelationClass
     */
    public function setIsBinary($isBinary)
    {
        $this->_isBinary = $isBinary;
        return $this;
    }

    /*********************/
    /* SPECIFIC FUNCTION */
    /*********************/

    /**
     * @param ShaRelation $parent
     */
    public function __construct($parent){
        $this->_parent = $parent;
    }

    /**
     * @return ShaRelation
     */
    public function end(){
        return $this->_parent;
    }



}