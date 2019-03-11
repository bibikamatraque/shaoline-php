<?php



/**
 * Random lib
 *
 * PHP version 5.3
 *
 * @category   Core
 * @package    ShaUtils
 * @subpackage Php
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Shaoline-php copyright
 * @link        No link
 *
 */
class ShaUtilsRand{
	
	/**
	 * Return random integer (included)
	 * 
	 * @param int $iMin
	 * @param int $iMax
	 * 
	 * @return number
	 */
	public static function getRandInt($iMin, $iMax){
		return rand($iMin, $iMax);
	}
	
	/**
	 * REturn random item of array
	 * 
	 * @param array $aArray
	 * 
	 * @return NULL|unknown
	 */
	public static function getRandItemInArray($aArray){
		$iMax = count($aArray) - 1;
		if ($iMax == -1){
			return null;
		}
		$iRand = self::getRandInt(0, $iMax);
		return $aArray[$iRand];
	}
	
	
}

?>