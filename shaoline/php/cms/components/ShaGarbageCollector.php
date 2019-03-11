<?php
/**
 * Class ShaGarbageCollector
 * Manage action storage
 *
 * @category   Component
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaGarbageCollector extends ShaCmo
{

	private static $_unicId = 0;
	private static $_internalId = -1;
    private static $_timeout = 120;

    /**
     * Define GC timeout
     *
     * @param int $timeout Timeout in seconds
     */
    public static function setTimeout($timeout){
        self::$_timeout = $timeout;
    }


    /**
     * Return table name concerned by object
     *
     * @return string
     */
    public static function getTableName()
    {
        return "shaoline_garbage";
    }

    /**
     * Return SQL crating request
     *
     * @return string
     */
    public static function getTableDescription()
    {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("garbage_session")->setType("VARCHAR(250)")->setPrimary()->end()
            ->addField("garbage_id")->setType("BIGINT UNSIGNED")->setPrimary()->setIndex()->end()
            ->addField("garbage_key")->setType("VARCHAR(50)")->setIndex()->end()
            ->addField("garbage_code")->setType("TEXT")->end()
            ->addField("garbage_last_activity")->setType("TIMESTAMP")->setDefault("CURRENT_TIMESTAMP")->setIndex()->end()
            ->addField("garbage_unic")->setType("TINYINT UNSIGNED")->end()
            ->addField("garbage_used")->setType("TINYINT UNSIGNED")->end();

        return $table;

    }

    /**
     * Return next id for session
     *
     * @return int
     */
    public static function getNextId()
    {
    	
    	if (self::$_internalId == -1) {
    		
    		$key = hexdec(uniqid());
    		self::$_unicId = substr($key, strlen($key) - 11, 11);
    		self::$_internalId = 0;
    		/*self::$_internalId = ShaContext::bddSelectValue(
    				"SELECT IFNULL(MAX(garbage_id), 0) as id FROM shaoline_garbage WHERE garbage_session='" . session_id() . "'"
    		);*/
    	}
    	self::$_internalId++;
    	return (int)self::$_unicId.str_pad(self::$_internalId, 5, "0", STR_PAD_LEFT);
    }

    /**
     * Update existing item in garbade collector
     *
     * @param string $code Gc code
     * @param string $gcId Gc key
     * 
     * @return void
     */
    public static function updateItem($code, $gcId)
    { 
    	ShaContext::bddUpdate(
    		"UPDATE shaoline_garbage SET garbage_code = '".ShaUtilsString::cleanForSQL($code)."', garbage_last_activity = NOW() WHERE garbage_id = '".$gcId."' AND garbage_session = '".session_id()."' LIMIT 1"
    	);
    }
    
    /**
     * Add new item in garbade collector
     *
     * @param string $code Gc code
     * @param string $key Gc key
     * @param bool $isUnic Allow only one hit
     *
     * @return int
     */
    public static function addItem($code, $key = '', $isUnic = false)
    {
    	
        $isUnic = ($isUnic) ? 1 : 0;
        $gcId = self::getNextId();
        if ($key != '') {
            self::deleteEntryByKey($key);
        }
        
        ShaContext::bddInsert(
            "INSERT INTO shaoline_garbage
            (garbage_code, garbage_session, garbage_id, garbage_unic, garbage_key)
            VALUES ('"
            . ShaUtilsString::cleanForSQL($code) . "',
        	'" . session_id() . "',
        	'" . $gcId . "',
        	" . $isUnic . ",
        	'". ShaUtilsString::cleanForSQL($key) . "')"
        );

        return $gcId;
    }

    /**
     * Add new item in garbade collector
     *
     * @param int $code Code
     * @param string $key Gc key
     * @param bool $isUnic Is unic action
     *
     * @return int
     */
    public static function addItemUseKey($code, $key = '', $isUnic = false)
    {
        $isUnic = ($isUnic) ? 1 : 0;
        $gcId = self::getNextId();
        if ($key != '') {
            self::deleteEntryByKey($key);
        }
        ShaContext::bddInsert(
            "INSERT INTO shaoline_garbage (garbage_code, garbage_session, garbage_id, garbage_unic, garbage_key) VALUES ('"
            . ShaUtilsString::cleanForSQL($code) . "', '" . session_id() . "', '" . $gcId . "', " . $isUnic . ", '" . $key
            . "')"
        );

        return $gcId;
    }

    /**
     * Return item
     *
     * @param int $gcId Gc ID
     *
     * @return ShaGarbageCollector
     */
    public static function getItem($gcId)
    {
        $result = null;
        $oRecordset = ShaContext::bddSelect(
            "SELECT garbage_code, garbage_unic, garbage_used FROM shaoline_garbage WHERE garbage_id = $gcId AND garbage_session='" . session_id() . "' LIMIT 1"
        );
        $oRecord = $oRecordset->fetchAssoc();
        if ($oRecord) {
            if ($oRecord['garbage_unic'] == 1) {
                if ($oRecord['garbage_used'] == 1) {
                    return "";
                } else {
                    ShaContext::bddInsert(
                        "UPDATE shaoline_garbage SET garbage_used = 1 WHERE garbage_id = $gcId AND garbage_session='"  . session_id() . "' LIMIT 1"
                    );
                }
            }
            //self::setItemActivity($gcId);
            $result = $oRecord['garbage_code'];
        }
        $oRecordset->close();
        return $result;
    }

    /**
     * Return item
     *
     * @param string $gcKey Gc ID
     *
     * @return ShaGarbageCollector
     */
    public static function getItemByKey($gcKey)
    {
        $sResult    = null;
        $oRecordset = ShaContext::bddSelect(
            "SELECT garbage_code, garbage_unic, garbage_used FROM shaoline_garbage WHERE garbage_key = '$gcKey' AND garbage_session='"
            . session_id() . "' LIMIT 1"
        );
        $oRecord = $oRecordset->fetchAssoc();
        if ($oRecord) {
            if ($oRecord['garbage_unic'] == 1) {
                if ($oRecord['garbage_used'] == 1) {
                    return "";
                } else {
                    ShaContext::bddInsert(
                        "UPDATE shaoline_garbage SET garbage_used = 1 WHERE garbage_key = '$gcKey' AND garbage_session='"
                        . session_id() . "' LIMIT 1"
                    );
                }
            }
            //self::setItemActivity($gcId);
            $sResult = $oRecord['garbage_code'];
        }
        $oRecordset->close();

        return $sResult;
    }

    /**
     * Return item
     *
     * @param int $gcKey Gc ID
     *
     * @return ShaGarbageCollector
     */
    public static function getItemIdByKey($gcKey)
    {
        return ShaContext::bddSelectValue("SELECT garbage_id WHERE garbage_key = '$gcKey' AND garbage_session='" . session_id() . "' LIMIT 1");
    }


    /**
     * Delete all tiems of session
     *
     * @return void
     */
    public static function deleteSessionItem()
    {
        ShaContext::bddDelete("DELETE FROM shaoline_garbage WHERE garbage_session = '" . session_id() . "'");
    }

    /**
     * Delete all tiems of session
     *
     * @param int $gcId Id of GC entry
     *
     * @return void
     */
    public static function deleteEntry($gcId)
    {
        ShaContext::bddInsert(
            "DELETE FROM shaoline_garbage WHERE garbage_id = $gcId AND garbage_session='" . session_id() . "' LIMIT 1"
        );
    }

    /**
     * Delete all tiems of session
     *
     * @param int $gcKey Id of GC entry
     *
     * @return void
     */
    public static function deleteEntryByKey($gcKey)
    {
        ShaContext::bddDelete(
            "DELETE FROM shaoline_garbage WHERE garbage_key = '$gcKey' AND garbage_session='" . session_id() . "' LIMIT 1"
        );
    }


    /**
     * Delete all unused item
     *
     * @return void
     */
    public static function deleteUnusedItems()
    {
        ShaContext::bddDelete(
            "DELETE FROM shaoline_garbage WHERE garbage_last_activity < (curdate() - INTERVAL " . self::$_timeout . " SECOND)", false
        );
    }


    /**
     * Update lasty used date for a variable
     *
     * @param int $gcId Gc ID
     *
     * @return void
     */
    public static function setItemActivity($gcId)
    {
        ShaContext::bddUpdate(
            "UPDATE shaoline_garbage SET garbage_last_activity = '" . date('Y-m-d H:i:s') . "' WHERE garbage_id=$gcId AND garbage_session = '"
            . session_id() . "'", false
        );
    }

    /**
     * Return JS code for GC checking
     *
     * @return string
     */
    public static function getGcJsCode()
    {
        $interval = (round((self::$_timeout / 2), 0) * 1000);
        return '
        	setInterval(function(){Shaoline.checkGc();},'.$interval.');
        	setTimeout(function(){alert("'.ShaContext::t("warning you lost your session ! please reload page").'");},'.($interval - 2000).');			
        ';
    }

    /**
     * Set a list of item active
     *
     * @param string $managedOjectIdList list of managed object IDs (separator ';')
     *
     * @return void
     */
    public static function refreshList($managedOjectIdList)
    {

        self::deleteUnusedItems();
        if (!isset($managedOjectIdList)) {
            die("null pointer expetion");
        }

        $managedOjectIdList = explode(";", $managedOjectIdList);
        $ids = array();
        foreach ($managedOjectIdList as $managedOjectId) {
            if (ShaUtilsString::isRegexPositiveInteger($managedOjectId)) {
                $ids[] = (int)$managedOjectId;
            }
        }

		//This is deprecated for multipage compatibility
        /*ShaContext::bddDelete("
            DELETE FROM shaoline_garbage WHERE
            garbage_session = '" . session_id() . "'
            AND
            garbage_id NOT IN (".implode(",", $ids).")
            "
        );*/
        
        self::deleteUnusedItems();

        ShaContext::bddUpdate(
            "UPDATE shaoline_garbage
            SET garbage_last_activity = NOW()
            WHERE garbage_session = '". session_id() . "'
            AND
            garbage_id IN (".implode(",", $ids).")
            "
        );

    }

}