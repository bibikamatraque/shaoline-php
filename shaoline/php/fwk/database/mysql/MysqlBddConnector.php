<?php

/**
 * MysqlBddConnector
 * This class define implement methodes required to work with MSQYL database
 *
 * @category   BddConnector
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class MysqlBddConnector implements IBddConnector
{

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var string $_host SGBD IP or UID */
    private $_host = "";
    /** @var  int $_port SGBD connection port (default : default: 3306 ) */
    private $_port = 3306;
    /** @var  string $_login SGBC connection login */
    private $_login = "";
    /** @var  string $_login SGBC connection password */
    private $_password = "";
    /** @var  string $_login SGBC database name to use */
    private $_bddName = "";
    /** @var mysqli $_link Mysql link */
    private $_link = null;

    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * Define the bdd name to use
     *
     * @param string $bddName
     *
     * @return MysqlBddConnector
     */
    public function setBddName($bddName)
    {
        $this->_bddName = $bddName;
        return $this;
    }

    /**
     * Define the SGBD host
     *
     * @param string $host
     *
     * @return MysqlBddConnector
     */
    public function setHost($host)
    {
        $this->_host = $host;
        return $this;
    }

    /**
     * Define the SGBD connection login
     *
     * @param string $login
     *
     * @return MysqlBddConnector
     */
    public function setLogin($login)
    {
        $this->_login = $login;
        return $this;
    }

    /**
     * Define the SGBC connection password
     *
     * @param string $password
     *
     * @return MysqlBddConnector
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }

    /**
     * Define the SGBC connection port
     *
     * @param int $port
     *
     * @return MysqlBddConnector
     */
    public function setPort($port)
    {
        $this->_port = $port;
        return $this;
    }

    /********************/
    /* REQUIRED METHODS */
    /********************/

    /**
     * connect
     * Connect to SGBD
     *
     * @return int
     */
    public function connect(){
        $this->_connect();
    }

    /**
     * Execute 'SELECT' query and return Recordset
     *
     * @param string $query
     *
     * @return IRecorset
     */
    public function select($query){

        $this->_connect();
        $result = mysqli_query($this->_link, $query);
        if (false === $result ){
        	ShaUtilsLog::fatal("Unable to execute SQL query : $query (".mysqli_error($this->_link).")");
        }
        return new MysqlRecordset($result);

    }

    /**
     * Test if a 'SELECT' query has at less 1 result
     *
     * @param string $query
     *
     * @return bool
     */
    public function exist($query){
        $recordset = $this->select($query);
        $record = $recordset->fetchArray();
        $result = isset($record);
        $recordset->close();
        return $result;
    }

    /**
     * Execute 'SELECT' query and return the single value
     *
     * @param string $query (the result has to contain only 1 row with 1 field)
     *
     * @return string
     */
    public function selectValue($query){
        $result = null;
        $recordset = $this->select($query);
        $record = $recordset->fetchArray();
        if (isset($record)) {
            $result = $record[0];
        }
        $recordset->close();
        return $result;
    }

    /**
     * Execute 'INSERT' query and return last insered id
     *
     * @param string $query
     *
     * @return int
     */
    public function insert($query){
        return $this->_execute($query, true);
    }

    /**
     * Execute 'UPDATE' query
     *
     * @param string $query
     */
    public function update($query){
        $this->_execute($query, false);
    }

    /**
     * Execute 'DELETE' query
     *
     * @param string $query
     */
    public function delete($query){
        $this->_execute($query, false);
    }

    /**
     * Execute 'CREATE, ALTER' query
     *
     * @param string|array $query
     */
    public function execute($query){
        if (is_array($query)){
            foreach ($query as $queryLine){
                $this->_execute($queryLine, false);
            }
        } else {
            $this->_execute($query, false);
        }
    }

    /**
     * Close connection
     */
    public function close(){
        if (isset($this->_link)) {
            mysqli_close($this->_link);
            unset($this->_link);
        }
    }

    /**
     * Enable/disable tranactional mode
     *
     * @param bool $state
     */
    public function setTransactionalMode($state){
        $this->_execute("SET autocommit = ".(($state) ? "1": "0"), false);
    }

    /**
     * Commit all waiting ShaOperations
     */
    public function commit(){
        $this->_execute("COMMIT", false);
    }

    /**
     * Abord all waiting ShaOperations
     */
    public function abord(){
        $this->_execute("ROLLBACK", false);
    }


    /*********************/
    /* SPECIFICS METHODS */
    /*********************/

    /**
     * Establish connection with SGBD if not already do
     *
     * @throws Exception
     */
    private function _connect(){

        if (!isset($this->_link)) {

            $this->_link = mysqli_connect($this->_host, $this->_login, $this->_password);

            if (mysqli_connect_errno() || !isset($this->_link)){
                throw new Exception(__CLASS__."::".__FUNCTION__." : Connection error (".mysqli_connect_error().")");
            }

            if (!mysqli_select_db($this->_link, $this->_bddName)) {
                throw new Exception(__CLASS__."::".__FUNCTION__." : Database ".$this->_bddName." not found ");
            }

            $this->_setBddUtf8();

        }
    }

    /**
     * Execture (INSERT, UPDATE, DELETE, ALTER or CREATE query) and result auto-increment id if asked
     *
     * @param string $query
     * @param bool $getLastInseredId
     *
     * @return int
     * @throws Exception
     */
    private function _execute($query, $getLastInsertedId){
        if ($query == ""){
            return 0;
        }

        $this->_connect();
        $result = mysqli_query($this->_link, $query);
        if (false === $result ){
        	ShaUtilsLog::fatal("Unable to execute SQL query : $query (".mysqli_error($this->_link).")");
        }
        $lastId = 0;
        if ($getLastInsertedId) {
            $lastId = mysqli_insert_id($this->_link);
        }
        return $lastId;
    }

    /**
     * Force all SQL transaction using UTF8
     *
     * @return void
     */
    private function _setBddUtf8() {
        mysqli_query($this->_link, "SET character_set_client = utf8;");
        mysqli_query($this->_link, "SET character_set_results = utf8;");
        mysqli_query($this->_link, "SET character_set_connection = utf8;");
    }

}