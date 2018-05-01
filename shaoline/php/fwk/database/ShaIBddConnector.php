<?php

/**
 * Interface IBddConnector
 * This class define structure of BddConnector class
 *
 * @category   BddConnector
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
interface IBddConnector {

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    public function setHost($host);
    public function setLogin($login);
    public function setPassword($password);
    public function setBddName($bddName);
    public function setPort($port);

    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * connect
     * Connect to SGBD
     */
    public function connect();
    /**
     * Execute 'SELECT' query and return Recordset
     *
     * @param string $query
     *
     * @return IRecorset
     */
    public function select($query);
    /**
     * Test if a 'SELECT' query has at less 1 result
     *
     * @param string $query
     *
     * @return bool
     */
    public function exist($query);
    /**
     * Execute 'SELECT' query and return the single value
     *
     * @param string $query (the result has to contain only 1 row with 1 field)
     *
     * @return string
     */
    public function selectValue($query);
    /**
     * Execute 'INSERT' query and return last inserted id
     *
     * @param string $query
     *
     * @return int
     */
    public function insert($query);

    /**
     * Execute 'UPDATE' query
     *
     * @param string $query
     */
    public function update($query);

    /**
     * Execute 'DELETE' query
     *
     * @param string $query
     */
    public function delete($query);

    /**
     * Execute 'CREATE, ALTER' query
     *
     * @param string|array $query
     */
    public function execute($query);

    /**
     * Close connection
     */
    public function close();

    /**
     * Enable/disable transactional mode
     *
     * @param bool $state
     */
    public function setTransactionalMode($state);

    /**
     * Commit all waiting ShaOperations
     */
    public function commit();

    /**
     * Abord all waiting ShaOperations
     */
    public function abord();



}

