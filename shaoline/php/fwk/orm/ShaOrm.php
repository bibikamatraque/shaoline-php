<?php

/**
 * Class ShaOrm (singleton)
 * This represent the ShaDao manager
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOrm {

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var array ShaBddTables */
    private static $_tables;
    /** @type IBddConnector BddConnector */
    private static $_bddConnector;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * Constructor
     */
    private function __construct(){
    }

    /**
     * Define BddConnector
     *
     * @param IBddConnector $bddConnector
     */
    public static function setBddConnector($bddConnector){
        self::$_bddConnector = $bddConnector;
    }

    /**
     * Return specific table
     *
     * @param string $name
     *
     * @return ShaBddTable
     * @throws Exception
     */
    public static function getTable($name)
    {
        if (!isset(self::$_tables[$name])) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddTable not found " . $name);
        }
        return self::$_tables[$name];
    }

    /**
     * Return BddConnector
     *
     * @return IBddConnector
     */
    public static function getBddConnector(){
        return self::$_bddConnector;
    }


    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * @param ShaDao $className
     */
    protected function loadTableByClassName($className)
    {
        $table = $className::getTableDescription();
        $table->updatePrimaryKeyFields();
        self::$_tables[$className::getTableName()] = $table;
    }

    /**
     * Load table in bdd from name
     *
     * @param string $tableName
     *
     * @throws Exception
     */
    protected static function loadTableByName($tableName)
    {

        if (isset(self::$_tables[$tableName])) {
            return;
        }

        //Create table
        $newTable = new ShaBddTable();
        $newTable->setName($tableName);

        //Save each fields
        $recordset = self::$_bddConnector->select("SHOW COLUMNS FROM " . $tableName);
        if (!isset($recordset)) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : ShaBddTable '" . $tableName . "' doesn't exist");
        }
        while ($record = $recordset->fetchAssoc()) {

            /** @type ShaBddField $field */
            $field = $newTable
                ->addField($record['Field'])
                ->init($record['Type'], $record['Key'], $record['Null'], $record['Default'], $record['Extra'])
            ;

            if ($field->isAutoIncremental()) {
                $newTable->setAutoIncremental(true);
            }
            if ($field->isPrimary()){
                $newTable->addPrimaryFields($field->getName());
            }
        }
        $recordset->close();

        //Control table integrity
        if ($newTable->getQtyPrimaryKeys() == 0) {
            throw new Exception(__CLASS__ . "::" . __FUNCTION__ . " : The table '" . $tableName . "' doesn't contain primary key!");
        }
        if ($newTable->getQtyPrimaryKeys() > 1 && $newTable->getIsAutoincremental()) {
            throw new Exception(
                __CLASS__ . "::" . __FUNCTION__ . " : The table '" . $tableName . "' cannot contain more than one primary key if it is an auto-increment table !"
            );
        }

        //Add table
        self::$_tables[$newTable->getName()] = $newTable;

    }

    /**
     * Test if a table existe
     *
     * @param string $table  ShaBddTable name
     * @param string $column Column name
     *
     * @return boolean
     */
    protected static function isColumnExist($table, $column)
    {
        return self::$_bddConnector->exist("SHOW COLUMNS FROM " . $table . " WHERE Field = '" . $column . "'");
    }

    /**
     * Test if a table exist
     *
     * @param string $name ShaBddTable name
     *
     * @return boolean true if table exist (false if not)
     */
    public static function isTableExist($name)
    {
        return isset(self::$_tables[$name]);
    }

    /**
     * Test if a table exist
     *
     * @param string $name ShaBddTable name
     *
     * @return boolean true if table exist (false if not)
     */
    public static function isTableExistInBdd($name)
    {
        return self::$_bddConnector->exist("SHOW TABLES LIKE '" . $name . "'");
    }



    /**
     *  Check if a table existe and create it if not using $sqlCreator
     *
     * @param string $table ShaBddTable name
     * @param string $creatingSQLRequests SQL request for creating table if not exist
     *
     * @return void
     * @throws Exception Error description
     */
   /* private function _checkIfTableExist($table, $creatingSQLRequests)
    {
        if (array_key_exists($table, self::$_tablesStructure)) {
            return;
        }
        if (!self::isTableExist($table)) {
            if (is_array($creatingSQLRequests)) {
                if (count($creatingSQLRequests) > 0) {
                    foreach ($creatingSQLRequests as $creatingSQLRequest) {
                        self::$_bddConnector->executeRequete($creatingSQLRequest);
                    }
                }
            } else {
                if ($creatingSQLRequests != "") {
                    self::$_bddConnector->executeRequete($creatingSQLRequests);
                }
            }
            if (!self::isTableExist($table)) {
                throw new Exception("Error during '" . $table . "' table creation ! ");
            }
        }
    }*/


    /**
     * Analyse table
     *
     * @param string $table              ShaBddTable name
     * @param string $creatingSQLRequest ShaBddTable SQL creation request
     *
     * @throws Exception
     * @return void
     */
    /*protected function setTableName($table, $creatingSQLRequest)
    {
        try {
            self::_checkIfTableExist($table, $creatingSQLRequest);
            self::_analyseTable($table);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }*/


}
