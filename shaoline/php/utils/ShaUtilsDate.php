<?php



/**
 * Date lib
 *
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  Shaoline-php copyright
 * @link     No link
 *
 */
class ShaUtilsDate {
	
	/**
	 * 
	 * @param unknown $sStartDateTime
	 * @param unknown $sCalendrierPattern
	 * @throws Exception
	 * @return multitype:NULL
	 */
	public static function getPeriodeTic($sStartDateTime, $sCalendrierPattern) {
	
		//Getting NOW object
		$oNow = new DateTime("NOW");
		$oStartDate = new DateTime($sStartDateTime);
	
		//Construct operand
		$aCalendarItems = explode(" ", $sCalendrierPattern);
		$aCalendarZeroedItems = explode(" ", str_replace("*", "0", $sCalendrierPattern));
		if (count($aCalendarItems) != 4) {
			throw new Exception(T('Bad "aCalendarItems" parameter, must be an englis format : "'.$sCalendrierPattern.'"'));
		}
	
		//Calculate tic interval
		$oTicDiff = new DateInterval('P'.$aCalendarZeroedItems[3].'Y'.$aCalendarZeroedItems[2].'M'.$aCalendarZeroedItems[1].'DT'.$aCalendarZeroedItems[0].'H0M0S');
	
		//Calculate first TIC
		$sStartingTic = "";
		$sStartingTic .= ('*' == $aCalendarItems[3]) ? $oStartDate->format("Y") : '00'; //Calculate year
		$sStartingTic .= '/';
		$sStartingTic .= ('*' == $aCalendarItems[2]) ? $oStartDate->format("m") : '00'; //Calculate month
		$sStartingTic .= '/';
		$sStartingTic .= ('*' == $aCalendarItems[1]) ? $oStartDate->format("d") : '00'; //Calculate day
		$sStartingTic .= ' ';
		$sStartingTic .= ('*' == $aCalendarItems[0]) ? $oStartDate->format("H") : '00'; //Calculate hour
		$sStartingTic .= ':00:00';
		$oStartingTic = new DateTime($sStartingTic);
		while ($oStartingTic < $oStartDate) {
			$oStartingTic->add($oTicDiff);
		}
	
		//Stock result
		$aResult = array();
		while ($oStartingTic < $oNow) {
			$aResult[] = $oStartingTic->format("Y/m/d H:i:s");
			$oStartingTic->add($oTicDiff);
		}
	
		return $aResult;
	
	}
	
	/**
	 * Write date with defined precision
	 * 
	 * @param Date $date      Instance of date
	 * @param int  $precision Precision [0=Y, 1=Y-m, 2=Y-m-d]
	 * 
	 * @return type
	 */
	public static function writePrecision($date,$precision){
		$d = new DateTime($date);
		switch($precision){
			case "0":
				return $d->format("Y");
			case "1":
				return $d->format("Y-n");
			case "2":
				return $d->format("Y-n-j");
		}
	}

	/**
	 * Convertie une quantité de temps en texte compte à rebouir de secondes, minutes, heures, jours, années.
	 * 
	 * @param type $dateDiff quantité de temps
	 * 
	 * @return string
	 */
	public static function getStringDelay($dateDiff) {
		if ($dateDiff < 0) {
			return "0s";
		}
		//< 1 minute
		if (round($dateDiff / 60) - 1 < 1) {
			return $dateDiff . ShaContext::t("s");
		}
		//< 1 heure
		if (round($dateDiff / 60) - 1 < 60) {
			return round($dateDiff / 60) . ShaContext::t("min");
		}
		//< 24 heures
		if (round($dateDiff / (3600)) - 1 < 24) {
			return round($dateDiff / (3600)) . ShaContext::t("h");
		}
		//< 1 an
		if (round($dateDiff / (3600 * 24)) - 1 < 365) {
			return round($dateDiff / (3600 * 24)) . ShaContext::t("j");
		}
		//>= 1 an
		return ShaContext::t("more than 1 year");
	}
	
	/**
	 * Sub function 
	 * 
	 * @param unknown &$oDateTimeA Date A
	 * @param unknown $oDateTimeB  Date B
	 * 
	 * @return void
	 */
	public static function dateTimeSub(&$oDateTimeA, $oDateTimeB) {
		$sY = $oDateTimeB->format('Y'); 
		$sM = $oDateTimeB->format('m');
		$sD = $oDateTimeB->format('d');
		$sH = $oDateTimeB->format('H');
		$sI = $oDateTimeB->format('i');
		$sS = $oDateTimeB->format('s');
		$oInterval = new DateInterval("P".$sY."Y".$sM."M".$sD."DT".$sH."H".$sI."M".$sS."S");
		$oDateTimeA->sub($oInterval);
	}

    /**
     * Return readable delay
     *
     * @param $dateDown
     * @param $dateUp
     *
     * @return string
     */
    public static function humanDelay($dateDown, $dateUp = null) {

        $dateDown = strtotime($dateDown);
        $dateUp = ($dateUp == null) ? strtotime('now') : strtotime($dateUp);

        $dateDiff = $dateUp - $dateDown;

        if ($dateDiff < 0)
            return array(0, "s");
        if (round($dateDiff / 60) - 1 < 1)
            return array($dateDiff, "s");
        if (round($dateDiff / 60) - 1 < 60)
            return array(round($dateDiff / 60), "min");
        if (round($dateDiff / (3600)) - 1 < 24)
            return array(round($dateDiff / 3600), "h");
        if (round($dateDiff / (3600 * 24)) - 1 < 365)
            return array(round($dateDiff / (3600 * 24)), "d");
        return array("", "more_than_one_year");
    }

    public static function getAge($datetime) {
    	
    	$now = new DateTime();
    	$datetime = new DateTime($datetime);
    	return $now->getTimestamp () - $datetime->getTimestamp();
    }
	
}

?>