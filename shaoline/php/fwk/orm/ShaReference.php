<?php

/**
 * Class ShaReference
 * Describe link between CMO A and B
 *
 * ShaReference types :
 * - 1>1 (One A to one B, A key into B table)   (no through)
 * - 1<1 (One A to one B, B key into A table)   (no through)
 * - 1:1 (One A to one B, A/B key couple into C table)   (need through)
 * - 1>n (One A for some B, A key into B tables)    (no through)
 * - n<1 (One B for some A, B key into A tables)    (no through)
 * - 1:n (One A for some B, A/B key couples into C table)    (need through)
 * - n:1 (One B for some A, A/B key couples into C table)   (need through)
 * - n:n (Some A for some B, A/B key couple into C table)   (need through) (no duplicate couples)
 * - n::n (Some A for some B, A/B key couples into C table) (need through) (allow duplicate couples))
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaReference
{
    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @type string $_name ShaReference name */
    private $_name = null;
    /** @type string inter ShaDao class name */
    private $_through = null;
    /** @type array inter Common keys between from and inter */
    private $_throughKeys = null;
    /** @type string $_to Target ShaDao class name */
    private $_to = null;
    /** @type array $_toKeys Common keys between inter and to or from and to */
    private $_toKeys = null;
    /** @type ShaBddTable $_parent Parent ShaBddTable */
    private $_parent = null;

    /**
     * @type string $_type ShaReference type
     *
     * - 1>1 (One A to one B, A key into B table)   (no through)
     * - 1<1 (One A to one B, B key into A table)   (no through)
     * - 1:1 (One A to one B, A/B key couple into C table)   (need through)
     * - 1>n (One A for some B, A key into B tables)    (no through)
     * - n<1 (One B for some A, B key into A tables)    (no through)
     * - 1:n (One A for some B, A/B key couples into C table)    (need through)
     * - n:1 (One B for some A, A/B key couples into C table)   (need through)
     * - n:n (Some A for some B, A/B key couple into C table)   (need through) (no duplicate couples)
     * - n::n (Some A for some B, A/B key couples into C table) (need through) (allow duplicate couples))
     */
    private $_type = null;
    /** @type int $_nextUsingTarget Internal int need to determine the action of next 'using' methode */
    private $_nextUsingTarget;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/


    /**
     * @param mixed $name
     *
     * @return ShaReference
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $through
     *
     * @return ShaReference
     */
    public function setThrough($through)
    {
        $this->_nextUsingTarget = 1;
        $this->_through = $through;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThrough()
    {
        return $this->_through;
    }

    /**
     * @param mixed $throughKeys
     *
     * @return ShaReference
     */
    private function setThroughKeys($throughKeys)
    {
        $this->_throughKeys = $throughKeys;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThroughKeys()
    {
        return $this->_throughKeys;
    }

    /**
     * @param mixed $to
     *
     * @return ShaReference
     */
    public function setTo($to)
    {
        $this->_nextUsingTarget = 0;
        $this->_to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * @param mixed $toKeys
     *
     * @return ShaReference
     */
    private function setToKeys($toKeys)
    {
        $this->_toKeys = $toKeys;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToKeys()
    {
        return $this->_toKeys;
    }

    /**
     * @param mixed $type
     *
     * @return ShaReference
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Specific constructor
     *
     * @param ShaBddTable $parent Parent ShaBddTable
     */
    public function __construct($parent){
        $this->_parent = $parent;
    }

    /**
     * End setting mode
     *
     * @return ShaBddTable
     */
    public function end(){
        return $this->_parent;
    }

    /**
     * Affect primary keys to relation
     *
     * @param array $keys Common keys
     *
     * @return ShaReference
     */
    public function using($keys){
        if (!is_array($keys)){
            $keys = array($keys);
        }

        //TO
        if ($this->_nextUsingTarget == 0){
            $this->setToKeys($keys);
        }
        //THROUGH
        if ($this->_nextUsingTarget == 1){
            $this->setThroughKeys($keys);
        }

        return $this;
    }

    /**
     * Return true if type need through datas
     *
     * @return bool
     */
    public function needThrough(){
        return (
            ($this->_type == "1:1") ||
            ($this->_type == "1:n") ||
            ($this->_type == "n:1") ||
            ($this->_type == "n:n") ||
            ($this->_type == "n::n")
        );
    }


    /**
     * Check reference integrity
     *
     * @return bool
     */
    public function checkIntegrity(){
        if ($this->needThrough() && !isset($this->_through)) {
            return false;
        }
        if (!$this->needThrough() && isset($this->_through)) {
            return false;
        }

        return true;
    }

    /**
     * Init all datas before use relation
     *
     * @param ShaDao $instance Insatnce of ShaDao
     *
     * @return ShaRelation
     */
    public function generateRelation($instance)
    {

        $className = ($this->getThrough() == null) ?  $this->getTo() : $this->getThrough();
        /** @type ShaCmo $objB */
        $objB = new $className();

        $relation = new ShaRelation();
        $relation
            ->setCursor(0)
            ->setType($this->getType())

            ->setClassA(get_class($instance))
                ->setTable($instance::getTableName())
                ->setPrimaryKeysFields($instance->getPrimaryFields())
                ->setPrimaryKeysValues($instance->getPrimaryKeysAsArray())
                ->end()

            ->setClassB(get_class($objB))
                ->setTable($objB::getTableName())
                ->setPrimaryKeysFields($objB->getPrimaryFields())
                ->end()
        ;


        if ($this->getThrough() == null){

            $relation
                ->setLinkAToB()
                    ->setCommonKeys($this->getToKeys())
                    ->end();

        } else {

            $relation
                ->setCursor(0)
                ->setLinkAToB()
                    ->setCommonKeys($this->getThroughKeys())
                    ->end();

            /** @var ShaDao $objC */
            $className = $this->getTo();
            $objC = new $className();

            $relation
                ->setClassC(get_class($objC))
                    ->setTable($objC->getTableName())
                    ->setPrimaryKeysFields($objC->getPrimaryFields())
                    ->end()

                ->setLinkBToC()
                    ->setCommonKeys($this->getToKeys())
                    ->end();
        }
        return $relation;
    }

    /**
     * Generate SQl contraint query
     *
     * @return string
     */
    private function _getCreateSqlContraint($fromName, $fromKey, $toName, $toKey){
        $fromKey = implode(",", $fromKey);
        $toKey = implode(",", $toKey);

        $name = substr(md5($fromName."_".$fromKey."_".$toName."_".$toKey), 20);
        $name = ShaUtilsString::replace($name, ",", "_");
        $name = ShaUtilsString::replace($name, " ", "");

        return "
            ALTER TABLE
            `" . $fromName . "`
            ADD CONSTRAINT
            `" . $name . "`
            FOREIGN KEY
            (" . $fromKey . ")
            REFERENCES
            `" . $toName . "`
            (" . $toKey . ")
        ";
    }


    /**
     * Generate SQl contraint queries
     *
     * @return array
     */
    public function getCreateSqlContraint(){

        /** @type ShaDao $fromName */
        $fromName = null;
        /** @type ShaDao $toName */
        $toName = null;

        $result = array();
        if ($this->getType() == "1>1" || $this->getType() == "1>n"){
            //B => A
            $fromName = $this->_toKeys;
            $fromName = $fromName::getTableName();
            $fromKey = $this->_toKeys;
            $toName = $this->_parent->getName();
            $toKey = $this->_toKeys;
            $result[] = $this->_getCreateSqlContraint($fromName, $fromKey, $toName, $toKey);

        } elseif ($this->getType() == "1<1" || $this->getType() == "n<1") {
            //A => B
            $fromName = $this->_parent->getName();
            $fromKey = $this->_toKeys;
            $toName = $this->_to;
            $toName = $toName::getTableName();
            $toKey = $this->_toKeys;
            $result[] = $this->_getCreateSqlContraint($fromName, $fromKey, $toName, $toKey);

        } elseif (
            $this->getType() == "1:1" ||
            $this->getType() == "1:n" ||
            $this->getType() == "n:1" ||
            $this->getType() == "n:n" ||
            $this->getType() == "n::n"
        ) {

            //B => A
            $fromName = $this->_through;
            $fromName = $fromName::getTableName();
            $fromKey = $this->_throughKeys;
            $toName = $this->_parent->getName();
            $toKey = $this->_throughKeys;
            $result[] = $this->_getCreateSqlContraint($fromName, $fromKey, $toName, $toKey);

            //B => C
            $fromName = $this->_through;
            $fromName = $fromName::getTableName();
            $fromKey = $this->_toKeys;
            $toName = $this->_to;
            $toName = $toName::getTableName();
            $toKey = $this->_toKeys;
            $result[] = $this->_getCreateSqlContraint($fromName, $fromKey, $toName, $toKey);
        }

        return $result;

    }



}