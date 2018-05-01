<?php

/**
 * Class ShaRelation
 * Describe relation beween 2 or 3 tables
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaRelation
{

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @type string $_type Relation Type */
    private $_type;
    
    /** @type ShaRelationClass $_classA Class A description */
    private $_classA;
    /** @type ShaRelationClass $_classB Class B description  */
    private $_classB;
    /** @type ShaRelationClass $_classC Class C description  */
    private $_classC;

    /** @type ShaRelationLink Link between A and B */
    private $_linkAToB;
    /** @type ShaRelationLink Link between B and C */
    private $_linkBToC;

    /** @type int Read cursor */
    private $_cursor = false;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * @return ShaRelationClass
     */
    public function getClassA()
    {
        return $this->_classA;
    }

    /**
     * @param string $classA
     *
     * @return ShaRelationClass
     */
    public function setClassA($classA)
    {
        $this->_classA = new ShaRelationClass($this);
        $this->_classA->setClass($classA);
        return $this->_classA;
    }

    /**
     * @return ShaRelationClass
     */
    public function getClassB()
    {
        return $this->_classB;
    }

    /**
     * @param string $classB
     *
     * @return ShaRelationClass
     */
    public function setClassB($classB)
    {
        $this->_classB = new ShaRelationClass($this);
        $this->_classB->setClass($classB);
        return $this->_classB;
    }

    /**
     * @return ShaRelationClass
     */
    public function getClassC()
    {
        return $this->_classC;
    }

    /**
     * @param string $classC
     *
     * @return ShaRelationClass
     */
    public function setClassC($classC)
    {
        $this->_classC = new ShaRelationClass($this);
        $this->_classC->setClass($classC);
        return  $this->_classC;
    }

    /**
     * @return ShaRelationLink
     */
    public function getLinkAToB()
    {
        return $this->_linkAToB;
    }

    /**
     * @return ShaRelationLink
     */
    public function setLinkAToB()
    {
        $this->_linkAToB = new ShaRelationLink($this);
        return $this->_linkAToB;
    }

    /**
     * @return ShaRelationLink
     */
    public function getLinkBToC()
    {
        return $this->_linkBToC;
    }

    /**
     * @return ShaRelationLink
     */
    public function setLinkBToC()
    {
        $this->_linkBToC = new ShaRelationLink($this);
        return $this->_linkBToC;
    }

    /**
     * @return int
     */
    public function getCursor()
    {
        return $this->_cursor;
    }

    /**
     * @param int $cursor
     *
     * @return ShaRelation
     */
    public function setCursor($cursor)
    {
        $this->_cursor = $cursor;
        return $this;
    }


    /**
     * @param string $type
     *
     * @return ShaRelation
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



    /*********************/
    /* SPECIFIC FUNCTION */
    /*********************/

    public function isTriplet(){
        return (
            $this->_type == "1:1" ||
            $this->_type == "1:n" ||
            $this->_type == "n:1" ||
            $this->_type == "n:n" ||
            $this->_type == "n::n"
        );
    }

    /**
     * Return true if index 'objectLink' is set
     *
     * @param array $config Configuration
     *
     * @return boolean
     */
     public function isLinkedReference($config) {
            return ( $this->getType() == "1<<1" );
     }



}

?>