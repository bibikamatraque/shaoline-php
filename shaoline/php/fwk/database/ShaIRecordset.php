<?php

/**
 * Interface IBddRecorset
 * This class define structure of Recorset class
 *
 * @category   BddConnector
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
interface IRecorset {


    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * Return next row datas in a simple array
     *
     * @return array
     */
    public function fetchArray();

    /**
     * Return next row datas in a associative array
     *
     * @return bool|array
     */
    public function fetchAssoc();

    /**
     * Return next row datas as an stdObject
     *
     * @return stdObject
     */
    public function fetchObject();

    /**
     * Return number of field in a row line
     *
     * @retiurn int
     */
    public function getRowsSize();

    /**
     * Return number of total lines in recordset
     *
     * @return int
     */
    public function getRowsQty();

    /**
     * Close recordset
     */
    public function close();


}

