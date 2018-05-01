<?php

/**
 * Class ShaDao
 * ShaDao extended object manage all datas ShaOperations
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
abstract class ShaDao extends ShaOrm
{


    /** @var string $_tableName ShaBddTable name */
    private $_tableName = "";
    /** @var array $_values ShaBddFields values */
    private $_values = array();
    /** @var array $_primaryKeyValues Key/Value of primary keys */
    private $_primaryKeyValues = array();
    /** @var ShaBddTable $_table Key/value of fields */
    private $_table;

    /**
     * Return table name concerned by object
     *
     * @return string
     */
    public static function getTableName()
    {
        return "";
    }

    /**
     * Return table structure
     *
     * @return ShaBddTable
     */
    public static function getTableDescription()
    {
        return null;
    }


    /**
     * Init SQL datas
     */
    protected static function initData()
    {
        return;
    }

    /**
     * Return all fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->_table->getFields();
    }

    /**
     * Return specific field
     *
     * @param string $name
     *
     * @return ShaBddField
     */
    public function getField($name)
    {
        return $this->_table->getField($name);
    }

    /**
     * Return true if field exist
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasField($name)
    {
        return $this->_table->hasField($name);
    }

    /**
     * Return value of specific field
     *
     * @param string $name
     *
     * @return string
     */
    public function getValue($name)
    {
        if (!array_key_exists($name, $this->_values)){
            throw new Exception("Field '$name'' not found for DAO '".get_called_class()."'");
        }
        return $this->_values[$name];
    }

    /**
     * Return primary field names
     *
     * @return array
     */
    public function getPrimaryFields()
    {
        return $this->_table->getPrimaryFields();
    }

    /**
     * Return primary keys assoc array
     *
     * @return array
     */
    public function getPrimaryKeysAsArray()
    {
        return $this->_primaryKeyValues;
    }

    /**
     * Return all primary key names separated by string sequence
     *
     * @param string $separator Separator
     *
     * @return string
     */
    public function getPrimaryKeyAsString($separator = "_")
    {
        return implode($separator, array_keys($this->_primaryKeyValues));
    }

    /**
     * Return sql condition for link primary key
     *
     * @return string
     */
    public function getPrimaryAsSql()
    {
        $data = array();
        foreach ($this->_primaryKeyValues as $key => $value) {
            $data[] = $key . " = '" . ShaUtilsString::cleanForSQL($this->_values[$key]) . "'";
        }
        return implode(" AND ", $data);
    }


    /**
     * Return qty of primary keys
     *
     * @return int
     */
    public function getQtyPrimaryKeys()
    {
        return $this->_table->getQtyPrimaryKeys();
    }

    /**
     * Set field value (Not persistent)
     *
     * @param string $field ShaBddField name
     * @param string $value New value
     *
     * @throws Exception Error description
     * @return ShaDao
     */
    public function setValue($field, $value)
    {

        //Check field
        if (!$this->hasField($field)) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField " . $field . " doesn't existe in table '" . $this->_tableName . "'");
        }

        //Check value
        if (!$this->getField($field)->check($value)) {
        	var_dump($value);
            throw new Exception(
                __CLASS__ . "::" . __FUNCTION__ . " : The field '" . $field . "' of type " . $this->getField($field)->getType() . " cannot have '" . $value . "' for value type in table " . $this->_tableName
            );
        }

        //Set value
        $this->_values[$field] = $value;

        //Set in primary array
        if (array_key_exists($field, $this->_primaryKeyValues)) {
            $this->_primaryKeyValues[$field] = $value;
        }

        return $this;
    }

    /**
     * Load and object using a data array
     *
     * @param array $datas Array of mapping fieldName/value
     *
     * @throws Exception Error description
     * @return void
     */
    public function setDatas($datas)
    {
        try {
            foreach ($datas as $key => $value) {
                if ($this->hasField($key)) {
                    $this->setValue($key, $value);
                }
            }
            $this->_fillPrimaryKeysWithValues();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Set field value (Persistent)
     *
     * @param string $field ShaBddField name
     * @param string $value Value of field
     *
     * @throws Exception Error description
     * @return void
     */
    public function setPersistentValue($field, $value)
    {
        $this->setValue($field, $value);
        $this->save();
    }

    /**
     * Return sql condition for link relation
     *
     * @param string $name ShaReference name
     *
     * @return string
     */
    public function getReferenceSql($name)
    {

        $fields = array();

        $reference = $this->getReference($name);
        $keys = $reference->getToKeys();

        foreach ($keys as $key) {
            $fields[] = " " . $key . " = '" . $this->getValue($key) . "' ";
        }
        return implode(" AND ", $fields);
    }


    /********************/
    /* PUBLIC FUNCTIONS */
    /********************/

    /**
     * Constructor
     *  Check if table referenced
     *  Check if table exist in bdd
     *  Load fields
     *  Init fields
     */
    public function __construct()
    {

        /** @type ShaDao $class */
        $class = get_class($this);
        $this->_tableName = $class::getTableName();

        //Check name
        if ($this->_tableName == "") {
            throw new Exception(
                __CLASS__ . "::" . __FUNCTION__ . " : No table name found for ShaDao : " . $class
            );
        }

        //If table doesn't exist, create it
        if (!self::isTableExist($this->_tableName)) {
            if (!self::isTableExistInBdd($this->_tableName)) {
                $this->_setup();
            }
            self::loadTableByClassName($class);
        }

        //Set default values
        $this->_table = self::getTable($this->_tableName);
        $this->_values = array();
        $fields = $this->getFields();
        /** @var ShaBddField $field */
        foreach ($fields as $field) {
            $this->_values[$field->getName()] = $field->getInitValue();
        }

        //Fill primary key array
        $this->_fillPrimaryKeysWithValues();
    }

    /**
     * Load database row using primary key
     *
     * @param array $primaryKeys Array of mapping primaryKey/value
     *
     * @return boolean False if error (true if not)
     * @throws Exception Error description
     */
    public function load($primaryKeys)
    {

        $result = false;
        $recordset = $this->_loadRecordsetByPrimaryKey($primaryKeys);
        $record = $recordset->fetchAssoc();
        if ($record) {
            $this->setDatas($record);
            $result = true;
        }
        $recordset->close();
        return $result;
    }


    /**
     * Delete information's from database (using serialized primary keys array)
     */
    public function delete()
    {
        /** @var ShaDao $class */
        $class = get_called_class();
        //Delete all relation entries
        $references = $this->getReferences();

        /** @var ShaReference $reference */
        if ($references != null){
            foreach ($references as $reference) {

                if ($reference->getType() == "1>1" ||
                    $reference->getType() == "1:1" ||
                    $reference->getType() == "1>n") {
                    $relationKeysValues = $this->getArrayWithValues($reference->getToKeys());
                    /** @var ShaDao $className */
                    $className = $reference->getTo();
                    $items = $className::loadByWhereClause($relationKeysValues);
                    /** @var ShaDao $item */
                    foreach ($items as $item) {
                        $item->delete();
                    }
                } elseif (
                    $reference->getType() == "1:n" ||
                    $reference->getType() == "n:1" ||
                    $reference->getType() == "n:n" ||
                    $reference->getType() == "n::n"
                ) {
                    $relationKeysValues = $this->getArrayWithValues($reference->getThroughKeys());
                    /** @var ShaDao $className */
                    $className = $reference->getThrough();

                    $items = $className::loadByWhereClause(ShaUtilsArray::arrayToSqlCondition($relationKeysValues));
                    /** @var ShaDao $item */
                    foreach ($items as $item) {
                        $item->delete();
                    }
                }
            }
        }

        //Delete entities
        $this->bddDelete("DELETE FROM " . $class::getTableName() . " WHERE " . $this->getPrimaryAsSql(), false);

    }


    /**
     * Save a ShaDao object in database
     *
     * @param boolean $check Ture if it must check the integrity of datas
     *
     * @throws Exception Error description
     * @return int
     */
    public function save($check = true)
    {

        //UPDATE
        if ($this->_isInBDD()) {
            $update = "UPDATE " . $this->_tableName . " SET ";
            $condition = " WHERE 1=1 ";

            $fields = $this->getFields();
            /** @var ShaBddField $field */
            foreach ($fields as $field) {

                if (!$field->isAutoDate()){

                    $fieldName = $field->getName();
                    $fieldValue = $this->getValue($fieldName);

                    if ($check && !$field->check($fieldValue)) {

                        throw new Exception(
                            __LINE__ . ":" .__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField '" . $fieldName . " of type " . $field->getType(
                            ) . " cannot have '" . $fieldValue . "' for value in table " . $this->_tableName
                        );

                    }
                    if (array_key_exists($fieldName, $this->_primaryKeyValues)) {
                        $condition .= " AND " . $fieldName . "=" . $field->formatType($fieldValue);
                    } else {
                        $update .= $fieldName . "=" . $field->formatType($fieldValue) . ",";
                    }
                }

            }
            $update = substr($update, 0, strlen($update) - 1);
            $this->bddUpdate($update . $condition);
          
        } else {
            //INSERT
            if (!$this->_table->getIsAutoincremental() && !$this->_hasAllPrimaryKeys()) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : Missing primary keys for table " . $this->_tableName . "");
            }
            $insert = "INSERT INTO " . $this->_tableName . " (";
            $values = " VALUES (";

            $fields = $this->getFields();
            /** @var ShaBddField $field */
            foreach ($fields as $field) {

                $fieldName = $field->getName();
                $fieldValue = $this->getValue($fieldName);

                if (!$field->isAutoValue() && $this->hasField($fieldName)) {

                    if ($check && !$field->check($fieldValue)) {
                        echo ($fieldValue === NULL) ? "$fieldName is null<br/>" : "$fieldName is not null<br/>";
                        echo ($fieldValue === '') ? "$fieldName is empty string<br/>" : "$fieldName is not empty string<br/>";
                        echo ($field->getDefault() !== '') ? "$fieldName has default value<br/>" : "$fieldName has not default value<br/>";
                        echo ($field->isAutoValue()) ? "$fieldName has auto value<br/>" : "$fieldName has not auto value<br/>";
                        echo "Init value = " . $field->getInitValue();
                        if (($fieldValue === NULL || $fieldValue === '') && $field->getDefault() !== ''){
                            $fieldValue = $field->getInitValue();
                            if ($fieldValue != null){
                                $this->setValue($fieldName, $fieldValue);
                            }
                        } else {

                            throw new Exception(
                                __LINE__ . ":" . __CLASS__ . "::" . __FUNCTION__ . " : ShaBddField '" . $fieldName . " of type " . $field->getType(
                                ) . " cannot have '" . $fieldValue . "' for value in table " . $this->_tableName
                            );

                        }
                    }

                    if ($fieldValue != null){
                        $insert .= $fieldName . ",";
                        $values .= $field->formatType($fieldValue) . ",";
                    }

                }
            }
            if ($insert != "INSERT INTO " . $this->_tableName . " (") {
                $insert = substr($insert, 0, strlen($insert) - 1);
            }

            $insert .= ")";

            if ($values != " VALUES (") {
                $values = substr($values, 0, strlen($values) - 1);
            }

            $values .= ")";
            
            if ($this->getQtyPrimaryKeys() == 1 && $this->_table->getIsAutoincremental()) {
            	//die($insert . $values);
                $id = $this->bddInsert($insert . $values, true);

                foreach ($this->_primaryKeyValues as $key => $value) {
                    $this->_values[$key] = $id;
                    $this->_primaryKeyValues[$key] = $id;
                }
            } else {
                $this->bddInsert($insert . $values, false);

            }

        }
        return true;
    }


    /**
     * Execute 'SELECT' query and return Recordset
     *
     * @param string $query
     *
     * @return IRecorset
     */
    public static function bddSelect($query)
    {
        return self::getBddConnector()->select($query);
    }


    /**
     * Execute 'SELECT' query and return Recordset
     *
     * @param string $query
     *
     * @return IRecorset
     */
    public static function bddExist($query)
    {
        return self::getBddConnector()->exist($query);
    }

    /**
     * Execute 'SELECT' query and return the single value
     *
     * @param string $query (the result has to contain only 1 row with 1 field)
     *
     * @return string
     */
    public static function bddSelectValue($query)
    {
        return self::getBddConnector()->selectValue($query);
    }

    /**
     * Execute 'INSERT' query and return last insered id
     *
     * @param string $query
     *
     * @return int
     */
    public static function bddInsert($query)
    {
        return self::getBddConnector()->insert($query);
    }

    /**
     * Execute 'UPDATE' query
     *
     * @param string $query
     */
    public static function bddUpdate($query)
    {
        self::getBddConnector()->update($query);
    }

    /**
     * Execute 'DELETE' query
     *
     * @param string $query
     */
    public static function bddDelete($query)
    {
        self::getBddConnector()->delete($query);
    }

    /**
     * Execute 'CREATE, ALTER' query
     *
     * @param string|array $query
     */
    public static function bddExecute($query)
    {
        self::getBddConnector()->execute($query);
    }

    /**
     * Load all information about object linked by a specific relation
     *
     * @param string $name ShaReference name to load
     *
     * @return array
     */
    public function loadByReference($name)
    {
        /** @type ShaReference $reference */
        $reference = $this->getReference($name);
        $type = $reference->getType();

        if ($type == "1>1" || $type == "1>n") {

            /** @type ShaDao $class */
            $class = $reference->getTo();
            $values = $this->getArrayWithValues($reference->getToKeys());
            return $class::loadByWhereClause(ShaUtilsArray::arrayToSqlCondition($values));

        } elseif ($type == "1<1" || $type == "n<1") {

            $class = $reference->getTo();
            /** @var ShaDao $instance */
            $instance = new $class();
            $values = $this->getArrayWithValues($reference->getToKeys());
            if ($instance->load($values)) {
                return $instance;
            }
            return null;

        } elseif ($type == "1:1" || $type == "1:n" || $type == "n:1" || $type == "n:n" || $type == "n::n") {

            $class = $reference->getThrough();
            $values = $this->getArrayWithValues($reference->getThroughKeys());
            $throughClasses = $class::loadByWhereClause(ShaUtilsArray::arrayToSqlCondition($values));

            $result = array();
            $class = $reference->getTo();
            /** @type ShaDao $throughClass */
            foreach ($throughClasses as $throughClass) {
                $values = $throughClass->getArrayWithValues($reference->getToKeys());
                $result = array_merge($result, $class::loadByWhereClause(ShaUtilsArray::arrayToSqlCondition($values)));
            }
            return $result;
        }

        return null;
    }


    /**
     * Return all references
     *
     * @return array
     */
    public function getReferences()
    {
        return $this->_table->getReferences();
    }

    /**
     * Return ShaDaoList list for specific relation
     *
     * @param string $name ShaRelation name
     *
     * @return ShaReference
     */
    public function getReference($name)
    {
        return $this->_table->getReference($name);
    }

    /**
     * Return array of key with value of object
     *
     * @param array $keys array of keys
     *
     * @return array ((key/value)
     * @throws Exception Error description
     */
    public function getArrayWithValues($keys)
    {

        $result = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->_values)) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : The key " . $key . " is unknown for table " . $this->_tableName);
            }
            $result[$key] = $this->_values[$key];
        }
        return $result;
    }


    /**
     * Return true if an rows respect all condition
     *
     * @param string $condition Condition
     *
     * @return boolean
     */
    public function existByWhereClause($condition)
    {
        return $this->bddExist("SELECT 1 FROM " . $this->_tableName . " WHERE " . $condition);
    }

    /**
     * Load all datas in array
     * Use only from small bdd
     * Only for simple primary key object or translated object
     *
     * @return array
     */
    public static function loadAllDatas()
    {
        $result = array();
        $className = get_called_class();
        /** @var ShaDao $className */
        $allDaos = $className::loadByWhereClause("");
        $primaryKeyName = null;
        /** @var ShaDao $dao */
        foreach ($allDaos as $dao) {
        	//TODO, what that
            if (!isset($sPrimaryKeyName)) {
                $primaryKeyName = key($dao->getPrimaryFields());
            }
            $result[$dao->getValue($primaryKeyName)] = $dao;
        }
        return $result;
    }

    /**
     * Get an array of ShaCmo instance depending of a condition
     *
     * @param string $condition
     * @param string $order
     * @param int $start
     * @param int $qty
     * @param ShaRelation $relation
     * @param bool $addMode
     *
     * @return array
     * @throws Exception
     */
    public static function getList(
        $condition = null,
        $order = "",
        $start = null,
        $qty = null,
        $relation = null,
        $additionalSelect = null
    ) {

        /** @type ShaDao $class */
        $class = get_called_class();
        $sqlLimit = (isset($start) && isset($qty)) ? " LIMIT $start, $qty " : "";
        $sqlCondition = (isset($condition) && $condition != "") ? " WHERE " . $condition . " " : " WHERE 1=1 ";
        $sqlCondition = ShaUtilsString::replace($sqlCondition, "WHERE WHERE", "WHERE");
        $sqlOrder = (isset($order) && $order != "") ? " ORDER BY " . $order . " " : "";
        $select = (isset($additionalSelect)) ? "* ".$additionalSelect : "*";

        if (isset($relation)) {

            $relationType = $relation->getType();
            $inOrNotIn = ($relation->getCursor() == 1) ? " NOT IN " : " IN ";

            if (
                $relationType == "1>1" ||
                $relationType == "1>n"
            ) {

                $class = $relation->getClassB()->getClass();
                $from = $relation->getClassB()->getTable();
                $through = $from;
                $commonKeys = $relation->getLinkAToB()->getCommonKeys();
                $concatenated = ShaUtilsArray::arrayToSQLConcat($commonKeys);
                $keyValues = $relation->getClassA()->getPrimaryKeysValues();

            } elseif (
                $relationType == "1<1" ||
                $relationType == "n<1"
            ) {

                $class = $relation->getClassB()->getClass();
                $from = $relation->getClassB()->getTable();
                $through = $from;
                $commonKeys = $relation->getLinkAToB()->getCommonKeys();
                $concatenated = ShaUtilsArray::arrayToSQLConcat($commonKeys);
                $keyValues = $relation->getClassA()->getPrimaryKeysValues();

            } else {
                $class = $relation->getClassC()->getClass();
                //$from = $relation->getClassC()->getTable();
                $through = $relation->getClassB()->getTable();
                $concatenated = ShaUtilsArray::arrayToSQLConcat($relation->getLinkBToC()->getCommonKeys());
                $keyValues = $relation->getClassA()->getPrimaryKeysValues();

            }
            $instance = new $class();
            $sqlCondition .=
                " AND " .
                $concatenated .
                $inOrNotIn .
                " (SELECT " .
                $concatenated .
                " FROM " .
                $through .
                " WHERE " .
                ShaUtilsArray::arrayToSQLCondition($keyValues) .
                ")";

        }

        $query = "SELECT ".$select." FROM " . $class::getTableName() . " " . $sqlCondition . " " . $sqlOrder . " " . $sqlLimit;
       // echo $query;

        $recordset = ShaContext::bddSelect($query);
        if (!isset($recordset)) {
        	ShaUtilsLog::fatal(__CLASS__ . "::" . __FUNCTION__ . " : Bad SQL query : ( $condition, $order, $start, $qty, ...)");
            throw new Exception("Fatal error during listing !");
        }


        $index = 0;
        $items = array();
        while ($record = $recordset->fetchAssoc()) {
            /** @var ShaDao $instance */
            $instance = new $class();
            foreach ($record as $key => $value) {
                if (!is_numeric($key) && $instance->hasField($key)) {
                    $instance->setValue($key, $value);
                }
            }
            $items[$index] = $instance;
            $index++;
        }
        $recordset->close();
        
        return $items;

    }

    /**
     * Count qty of rows concerned by query
     *
     * @param string $condition SQL condition
     * @param ShaRelation $relation Addition relation
     *
     * @return int
     * @throws Exception
     */
    public static function countByWhereClause(
        $condition,
        $relation = null
    ) {
    	
        /** @var ShaDao $instance */
        $class = get_called_class();
        $instance = new $class();
        $sqlCondition = ($condition == "") ? " WHERE 1=1 " : " WHERE " . $condition;


        if (isset($relation)) {
        	
            /** @var ShaDao $objA */
            $class = $relation->getClassA()->getClass();
            
            $objA = new $class();
            if (!$objA->load($relation->getClassA()->getPrimaryKeysValues())) {
                throw new Exception (
                    __CLASS__ . "::" . __FUNCTION__ . " : Unable to load A class !"
                );
            }

            /** @var array $relationKeyValueOfA */
            $relationKeyValueOfA = $objA->getArrayWithValues($relation->getLinkAToB()->getCommonKeys());

            $inOrNotIn = ($relation->getCursor() == 1) ? " NOT IN " : " IN ";
            $egalOrNotegal = ($relation->getCursor() == 1) ? " <> " : " = ";

            if ($relation->getType() == "n:n") {

                $sqlCondition .=
                    " AND " .
                    ShaUtilsArray::arrayToSQLConcat($relation->getLinkBToC()->getCommonKeys()) .
                    $inOrNotIn .
                    " (SELECT " .
                    ShaUtilsArray::arrayToSQLConcat($relation->getLinkBToC()->getCommonKeys()) .
                    " FROM " .
                    $relation->getClassB()->getTable() .
                    " WHERE " .
                    ShaUtilsArray::arrayToSQLCondition($relationKeyValueOfA) .
                    ")";

            } elseif ($relation->getType() == "1:n") {

                if ($inOrNotIn == " IN ") {
                    $toTableKeys = array_merge(
                        $relation->getLinkAToB()->getCommonKeys(),
                        $relation->getLinkBToC()->getCommonKeys()
                    );
                } else {
                    $toTableKeys = $relation->getLinkBToC()->getCommonKeys();
                }

                $sqlCondition .=
                    " AND " .
                    ShaUtilsArray::arrayToSQLConcat($toTableKeys) .
                    $inOrNotIn .
                    " (SELECT " .
                    ShaUtilsArray::arrayToSQLConcat($toTableKeys) .
                    " FROM " . $relation->getClassB()->getTable() .
                    " WHERE " .
                    ShaUtilsArray::arrayToSQLCondition($relationKeyValueOfA) .
                    ")";

            } elseif ($relation->getType() == "1>n") {

                $sqlCondition .=
                    " AND " .
                    ShaUtilsArray::arrayToSQLConcat($relation->getLinkAToB()->getCommonKeys()) .
                    $egalOrNotegal .
                    ShaUtilsArray::arrayToSQLConcat($relationKeyValueOfA);

            } else {

                $sqlCondition .=
                    " AND " .
                    ShaUtilsArray::arrayToSQLConcat($relation->getLinkAToB()->getCommonKeys()) .
                    $inOrNotIn .
                    " (SELECT " .
                    ShaUtilsArray::arrayToSQLConcat($relation->getLinkAToB()->getCommonKeys()) .
                    " FROM " .
                    $relation->getClassB()->getClass().
                    " WHERE " .
                    ShaUtilsArray::arrayToSQLCondition($relationKeyValueOfA) .
                    ")";

            }

            if (substr($sqlCondition, strlen($sqlCondition) - 4, 4) == "AND ") {

                $sqlCondition = substr($sqlCondition, 0, strlen($sqlCondition) - 4);

            }

            $query = "SELECT count(1) as qty FROM " . $instance->getTableName() . $sqlCondition;

        } else {

            $query = "SELECT count(1) as qty FROM " . $instance->getTableName() . $sqlCondition;

        }

        return self::bddSelectValue($query);

    }

    /**
     * Fill field with value
     *
     * @param array $keysValues array of key/value
     *
     * @throws Exception Error description
     * @return void
     */
    public function fillKeysWithValues($keysValues)
    {
        foreach ($keysValues as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    /**
     * Replace all [FILED_NAME] uper case word existing in database by this value
     *
     * @param string $value Value to change
     *
     * @return string
     */
    public function strFieldsToValues($value)
    {
        $fields = $this->getFields();
        /** @var ShaBddField $field */
        foreach ($fields as $field) {
            $value = ShaUtilsString::replace(
                $value,
                "[" . strtoupper($field->getName()) . "]",
                $this->getValue($field->getName())
            );
        }
        return $value;
    }

    /**
     * Check if primary key and values are allowed for this
     *
     * @param array $fields ShaBddField array (name/value)
     * @param bool $checkValues True for checking value
     *
     * @throws Exception
     * @return bool
     */
    public function checkFieldsAndValue($fields, $checkValues = true)
    {
        foreach ($fields as $key => $value) {
            if (!$this->hasField($key)) {
                return false;
            }
            if ($checkValues) {
                if (!$this->getField($key)->check($value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check if primary key only are allowed for this
     *
     * @param array $fields ShaBddField names list
     *
     * @throws Exception
     * @return bool
     */
    public function checkFields($fields)
    {
        foreach ($fields as $key) {
            if (!$this->hasField($key)) {
                return false;
            }
        }
        return true;
    }

    /*******************/
    /* PRIVATE METHODS */
    /*******************/

    /**
     * Init all data before use relation
     *
     * @param string $name ShaReference name
     *
     * @return ShaRelation
     */
    private function generateRelation($name)
    {

        /** @var ShaReference $reference */
        $reference = $this->getReference($name);
        return $reference->generateRelation($this);

    }

    /**
     * Return true if all  primary key are defined
     *
     * @return boolean
     */
    private function _hasAllPrimaryKeys()
    {

        foreach ($this->_primaryKeyValues as $key => $value) {
            if (!$this->hasField($key) || !$this->getField($key)->check($this->getValue($key))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Load database row using primary key
     *
     * @param array $primaryKeys Array of mapping primaryKey/value
     *
     * @return IRecorset
     * @throws Exception Error description
     */
    private function _loadRecordsetByPrimaryKey($primaryKeys)
    {

        $daoPrimaryKeys = $this->getPrimaryFields();
        $daoQtyPrimaryKeys = count($daoPrimaryKeys);

        if (!is_array($primaryKeys)) {

            if ($daoQtyPrimaryKeys > 1) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : Not unique primary key for table '" . $this->_tableName . "'");
            }
            $primaryKeys = array($daoPrimaryKeys[0] => $primaryKeys);

        } else {

            if ($daoQtyPrimaryKeys != count($primaryKeys)) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : Bad number of primary key for table '" . $this->_tableName . "'");
            }

        }

        $condition = " WHERE 1=1 ";
        foreach ($primaryKeys as $key => $value) {

            if (!in_array($key, $daoPrimaryKeys)) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField '" . $key . "' is not a primary key of table " . $this->_tableName);
            }

            if (!$this->_table->getField($key)->check($value)) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField '" . $key . "' cannot have '" . $value . "' for value in table " . $this->_tableName);
            }

            $condition .= " AND " . $key . "=" . $this->getField($key)->formatType($value);

        }

        return $this->bddSelect("SELECT * FROM " . $this->_tableName . $condition." LIMIT 1");

    }


    /**
     * Get all primary key values and stock them in memory
     *
     * @throws Exception Error description
     * @return void
     */
    private function _fillPrimaryKeysWithValues()
    {
        $primaryKeyFields = $this->getPrimaryFields();
        foreach ($primaryKeyFields as $key) {
            $this->_primaryKeyValues[$key] = $this->getValue($key);
        }
    }

    /**
     * Check if an object already in the database
     *
     * @return boolean
     *
     * @throws Exception Error description
     */
    private function _isInBDD()
    {

        if (!$this->_hasAllPrimaryKeys()) {
            return false;
        }

        $condition = " WHERE 1=1 ";
        foreach ($this->_primaryKeyValues as $key => $value) {

            if (!$this->getField($key)->check($value)) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField '" . $key . "' cannot have value : '" . $value . "' in table " . $this->_tableName);
            }
            $condition .= " AND " . $key . "=" . $this->getField($key)->formatType($value);

        }

        return $this->bddExist("SELECT * FROM " . $this->_tableName . $condition);

    }

    /*********************/
    /* PROTECTED METHODS */
    /*********************/

    /**
     * Change primary value in database
     *
     * @param string $field ShaBddField name
     * @param string $value ShaBddField new value
     *
     * @return void
     * @throws Exception
     */
    protected function _changePrimaryKey($field, $value)
    {
        if (!array_key_exists($field, $this->_primaryKeyValues)) {
            $this->setValue($field, $value);
        } else {
            if (!$this->getField($field)->check($value)) {
                throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddField '" . $field . "' can't have value '" . $value . "' in table " . $this->_tableName);
            }
            $this->bddUpdate(
                "UPDATE " . $this->getTableName(
                ) . " SET " . $field . " = '" . $value . "' WHERE " . $this->getPrimaryAsSql()
            );
            $this->setValue($field, $value);
        }
    }


    /******************/
    /* STATIC METHODS */
    /******************/

    /**
     * Setup ShaDao in database
     */
    private function _setup()
    {
        $tmp = $this->getTableDescription()->getCreateQuery();
        $queries = array();
		//$queries[] = 'SHOW WARNINGS LIMIT 0';
		//$queries[] = 'set @orig_mode = @@global.sql_mode';
		//$queries[] = 'set @@global.sql_mode = "MYSQL40"';
		
        $queries = array_merge($queries, $tmp['creates']);
        $queries = array_merge($queries, $tmp['constraints']);
        $queries = array_merge($queries, $tmp['indexes']);
	
		//$queries[] = 'set @@global.sql_mode = @orig_mode';
		
        self::bddExecute($queries);
		
        foreach ( $tmp['daos'] as $dao){
            $dao::initData();
        }

        $this->initData();
    }


    /**
     * Return list of AbstractDao extended object
     *
     * @param string $condition SQL condition
     * @param boolean $bOnlyOne Load only first element (LIMIT 1 in request)
     *
     * @throws Exception
     * @return array
     */
    public static function loadByWhereClause($condition = "", $bOnlyOne = false)
    {

        /** @type ShaDao $class */
        $class = get_called_class();
        try {

            $sOnlyOne = ($bOnlyOne) ? " LIMIT 1 " : "";
            $condition = ($condition == "") ? " WHERE 1=1 " : " WHERE " . $condition;

            $recordset = self::bddSelect("SELECT * FROM " . $class::getTableName() . $condition . $sOnlyOne);
            if (!isset($recordset)) {
                throw new Exception(get_called_class() . "::" . __FUNCTION__ . " : Error during execute query ! ");
            }

            $items = array();
            while ($record = $recordset->fetchAssoc()) {
                /** @var ShaDao $instance = */
                $instance = new $class();
                foreach ($record as $key => $value) {
                    $instance->setValue($key, $value);
                }
                $items[] = $instance;
            }
            $recordset->close();

            if ($sOnlyOne) {
                return (count($items) > 0) ? $items[0] : null;
            } else {
                return $items;
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Return array of id/value used for mapping
     *
     * @param string $fieldForValue Name of field for value
     * @param string $fieldForLabel Name of field for ID
     *
     * @return array
     */
    public static function getValuesMapping($fieldForValue, $fieldForLabel)
    {
        /** @var ShaCmo $class */
        $class = get_called_class();
        $recordset = self::bddSelect("SELECT $fieldForValue, $fieldForLabel FROM " . $class::getTableName());
        $result = array();
        while ($record = $recordset->fetchAssoc()) {
            $result[$record[$fieldForValue]] = $record[$fieldForLabel];
        }
        $recordset->close();
        return $result;
    }


    /**
     * Return array of id/value used for mapping
     *
     * @param string $fieldForValue Name of field for value
     * @param string $fieldForLabel Name of field for ID
     *
     * @return array
     */
    public static function getAllMappingFieldValues($fieldForValue, $fieldForLabel)
    {
        /** @var ShaDao $instance */
        $class = get_called_class();
        $instance = new $class();
        $recordset = $instance->bddSelect(
            "SELECT $fieldForValue, $fieldForLabel FROM " . $instance->getTableName()
        );
        $result = array();
        while ($record = $recordset->fetchAssoc()) {
            $result[$record[$fieldForValue]] = $record[$fieldForLabel];
        }
        $recordset->close();
        return $result;
    }


    /**
     * Return array of primary key/value used for mapping
     *
     * @param string $fieldForLabel Name of field for ID
     *
     * @return array
     */
    public static function getAllMappingPrimaryValues($fieldForLabel)
    {
        $className = get_called_class();
        /** @var ShaDao $className */
        $items = $className::loadByWhereClause("");
        $result = array();
        /** @var ShaDao $item */
        foreach ($items as $item) {
            $result[] = array(implode("#", $item->getPrimaryKeysAsArray()), $item->getValue($fieldForLabel));
        }
        return $result;
    }

    /**
     * @param $primaryKey
     *
     * @return boolean
     */
    public static function exist($primaryKey){
        $class = get_called_class();
        /** @var ShaDao $dao */
        $dao = new $class();
        return $dao->load($primaryKey);
    }

}

?>