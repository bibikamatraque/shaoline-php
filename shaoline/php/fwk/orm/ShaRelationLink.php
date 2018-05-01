<?php

/**
 * Class ShaRelationLink
 * Claases used by ShaRelation class
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaRelationLink {

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var  string relation type */
    //private $_type;
    /** @var array $_commonKeys Common key names between two tables */
    private $_commonKeys;

    /** @var ShaRelation $_parentParent relation */
    private $_parent;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * @return array
     */
    public function getCommonKeys()
    {
        return $this->_commonKeys;
    }

    /**
     * @param array $commonKeys
     *
     * @return ShaRelationLink
     */
    public function setCommonKeys($commonKeys)
    {
        $this->_commonKeys = $commonKeys;
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