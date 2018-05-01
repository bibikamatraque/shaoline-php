<?php



/**
 * String lib
 *
 * PHP version 5.3
 *
 * @category   Core
 * @package    ShaUtils
 * @subpackage Php
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    mon-referendum.com copyright
 *
 */
class ShaUtilsString {

	const CONSTANT_AZ_LOWER = "abcdefghijklmnopqrstuvwxyz";
	const CONSTANT_AZ_UPPER = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	const CONSTANT_09 = "0123456789";
	const CONSTANT_ALPHA = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	const CONSTANT_ALPHANUM = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	const CONSTANT_URLCHAR = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_";

	const PATTERN_ALPHA 						= 0;
	const PATTERN_ALPHANUM 						= 1;
	const PATTERN_DATE 							= 2;
	const PATTERN_DATEORDATETIME 				= 3;
	const PATTERN_DATETIME 						= 4;
	const PATTERN_DECIMAL 						= 5;
	const PATTERN_INTEGER 						= 6;
	const PATTERN_MAIL 							= 7;
	const PATTERN_POSITIVEINTEGER 				= 8;
	const PATTERN_VARIABLE 						= 9;
	const PATTERN_PATH 							= 10;
	const PATTERN_OPTIONAL_POSITIVE_INTERGER 	= 11;
	const PATTERN_ALL 							= 12;
	const PATTERN_BINARY 						= 13;
	const PATTERN_BOOLEAN						= 14;

	/**
	 * Complete un nombre avec des caracteres (zeros par defaut)
	 *
	 * @param string $text String to complete
	 * @param int    $n    Size wanted
	 * @param string $char Char to complete
	 *
	 * @return string
	 */
	public static function formatN($text, $n, $char = "0") {
		$text = $text . "";
		return self::repete($char, $n - strlen($text)) . $text;
	}

	/**
	 * Repete N fois une chaine
	 *
	 * @param string   $string Chaine a repeter
	 * @param interger $n      Nombre de repetitions
	 *
	 * @return string
	 */
	public static function repete($string, $n) {
		$result = "";
		for ($i = 0; $i < $n; $i++) {
			$result .= "" . $string;
		}
		return $result;
	}

	/**
	 * Renvoie la valeur ASCII codee sur N caracteres (par defaut 5) d'une chaine
	 *
	 * @param string   $text Texte a convertir
	 * @param interger $n    Taille de l'encodage
	 *
	 * @return string
	 */
	public static function getASCII($text, $n = 5) {
		$reponse = "";
		$lettre = "";
		$code = 0;
		$len = strlen($text);
		for ($i = 0; $i < $len; $i++) {
			$lettre = substr($text, $i, 1);
			$code = ord($lettre[0]);
			$reponse.=self::formatN($code, $n);
		}
		return $reponse;
	}

	/**
	 * Encode un caractere ASCII en CHAR
	 *
	 * @param integer $ascii Caractere a encoder
	 *
	 * @return string
	 */
	public static function encodedChar($ascii) {
		$char = "";
		$start = true;
		$len = strlen($ascii);
		for ($i = 0; $i < $len; $i++) {
			if ($start) {
				if ($ascii[$i] != "0") {
					$start = false;
					$char.=$ascii[$i];
				}
			} else {
				$char.=$ascii[$i];
			}
		}
		return "&#" . $char . ";";
	}

	/**
	 * Convertie un code ASCII code sur N caractere (5 par defaut) en chaine UTF-8
	 *
	 * @param string   $text Texte a convertir
	 * @param interger $n    Taille de l'encodage
	 *
	 * @return string
	 */
	public static function asciiTochar($text, $n=5) {
		if (!isset($text)) {
			return "";
		}
		$result = "";
		$len = strlen($text);
		for ($i = 0; $i < $len; $i+=5) {
			$result.=html_entity_decode(self::encodedChar(substr($text, $i, $n)), ENT_NOQUOTES, 'UTF-8');
		}
		$result = self::replace($result, "&#34;", '"');
		$result = self::replace($result, "&#39;", "'");
		return $result;
	}

	/**
	 * Remplace dans un texte une chaine par une autre
	 *
	 * @param string $text    Text a analyser
	 * @param array  $search  Chaine recherche
	 * @param array  $replace Chaine de remplacement
	 *
	 * @return string
	 */
	public static function replace($text, $search, $replace) {
		$search = array($search);
		$replace = array($replace);
		$result = str_replace($search, $replace, $text);
		unset($search);
		unset($replace);
		return $result;
	}

	/**
	 * Remplace toutes les occurence qui ne sont pas presente dans la liste autorisee
	 *
	 * @param string $text    Text a analyser
	 * @param string $allowed Caracteres autorise
	 * @param string $replace Caractere de remplacement
	 *
	 * @return string
	 */
	public static function reverseReplace($text, $allowed, $replace) {
		$result = "";
		$len = strlen($text);
		for ($i = 0; $i < $len; $i++) {
			if (strstr($allowed, $text[$i])) {
				$result.=$text[$i];
			} else {
				$result.=$replace;
			}
		}
		return $result;
	}

	/**
	 * Verifie si une valeur est de type alphanumerique
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexAlphanumeric($value) {
		return $value == "" || preg_match("/^[A-Za-z0-9\s]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type alphanumerique
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexMail($value) {
		return preg_match("/^[A-Za-z0-9._-]+@[A-Za-z0-9._-]{2,}\.[A-Za-z]{2,4}$/", $value);
	}

	/**
	 * Verifie si une valeur est de type date (separateur '-')
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexDate($value) {
		if ($value=="0000-00-00")return true;
		return preg_match("/^([0-9]{4})-([1-9]|0[1-9]|1[012])[-]([1-9]|0[1-9]|[12][0-9]|3[01])$/", $value);
	}

	/**
	 * Verifie si une valeur est de type datetime (separateur '-' et ':')
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexDatetime($value) {
		if ($value=="0000-00-00 00:00:00")return true;
		return preg_match("/^([0-9]{4})-([1-9]|0[1-9]|1[012])[-]([1-9]|0[1-9]|[12][0-9]|3[01]) ([0-9]|[01][0-9]|2[0-3]):([0-9]|[012345][0-9]):([0-9]|[012345][0-9])$/", $value);
	}

	/**
	 * Verifie si une valeur est de type date ou datetime (separateur '-' et ':')
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexDateOrDatetime($value) {
		if ($value=="0000-00-00 00:00:00")return true;
		return (self::isRegexDate($value) || self::isRegexDatetime($value));
	}

	/**
	 * Verifie si une valeur est de type nombre entier
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexInteger($value) {
		return preg_match("/^(-){0,1}[0-9]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type nombre entier positif
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexPositiveInteger($value) {
		return preg_match("/^[0-9]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type nombre decimale (separateur '.')
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexDecimal($value) {
		return preg_match("/^(-){0,1}[0-9.]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type alphabetique
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexAlpha($value) {
		return $value == "" || preg_match("/^[A-Za-z\s]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type variable (alphanum & '-' & '_' )
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexVariable($value) {
		return preg_match("/^[A-Za-z0-9-_]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type variable (alphanum & '-' & '_' )
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexURL($value) {
		return preg_match("/^[A-Za-z0-9-_]+\s$/\\.-_+:=?&#", $value);
	}

	/**
	 * Verifie si une valeur est de type URL (alphanum & '-' & '_' & '/' & '.')
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexPath($value) {
		return $value == "" || preg_match("/^[A-Za-z0-9-_.\/]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type binaire (0, 1)
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexBinary($value) {
		return $value == "" || preg_match("/^[0-1]+$/", $value);
	}

	/**
	 * Verifie si une valeur est de type binaire (0, 1)
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexBoolean($value) {
		return ($value == 0 || $value == 1 || $value == true || $value == false);
	}

	/**
	 * Verifie si une valeur est de type nombre entier positif
	 *
	 * @param string $value Value
	 *
	 * @return boolean
	 */
	public static function isRegexOptionalPositiveInteger($value) {
		return $value == "" || preg_match("/^[0-9]+$/", $value);
	}


	/**
	 * Confirm or not format of value
	 *
	 * @param string $value   Value
	 * @param string $pattern Pattern
	 *
	 * @return bool
	 */
	public static function isRegex($value, $pattern) {

		switch ($pattern) {
			case self::PATTERN_ALPHA:
				return self::isRegexAlpha($value);
				break;
			case self::PATTERN_ALPHANUM:
				return self::isRegexAlphanumeric($value);
				break;
			case self::PATTERN_DATE:
				return self::isRegexDate($value);
				break;
			case self::PATTERN_DATEORDATETIME:
				return self::isRegexDateOrDatetime($value);
				break;
			case self::PATTERN_DATETIME:
				return self::isRegexDatetime($value);
				break;
			case self::PATTERN_DECIMAL:
				return self::isRegexDecimal($value);
				break;
			case self::PATTERN_INTEGER:
				return self::isRegexInteger($value);
				break;
			case self::PATTERN_MAIL:
				return self::isRegexMail($value);
				break;
			case self::PATTERN_POSITIVEINTEGER:
				return self::isRegexPositiveInteger($value);
				break;
			case self::PATTERN_VARIABLE:
				return self::isRegexVariable($value);
				break;
			case self::PATTERN_PATH:
				return self::isRegexPath($value);
				break;
			case self::PATTERN_OPTIONAL_POSITIVE_INTERGER:
				return self::isRegexOptionalPositiveInteger($value);
				break;
			case self::PATTERN_ALL:
				return true;
				break;
			case self::PATTERN_BINARY:
				return self::isRegexBinary($value);
				break;
			case self::PATTERN_BOOLEAN:
				return self::isRegexBoolean($value);
				break;
			default:
				return false;
		}
	}

	/**
	 * Modifie un text pour eviter les injection SQL
	 *
	 * @param string $value Value
	 *
	 * @return string
	 */
	public static function cleanForSQL($value) {
		$value = self::replace($value, "'", "''");
		return $value;
	}

	/**
	 * Modifie un text pour eviter les problemes d'envoie de mail
	 *
	 * @param string $value Value
	 *
	 * @return string
	 */
	public static function cleanForMail($value) {
		$value = self::cleanForBalise($value);
		$value = self::replace($value, "<", "");
		$value = self::replace($value, ">", "");
		$value = self::replace($value, "&", "");
		$value = self::replace($value, '"', ' ');
		return $value;
	}

	/**
	 * Modifie un text pour eviter les injection SQL
	 *
	 * @param string $value Value
	 *
	 * @return string
	 */
	public static function cleanForJs($value) {
		$value = self::replace($value, "'", "&#39;");
		return $value;
	}

	/**
	 * Modifie un text pour eviter les injection XSS
	 *
	 * @param string $value Value
	 *
	 * @return string
	 */
	public static function cleanForBalise($value, $removeBreaks = false) {
        $value = self::replace($value, "&", "&#38;");
		$value = self::replace($value, "<", "&#60;");
		$value = self::replace($value, ">", "&#62;");
		return $value;
	}

	/**
	 * Place un antislash devant un caractere (souvent guillemet ou double guillemet) selon le cas
	 *
	 * @param string $text Texte a modifier
	 * @param string $char Caractere a proteger
	 *
	 * @return string
	 */
	public static function backslashProtection($text, $char) {
		return self::replace($text, $char, "\\" . $char);
	}

	/**
	 * Protege a l'affichage un text entre quote ou double quote
	 *
	 * @param string $text Texte a modifier
	 * @param string $char Caractere a proteger
	 *
	 * @return string
	 */
	public static function classicalBackslashProtection($text, $char) {
		$text = self::clearDbl($text, $char);
		$text = self::clearDbl($text, "\\");
		$text = self::backslashProtection($text, "\\");
		$text = self::backslashProtection($text, $char);
		return $text;
	}


	/**
	 * Rewrite URL replacing spicals char
	 *
	 * @param string $text           Text to format
	 * @param string $replace        Replacer char (default='-')
	 * @param string $bClearUrlSlash Delete or not '/'
	 *
	 * @return string
	 */
	public static function formatForURL($chaine, $replace = "-", $bClearUrlSlash=true) {
	
		$str_array = array(
				chr(195).chr(138)=>'e',
				chr(195).chr(169)=>'e',
				chr(195).chr(168)=>'e',
				chr(195).chr(170)=>'e',
				chr(195).chr(171)=>'e',
				chr(195).chr(160)=>'a',
				chr(195).chr(167)=>'c',
				chr(195).chr(160)=>'a',
				chr(195).chr(162)=>'a',
				chr(195).chr(180)=>'o',
				chr(195).chr(150)=>'o',
				chr(195).chr(182)=>'o',
				chr(195).chr(188)=>'u',
				chr(195).chr(175)=>'i',
				chr(195).chr(174)=>'i'
		);
		$chaine = str_replace(
				array_keys($str_array),
				array_values($str_array),
				$chaine
		);
	
		$chaine = strtolower($chaine);
		$chaine = preg_replace('#([^a-z0-9]+)#i', $replace, $chaine);
		$chaine = ShaUtilsString::clearDbl($chaine, $replace);
		$chaine = preg_replace('#'.$replace.'$#','',$chaine);
		$chaine = preg_replace('#^'.$replace.'#','',$chaine);
		return $chaine;
	
	
	}
	
	/**
	 * Protection contre Cmsles quote et les antislash (ajout un antiSlash)
	 *
	 * @param string $text Text a proteger
	 *
	 * @return string
	 */
	public static function quoteProtection($text) {
		return self::classicalBackslashProtection($text, "'");
	}

	/**
	 * Protection contre les double quote et les antislash (ajout un antiSlash)
	 *
	 * @param string $text Text protecte
	 *
	 * @return string
	 */
	public static function quoteDblProtection($text) {
		return self::classicalBackslashProtection($text, '"');
	}

	/**
	 * Protection pour les chaines JSON
	 *
	 * @param string $text Text a proteger
	 *
	 * @return string
	 */
	public static function jsonProtection($text) {
		$text = self::quoteDblProtection($text);
		$text = self::replace($text, "\n", "\\n");
		return $text;
	}

	/**
	 * Supprime les doublons dans un texte
	 *
	 * @param string $text Text a analyser
	 * @param string $char Caractere a dedoublonner
	 *
	 * @return string
	 */
	public static function clearDbl($text, $char) {
		do {
			$oldText = $text;
			$text = self::replace($text, $char . $char, $char);
		} while ($text != $oldText);
		return $text;
	}

	/**
	 * Replace special char in text
	 *
	 * @param string $text    Text to change
	 * @param string $replace Char to replace
	 *
	 * @return string
	 */
	public static function rewrittingFormat($text, $replace = "-") {
		$text = self::formatForURL($text);
		$text = self::replace($text, "-", $replace);
		return $text;
	}


	/**
	 * Rewrite text for url
	 *
	 * @param string $text Text to change
	 *
	 * @return string
	 */
	public static function rewrittingFormatForUrl($text) {
		$text = self::rewrittingFormat($text);
		$text = substr($text, 0, 300);
		return $text;
	}

	/**
	 * Nettoie les libelle pour les statistique
	 *
	 * @param string $value Valeur a ecrire
	 * @param string $max   Taille maximum de la valeur de retour
	 *
	 * @return string
	 */
	public static function rewrittingForStat($value, $max) {
		$value = self::rewrittingFormat($value, " ");
		$plus = (strlen($value) > $max) ? "..." : "";
		$value = substr($value, 0, $max);
		$value.=$plus;
		return $value;
	}

	/**
	 * Crypte une chaine (via une cle de cryptage
	 *
	 * @param string $maCleDeCryptage  Key
	 * @param string $maChaineACrypter Text
	 *
	 * @return string
	 */
	public static function crypter($maCleDeCryptage, $maChaineACrypter) {
		if ($maCleDeCryptage == "") {
			$maCleDeCryptage = $GLOBALS['PHPSESSID'];
		}
		$maCleDeCryptage = md5($maCleDeCryptage);
		$letter = -1;
		$newstr = '';
		$strlen = strlen($maChaineACrypter);
		for ($i = 0; $i < $strlen; $i++) {
			$letter++;
			if ($letter > 31) {
				$letter = 0;
			}
			$neword = ord($maChaineACrypter{$i}) + ord($maCleDeCryptage{$letter});
			if ($neword > 255) {
				$neword -= 256;
			}
			$newstr .= chr($neword);
		}
		return base64_encode($newstr);
		return $maChaineACrypter;
	}

	/**
	 * Decrypte une chaine (via une cle de cryptage
	 *
	 * @param string $maCleDeCryptage Key
	 * @param string $maChaineCrypter Text
	 *
	 * @return string
	 */
	public static function decrypter($maCleDeCryptage, $maChaineCrypter) {
		if ($maCleDeCryptage == "") {
			$maCleDeCryptage = $GLOBALS['PHPSESSID'];
		}
		$maCleDeCryptage = md5($maCleDeCryptage);
		$letter = -1;
		$newstr = '';
		$maChaineCrypter = base64_decode($maChaineCrypter);
		$strlen = strlen($maChaineCrypter);
		for ($i = 0; $i < $strlen; $i++) {
			$letter++;
			if ($letter > 31) {
				$letter = 0;
			}
			$neword = ord($maChaineCrypter{$i}) - ord($maCleDeCryptage{$letter});
			if ($neword < 1) {
				$neword += 256;
			}
			$newstr .= chr($neword);
		}
		return $newstr;
		return $maChaineCrypter;
	}

	/**
	 * Clear space
	 *
	 * @param string $text Text
	 *
	 * @return string
	 */
	public static function noSpace($text){
		return self::replace($text, " ", "");
	}


	/**
	 * Delete all non numeric caracteres
	 *
	 * @param string $text Text
	 *
	 * @return string
	 */
	public static function forceNumber($text){
		$result = "";
		$len = strlen($text);
		for ($i=0; $i<$len; $i++) {
			$letter = substr($text, $i, 1);
			if ($letter==",") {
				$result.=".";
			} else if ($letter=="." || $letter=="-" || ShaUtilsString::isRegexInteger($letter)) {
				$result.=$letter;
			} else {
				//RAS
			}
		}
		return $result;
	}


	/**
	 * Delete all non numeric caracteres
	 *
	 * @param string $text        Text
	 * @param string $charAllowed Char allowed
	 *
	 * @return string
	 */
	public static function forceChar($text, $charAllowed){
		$result = "";
		$len = strlen($text);
		for ($i=0; $i<$len; $i++) {
			$letter = substr($text, $i, 1);
			if (strripos($charAllowed, $letter)!==false) {
				$result.=$letter;
			}
		}
		return $result;
	}

	/**
	 * Transform frence date to english date
	 *
	 * @param string $input        Text
	 * @param string $formatRoot   ShaFormat
	 * @param string $formatTarget ShaFormat target
	 *
	 * @return string
	 */
	public static function convertDateFormat($input, $formatRoot, $formatTarget){
		$d = DateTime::createFromFormat($formatRoot, $input);
		return $d->format($formatTarget);
	}

	/**
	 * Change double quote '"' to special quotes '`'
	 *
	 * @param string $text Text
	 *
	 * @return string
	 */
	public static function changeDblQuoteToSpecialQuote($text){
		return self::replace($text,'"','`');
	}

	/**
	 * Transform news description with datas
	 *
	 * @param string $sText  Text to transform
	 * @param string $sDatas Mapping keys/values
	 *
	 * @return string
	 */
	public static function sprint($sText, $aDatas){
		$iDataSize = count($aDatas);
		for ($i = 0; $i < $iDataSize; $i++) {
			$sText = ShaUtilsString::replace($sText, "%".$i, $aDatas[$i]);
		}
		return $sText;
	}

    /**
     * Replace all UTF8 char by real value
     *
     * @param $text
     *
     * @return mixed
     */
    /*public static function cleanUtf8Char($text) {
        $replace = array(
            "Ãƒâ€¦ " => "Ã…Â ", "Ãƒâ€¦Ã‚Â¡" => "Ã…Â¡", "Ãƒâ€¦'" => "Ã…â€™", "Ãƒâ€¦\"" => "Ã…â€œ",
            "Ãƒâ€¦Ã‚Â¸" => "Ã…Â¸", "ÃƒÆ’Ã‚Â¿" => "ÃƒÂ¿", "ÃƒÆ’Ã¢â€šÂ¬" => "Ãƒâ‚¬", "ÃƒÆ’ " => "ÃƒÂ ",
            "ÃƒÆ’Ã‚ï¿½" => "Ãƒï¿½", "ÃƒÆ’Ã‚Â¡" => "ÃƒÂ¡", "ÃƒÆ’Ã¢â‚¬Å¡" => "Ãƒâ€š", "ÃƒÆ’Ã‚Â¢" => "ÃƒÂ¢",
            "ÃƒÆ’Ã†â€™" => "ÃƒÆ’", "ÃƒÆ’Ã‚Â£" => "ÃƒÂ£", "ÃƒÆ’Ã¢â‚¬Å¾" => "Ãƒâ€ž", "ÃƒÆ’Ã‚Â¤" => "ÃƒÂ¤",
            "ÃƒÆ’Ã¢â‚¬Â¦" => "Ãƒâ€¦", "ÃƒÆ’Ã‚Â¥" => "ÃƒÂ¥", "ÃƒÆ’Ã¢â‚¬Â " => "Ãƒâ€ ", "ÃƒÆ’Ã‚Â¦" => "ÃƒÂ¦",
            "ÃƒÆ’Ã¢â‚¬Â¡" => "Ãƒâ€¡", "ÃƒÆ’Ã‚Â§" => "ÃƒÂ§", "ÃƒÆ’Ã‹â€ " => "ÃƒË†", "ÃƒÆ’Ã‚Â¨" => "ÃƒÂ¨",
            "ÃƒÆ’Ã¢â‚¬Â°" => "Ãƒâ€°", "e" => "ÃƒÂ©", "ÃƒÆ’Ã…Â " => "ÃƒÅ ", "ÃƒÆ’Ã‚Âª" => "ÃƒÂª",
            "ÃƒÆ’Ã¢â‚¬Â¹" => "Ãƒâ€¹", "ÃƒÆ’Ã‚Â«" => "ÃƒÂ«", "ÃƒÆ’Ã…â€™" => "ÃƒÅ’", "ÃƒÆ’Ã‚Â¬" => "ÃƒÂ¬",
            "ÃƒÆ’Ã‚ï¿½" => "Ãƒï¿½", "ÃƒÆ’Ã‚Â­" => "ÃƒÂ­", "ÃƒÆ’Ã…Â½" => "ÃƒÅ½", "ÃƒÆ’Ã‚Â®" => "ÃƒÂ®",
            "ÃƒÆ’Ã‚ï¿½" => "Ãƒï¿½", "ÃƒÆ’Ã‚Â¯" => "ÃƒÂ¯", "ÃƒÆ’Ã‚ï¿½" => "Ãƒï¿½", "ÃƒÆ’Ã‚Â°" => "ÃƒÂ°",
            "ÃƒÆ’'" => "Ãƒâ€˜", "ÃƒÆ’Ã‚Â±" => "ÃƒÂ±", "ÃƒÆ’'" => "Ãƒâ€™", "ÃƒÆ’Ã‚Â²" => "ÃƒÂ²",
            "ÃƒÆ’\"" => "Ãƒâ€œ", "ÃƒÆ’Ã‚Â³" => "ÃƒÂ³", "ÃƒÆ’\"" => "Ãƒâ€�", "ÃƒÆ’Ã‚Â´" => "ÃƒÂ´",
            "ÃƒÆ’Ã¢â‚¬Â¢" => "Ãƒâ€¢", "ÃƒÆ’Ã‚Âµ" => "ÃƒÂµ", "ÃƒÆ’Ã¢â‚¬â€œ" => "Ãƒâ€“", "ÃƒÆ’Ã‹Å“" => "ÃƒËœ",
            "ÃƒÆ’Ã‚Â¸" => "ÃƒÂ¸", "ÃƒÆ’Ã¢â€žÂ¢" => "Ãƒâ„¢", "ÃƒÆ’Ã‚Â¹" => "ÃƒÂ¹", "ÃƒÆ’Ã…Â¡" => "ÃƒÅ¡",
            "ÃƒÆ’Ã‚Âº" => "ÃƒÂº", "ÃƒÆ’Ã¢â‚¬Âº" => "Ãƒâ€º", "ÃƒÆ’Ã‚Â»" => "ÃƒÂ»", "ÃƒÆ’Ã…â€œ" => "ÃƒÅ“",
            "ÃƒÆ’Ã‚Â¼" => "ÃƒÂ¼", "ÃƒÆ’Ã‚ï¿½" => "Ãƒï¿½", "ÃƒÆ’Ã‚Â½" => "ÃƒÂ½", "ÃƒÆ’Ã…Â¾" => "ÃƒÅ¾",
            "ÃƒÆ’Ã‚Â¾" => "ÃƒÂ¾", "ÃƒÆ’Ã…Â¸" => "ÃƒÅ¸", "ÃƒÆ’Ã‚Â¶" => "ÃƒÂ¶"
        );
        foreach($replace as $key => $val)
            $text = str_replace($key, $val, $text);
        return $text;
    }*/


    /**
     * Explode all string contain in an array and return other array
     *
     * @param string $glue Separator
     * @param array $array Array to analyze
     *
     * @return array
     */
    private static function _explodeArray($glue, $array)
    {
        $result = array();
        foreach ($array as $item) {
            $result = array_merge($result, explode($glue, $item));
        }
        return $result;
    }

    /**
     * Return true if string is upper case
     *
     * @param string $string
     *
     * @return boolean
     */
    public static function isUpperCase($string) {
    	return ($string == strtoupper($string));
    }

    /**
     * Return true if string is lower case
     *
     * @param string $string
     *
     * @return boolean
     */
    public static function isLowerCase($string){
    	return ($string == strtolower($string));
    }


    /**
     * Return pur cent of present words in array
     *
     * @param array $array Word array
     * @param string $text Text to analyse
     *
     * @return int
     *
     */
    public static function purCentSameWords($array,$text) {
        $total = count($array);
        $onePurCent = $total / 100;
        $cpt = 0;
        foreach ($array as $word) {
            if (stripos($text,$word)!==false) {
                $cpt++;
            }
        }
        return round($cpt / $onePurCent, 0);
    }

    /**
     * Converts all accent characters to ASCII characters.
     *
     * If there are no accent characters, then the string given is just returned.
     *
     * @since 1.2.1
     *
     * @param string $string Text that might have accent characters
     * @return string Filtered string with replaced "nice" characters.
     */
    /*public function removeAccents($string) {
    	if ( !preg_match('/[\x80-\xff]/', $string) )
    		return $string;
    
    	if (seems_utf8($string)) {
    		$chars = array(
    				// Decompositions for Latin-1 Supplement
    				chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
    				chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    				chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    				chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    				chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
    				chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
    				chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
    				chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
    				chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
    				chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
    				chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    				chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    				chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    				chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    				chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    				chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
    				chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
    				chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
    				chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
    				chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
    				chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    				chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    				chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    				chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    				chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
    				chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
    				chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
    				chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
    				chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
    				chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
    				chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
    				chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
    				// Decompositions for Latin Extended-A
    				chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    				chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    				chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    				chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    				chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    				chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    				chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    				chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    				chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    				chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    				chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    				chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    				chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    				chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    				chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    				chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    				chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    				chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    				chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    				chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    				chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    				chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    				chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    				chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    				chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    				chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    				chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    				chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    				chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    				chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    				chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    				chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    				chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    				chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    				chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    				chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    				chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    				chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    				chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    				chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    				chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    				chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    				chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    				chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    				chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    				chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    				chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    				chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    				chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    				chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    				chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    				chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    				chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    				chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    				chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    				chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    				chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    				chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    				chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    				chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    				chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    				chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    				chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    				chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
    				// Decompositions for Latin Extended-B
    				chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
    				chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
    				// Euro Sign
    				chr(226).chr(130).chr(172) => 'E',
    				// GBP (Pound) Sign
    				chr(194).chr(163) => '');
    
    		$string = strtr($string, $chars);
    	} else {
    		// Assume ISO-8859-1 if not UTF-8
    		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
    		.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
    		.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
    		.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
    		.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
    		.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
    		.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
    		.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
    		.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
    		.chr(252).chr(253).chr(255);
    
    		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
    
    		$string = strtr($string, $chars['in'], $chars['out']);
    		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
    		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
    		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
    	}
    
    	return $string;
    }*/

    public static function cryptApr1Md5($plainpasswd)
    {
    	$tmp = "";
    	$salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
    	$len = strlen($plainpasswd);
    	$text = $plainpasswd.'$apr1$'.$salt;
    	$bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
    	for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
    	for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
    	$bin = pack("H32", md5($text));
    	for($i = 0; $i < 1000; $i++)
    	{
    	$new = ($i & 1) ? $plainpasswd : $bin;
    	if ($i % 3) $new .= $salt;
    	if ($i % 7) $new .= $plainpasswd;
    	$new .= ($i & 1) ? $bin : $plainpasswd;
    	$bin = pack("H32", md5($new));
    	}
    	for ($i = 0; $i < 5; $i++)
    	{
    	$k = $i + 6;
    	$j = $i + 12;
    	if ($j == 16) $j = 5;
    		$tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
    	}
    	$tmp = chr(0).chr(0).$bin[11].$tmp;
    	$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
    	"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
    	"./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
    
    	return "$"."apr1"."$".$salt."$".$tmp;
	}
    
}

?>