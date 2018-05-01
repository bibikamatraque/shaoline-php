<?php

/**
 * Class Session
 * Session manager
 *
 * @category   Cms
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaSession extends ShaCmo{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_sessions";
	}	
	
	/**
	 * Return SQL crating request
	 *
	 * @return ShaBddTable
	 */
	public static function getTableDescription() {
	
		$table = new ShaBddTable();
		$table
			->setName(self::getTableName())
			->addField("session_id")->setType("VARCHAR(100)")->setPrimary()->end()
			->addField("ip")->setType("VARCHAR(250)")->end()
			->addField("browser")->setType("VARCHAR(256)")->setDefault('')->end()
			->addField("last_activity")->setType("TIMESTAMP")->setDefault('CURRENT_TIMESTAMP')->end()
			->addField("user_id")->setType("int")->setDefault(0)->end()
		;
		return $table;
	
	}
	
    /** @var  array Session key/values array */
    private static $_values = null;

    /**
     * Load session datas
     */
    private static function _loadSession(){
        if (!isset(self::$_values)){
        	self::deleteTooOldSessions();
            self::updateFromSession();    
        }
    }
    
	public static function deleteTooOldSessions(){
        if (ShaParameter::exist('GC_TIMEOUT')){
            $sessions = ShaSession::loadByWhereClause("last_activity < DATE_ADD(NOW(), INTERVAL - ".ShaParameter::get('GC_TIMEOUT')." SECOND)");
            $sessionIds = array();
            foreach ($sessions as $session){
                $sessionIds[] = "'".$session->getValue('session_id')."'";
                $path = session_save_path().$session->getValue("session_id");
                if (is_file($path)) {
                    unlink($path);
                }
            }
            if (count($sessionIds) > 0) {
                ShaContext::bddExecute("DELETE FROM shaoline_sessions WHERE session_id IN (".implode(',', $sessionIds).")");
            }
        }
	}    
    
    /**
     * Reload all session datas
     */
    public static function updateFromSession(){
    	session_start();
    	$sessionId = session_id();
        $shaSession = new ShaSession();
        if (!$shaSession->load($sessionId)){
        	$shaSession->setValue("session_id", $sessionId);
        } else {
        	if ($shaSession->getValue('ip') != ShaContext::getClientIp()) {
        		ShaUtilsLog::security("Cross IP session detected IPA : '".$shaSession->getValue('ip')."', IPB : '".ShaContext::getClientIp()."'");
				die("A security error occured ! Please clear your cookies and refresh the page.");		
        	}
        }
        $shaSession->setValue("ip", ShaContext::getClientIp());
        $shaSession->setValue("browser", ShaContext::getClientBrowser());
        $shaSession->save();
        
        self::$_values = array();
        foreach ($_SESSION as $key => $value) { 
            self::$_values[$key] = $value;
        }
        session_write_close();
    }

    /**
     * Save all local datas into session
     */
    public static function updateFromLocal(){
        session_start();
        $_SESSION = array();
        foreach (self::$_values as $key => $value){
            $_SESSION[$key] = $value;
        }
        session_write_close();
    }

    /**
     * Get session item
     *
     * @param string $key Item name
     *
     * @return mixed
     */
    public static function get($key, $default = null){
        self::_loadSession();
        if (!isset(self::$_values[$key])){
           return $default;
        }
        return self::$_values[$key];
    }


    /**
     * Save value in session
     *
     * @param string $key Item key
     * @param string $value Item Value
     */
    public static function set($key, $value){
        self::_loadSession();
        self::$_values[$key] = $value;
        @session_start();
        $_SESSION[$key] = $value;
        @session_write_close();
    }

    /**
     * Save value in session
     *
     * @param string $key Item key
     * @param string $value Item Value
     */
    public static function add($key, $value){
        self::_loadSession();
        @session_start();
        if (!isset(self::$_values[$key])) {
            self::$_values[$key] = array();
            $_SESSION[$key] = array();
        }
        self::$_values[$key][] = $value;
        $_SESSION[$key][] = $value;
        @session_write_close();
    }

    /**
     * Return itrue if value exist
     *
     * @param string $key Item name
     *
     * @return bool
     */
    public static function has($key){
        self::_loadSession();
        return isset(self::$_values[$key]);
    }

    /**
     * Clear
     */
    public static function clear($key) {
        @session_start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        @session_write_close();
    }


}