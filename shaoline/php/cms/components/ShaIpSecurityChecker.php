<?php


/**
 * Description of ShaUser
 *
 * PHP version 5.3
 *
 * @category   Cms
 * @package    Components
 * @subpackage Default
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaIpSecurityChecker extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName() {
		return "shaoline_ip_security_checker";
	}

    /**
     * Return SQL crating request
     *
     * @return string
     */
    public static function getTableDescription() {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("ip")->setType("VARCHAR(15)")->setPrimary()->end()
            ->addField("last_attempt")->setType("VARCHAR(19)")->setDefault('0000-00-00 00:00:00')->end()
            ->addField("qty_attempts")->setType("int")->setDefault(0)->end()
            ->addField("user_id")->setType("bigint")->setDefault(0)->end();
        ;

        return $table;

    }

    public static function get($ip){
    	 
    	self::clearOldEntries();
    	
    	$shaIpSecurityChecker = new ShaIpSecurityChecker();
    	if (!$shaIpSecurityChecker->load(ShaUtilsString::cleanForSQL($ip))) {
    
    		$shaIpSecurityChecker->setValue("ip", $ip);
    		$shaIpSecurityChecker->setValue("last_attempt", date('Y-m-d H:i:s'));
    		$shaIpSecurityChecker->save();
    
    	}
    	 
    	return $shaIpSecurityChecker;
    }
    
    public function addAttempt(){
    	
    	$this
    		->setValue("last_attempt", date('Y-m-d H:i:s'))
    		->setValue("qty_attempts", $this->getValue("qty_attempts") + 1)
    		->save()
    	;
    	
    }
    
    /**
     * Check if IP already known
     * If in defined timeElapse, same ip has N bad connection
     * Return false
     */
    public function isValid() {
    	
    	$lastAttempt = new DateTime($this->getValue("last_attempt"));
    	$deadLine = new DateTime("NOW");
    	$deadLine->sub(new DateInterval('PT'.ShaParameter::get("IP_SECURITY_TIMEOUT").'S'));

        if ($lastAttempt <= $deadLine){
            return true;
        }

        if ($this->getValue("qty_attempts") <= ShaParameter::get("IP_SECURITY_MAX_ATTEMPTS")){
            return true;
        }

        if ($this->getValue("qty_attempts") == ShaParameter::get("IP_SECURITY_MAX_ATTEMPTS")){
            $msg = "
                Too much login attempts from IP ".$this->getValue("ip")." !
                Last attempt : ".$this->getValue("last_attempt")."
                Qty attempts : ".$this->getValue("qty_attempts")."
            ";
            ShaUtilsMail::sendMailToAdmin("Security alert !", $msg);
        }

    	return false;
    	
    }
    
    public static function clearOldEntries(){
    	self::bddExecute("DELETE FROM shaoline_ip_security_checker WHERE last_attempt < ( NOW() - INTERVAL ".ShaParameter::get("IP_SECURITY_TIMEOUT")." SECOND)");
    }

    
}

?>