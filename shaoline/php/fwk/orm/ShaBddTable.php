<?php


/**
 * Class ShaBddTable
 * This class define structure SQL table
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaBddTable
{

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var  array $_table ShaBddTable name */
    private $_name = "";
    /** @var  array $_fields Descriptions of fields */
    private $_fields = null;
    /** @var  array $_primaryFields List of primary field names */
    private $_primaryFields = null;
    /** @var  array $_constraints List of constraints */
    private $_references = null;
    /** @var  array list of indexes */
    private $_indexes = array();
    /** @var  bool $_isAutoincremental Define if table is auto_increment type */
    private $_isAutoincremental = false;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * Return true if table is autoincremental
     *
     * @return bool
     */
    public function getIsAutoincremental()
    {
        return $this->_isAutoincremental;
    }

    /**
     * Define if table is autoincremental
     *
     * @param bool $isAutoincremental
     *
     * @return ShaBddTable
     */
    public function setAutoIncremental($isAutoincremental)
    {
        $this->_isAutoincremental = $isAutoincremental;

        return $this;
    }

    /**
     * Return indexes
     *
     * @return array
     */
    public function getIndexes()
    {
        return $this->_indexes;
    }

    /**
     * Return if a field exist
     *
     * @param string $name ShaBddField name
     */
    public function hasField($name) {
        return isset($this->_fields[$name]);
    }

    /**
     * Return specific index
     *
     * @param string $name
     *
     * @return Object
     * @throws Exception
     */
    public function getIndex($name)
    {
        if (!isset($this->_indexes[$name])) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : Index not found " . $name);
        }

        return $this->_indexes[$name];
    }

    /**
     * Define indexes
     *
     * @param array $indexes
     *
     * @return ShaBddTable
     */
    public function setIndexes($indexes)
    {
        $this->_indexes = $indexes;

        return $this;
    }

    /**
     *  Add new indexes
     *
     * @param $columns (use ',' like separation)
     * @param string $type
     *
     * @return $this
     */
    public function addIndex($columns, $type = "")
    {
        $name = ShaUtilsString::replace($columns, ",", "_");
        $name = ShaUtilsString::replace($name, " ", "");
        $this->_indexes[] = array (
            'name' => $name,
            'type' => $type,
            'column' => $columns
        );
        return $this;
    }

    /**
     * Return fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Return specific field
     *
     * @param string $name
     *
     * @return ShaBddField
     * @throws Exception
     */
    public function getField($name)
    {
        if (!isset($this->_fields[$name])) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField not found " . $name);
        }

        return $this->_fields[$name];
    }

    /**
     * Add new field
     *
     * @param string $name
     *
     * @return ShaBddField
     */
    public function addField($name)
    {

        $field = new ShaBddField($this);
        $field->setName($name);
        $this->_fields[$name] = $field;

        return $field;
    }

    /**
     * Add new field
     *
     * @param array $fields
     *
     * @return ShaBddTable
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    /**
     * Return all reference
     *
     * @return array
     */
    public function getReferences()
    {
        return $this->_references;
    }


    /**
     * Set all reference
     *
     * @param array $references
     *
     * @return ShaBddTable
     */
    public function setReferences($references)
    {
        $this->_references = $references;
        return $this;
    }

    /**
     * Add new reference
     *
     * @param string ShaReference name
     *
     * @return ShaReference
     */
    public function addReference($name)
    {
        $reference = new ShaReference($this);
        $reference->setName($name);
        if (!isset($this->_references)) {
            $this->_references = array();
        }
        $this->_references[$name] = $reference;
        return $reference;
    }

    /**
     * Return specific constraints
     *
     * @param string $name Constraint name
     *
     * @return ShaRelation
     * @throws Exception
     */
    public function getReference($name)
    {
        if (!isset($this->_references[$name])) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : Constraint not found " . $name);
        }

        return $this->_references[$name];
    }

      /**
     * Return primary field name
     *
     * @return array
     */
    public function getPrimaryFields()
    {
        return $this->_primaryFields;
    }

    /**
     * Set primary fields names
     *
     * @param array $primaryFields
     *
     * @return ShaBddTable
     */
    public function setPrimaryFields($primaryFields)
    {
        $this->_primaryFields = $primaryFields;
        return $this;
    }

    /**
     * Set primary fields names
     *
     * @param string $name
     *
     * @return ShaBddTable
     * @throws Exception
     */
    public function addPrimaryFields($name)
    {
        if (!isset($this->_fields[$name])) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : Key not found '" . $name . "'");
        }

        $this->_primaryFields[] = $name;
        return $this;
    }

    /**
     * Return table name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Define table name
     *
     * @param string $name
     *
     * @return ShaBddTable
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * Update internal primary key list an dvalues
     */
    public function updatePrimaryKeyFields() {
        if (!isset($this->_primaryFields)) {
            $this->_primaryFields = array();
            /** @type ShaBddField $field */
            foreach ($this->_fields as $field) {
                if ($field->isPrimary()) {
                    $this->_primaryFields[] = $field->getName();
                }
            }
        }
    }

    /**
     * Return quantity of primary keys
     *
     * @return int
     */
    public function getQtyPrimaryKeys() {
        return count($this->_primaryFields);
    }

    /**
     * Return CREATE queries
     *
     * @return array
     */
    public function getCreateQuery() {
        $tables = array();
        return $this->_getCreateQuery($tables);
    }

    /**
     * Return CREATE queries
     *
     * @return array
     */
    public function _getCreateQuery(&$tables) {

        $tables[] = $this->_name;

        $result = array(
            'creates'       => array(),
            'constraints'   => array(),
            'indexes'       => array(),
            'daos'          => array()
        );

        if (isset($this->_references)) {
            /** @type ShaReference $reference */
            foreach ($this->_references as $reference) {

            	if (in_array($reference->getType() , array('1>1', '1>n'))) {
            		continue;
            	}

                if ($reference->getTo() != null){
                	$dao = $reference->getTo();
                    $tableName = $dao::getTableName();
                	if (!ShaOrm::isTableExistInBdd($tableName) && (!in_array($tableName, $tables)) ) {
                        $tables[] = $tableName;
                        $tmp = $dao::getTableDescription()->_getCreateQuery($tables);
                        $result['creates'] = array_merge($result['creates'], $tmp['creates']);
                        $result['constraints'] = array_merge($result['constraints'], $tmp['constraints']);
                        $result['indexes'] = array_merge($result['indexes'], $tmp['indexes']);
                        $result['daos'] = array_merge($result['daos'], $tmp['daos']);
                        $result['daos'][] = $dao;
                	}
                }
                
                if ($reference->getThrough() != null) {
                    $dao = $reference->getThrough();
                    $tableName = $dao::getTableName();
                    if (!ShaOrm::isTableExistInBdd($tableName) && (!in_array($tableName, $tables))) {
                        $tables[] = $tableName;
                        $tmp = $dao::getTableDescription()->_getCreateQuery($tables);
                        $result['creates'] = array_merge($result['creates'], $tmp['creates']);
                        $result['constraints'] = array_merge($result['constraints'], $tmp['constraints']);
                        $result['indexes'] = array_merge($result['indexes'], $tmp['indexes']);
                        $result['daos'] = array_merge($result['daos'], $tmp['daos']);
                        $result['daos'][] = $dao;
                    }
                }

                $result['constraints'] = array_merge($result['constraints'], $reference->getCreateSqlContraint());
                echo "<br/>";
            }
        }

        $fields = array();
        $primary = array();
        /** @var ShaBddField $field */
        foreach ($this->_fields as $field) {
		
            $notnull = ($field->isNullable()) ? "" : "NOT NULL";
            $autoIncrement = ($field->isAutoIncremental()) ? "AUTO_INCREMENT" : "";
            $default = ($field->getDefault() != null) ? "DEFAULT ".$field->formatType($field->getDefault()) : "";
            $comment = ($field->getComment() != "") ? "COMMENT '".ShaUtilsString::cleanForSQL($field->getComment())."'" : "";
            $collate = ($field->getCollate() != "") ? "COLLATE '".$field->getCollate()."'" : "";

            if (!$field->isAutoValue() && !$field->isNullable() && $default == "") {
                $default = "DEFAULT ".$field->formatType($field->determineDefaultValue());
            }

            $fields[] = $field->getName()." ".$field->getType()." $notnull $autoIncrement $default $comment $collate";

            if ($field->isPrimary()) {
                $primary[] = $field->getName();
            }

        }

        $indexes = array();
        foreach ($this->_indexes as $index ) {
            $indexes[] = "ALTER TABLE ".$this->_name." ADD ".$index['type']." INDEX ".$index['name']." (".$index['column'].");";
        }

        $query = "
          CREATE TABLE ".$this->_name." (
          ".implode(",".PHP_EOL, $fields).",
          PRIMARY KEY (".implode(",", $primary)."))
          ENGINE=InnoDB
          DEFAULT CHARSET=utf8
          ;
        ";

        $result['creates'] = array_merge($result['creates'], array($query));
        $result['indexes'] = array_merge($result['indexes'], $indexes);

        $result['creates']      = array_unique($result['creates']);
        $result['constraints']  = array_unique($result['constraints']);
        $result['indexes']      = array_unique($result['indexes']);
        $result['daos']         = array_unique($result['daos']);


        return $result;
    }

}

?>