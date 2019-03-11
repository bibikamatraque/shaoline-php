<?php



/**
 * Network lib
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
class ShaUtilsNetwork
{


	/**
	 * Check if browser is allowed
	 * Write error HTML code if not and return false
	 * 
	 * @return boolean
	 */
	function testBrowser() {

		if (!isset($_SERVER["HTTP_USER_AGENT"])) {
			echo ShaContext::t("BadBrowser");
			return true;
		}

		if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE 6") !== false) {
			echo ShaContext::t("NeedUpdateBrowser");
			return true;
		}
		return false;
	}


	public static function strToHex($string)
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
		{
		$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	

	
	/**
	 * Get important word of text
	 *
	 * @param string $text Text
	 *
	 * @return string
	 */
	public static function formatForKeyword($text) {
		return $text;
		/*$word = explode(" ", $text);
		$keyword = "";
		$cpt = 0;
		foreach ($word as $word) {
			if (strlen($word) >= 5 && $cpt < 20) {
				$keyword.=$word . " ";
				$cpt++;
			}
		}
		return $keyword;*/
	}

	/**
	 * ShaFormat text for typical website description
	 * 
	 * @param string $text Text to format
	 * 
	 * @return string
	 */
	public static function formatForDescription($text) {
		//return substr(ShaUtilsString::quoteDblProtection($text),0,150);
		return $text;
	}


}

?>