<?php

/**
 * MysqlRecordset
 * This class convert mysql_result to Recorset
 *
 * @category   Recordset
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class MysqlRecordset
{

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var mysqli_result $_recordset Mysql SELECT query result */
    private $_recordset = null;

    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * Constructor
     *
     * @param mysqli_result $recorset
     */
    public function __construct($recorset){
        $this->_recordset = $recorset;
    }

    /**
     * Return next row datas in a simple array
     *
     * @return array
     */
    public function fetchArray(){
        if ($this->_recordset === false){
            return false;
        }
        return (isset($this->_recordset)) ? $this->_recordset->fetch_row() : null;
    }

    /**
     * Return next row datas in a associative array
     *
     * @return array
     */
    public function fetchAssoc(){
        if ($this->_recordset === false){
            return false;
        }
        return (isset($this->_recordset)) ? $this->_recordset->fetch_assoc() : null;
    }

    /**
     * Return next row datas as an stdObject
     *
     * @return stdClass
     */
    public function fetchObject(){
        if ($this->_recordset === false){
            return false;
        }
        return (isset($this->_recordset)) ? $this->_recordset->fetch_object() : null;
    }

    /**
     * Return number of field in a row line
     *
     * @retiurn int
     */
    public function getRowsSize(){
        if ($this->_recordset === false){
            return 0;
        }
        return (isset($this->_recordset)) ? mysqli_num_fields ($this->_recordset) : 0;
    }

    /**
     * Return number of total lines in recordset
     *
     * @return int
     */
    public function getRowsQty(){
        if ($this->_recordset === false){
            return 0;
        }
        return (isset($this->_recordset)) ? mysqli_num_rows ($this->_recordset) : 0;
    }

    /**
     * Close recordset
     */
    public function close(){
        if (isset($this->_recordset) && $this->_recordset !== false) {
            $this->_recordset->close();
        }
    }
}