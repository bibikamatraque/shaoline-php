/**
 * Class UtilsRegex
 * 
 * @category   Bus
 * @package    Utils
 * @subpackage Js
 * @author     bduhot <bastien.duhot@free.fr>
 * @license    Shaoline copyright
 * @version    1.0.0
 * @link       no link
 *
 */
function UtilsRegex() {}

/**
 * Block all non positive integer char
 * 
 * @param string iIdDom Input dom id 
 * 
 * @return void
 */
UtilsRegex.inputKeepOnlyPositiveInteger = function(iIdDom){
	var oDomItem = $j("#"+iIdDom); 
	if (oDomItem.length>0){
		oDomItem.val(oDomItem.val().replace(/[^0-9]/g, ''));
	}
}

/**
 * Block all non integer char
 * 
 * @param string iIdDom Input dom id 
 * 
 * @return void
 */
UtilsRegex.inputKeepOnlyInteger = function(iIdDom){
	var oDomItem = $j("#"+iIdDom); 
	if (oDomItem.length>0){
		oDomItem.val(oDomItem.val().replace(/[^0-9-]/g, ''));
	}
}

/**
 * Block all non positive decimal char
 * 
 * @param string iIdDom Input dom id 
 * 
 * @return void
 */        
UtilsRegex.inputKeepOnlyPositiveDecimal = function(iIdDom){
	var oDomItem = $j("#"+iIdDom); 
	if (oDomItem.length>0){
		oDomItem.val(oDomItem.val().replace(/[^0-9.]/g, ''));
	}
}

/**
 * Block all non decimal char
 * 
 * @param string iIdDom Input dom id 
 * 
 * @return void
 */
UtilsRegex.inputKeepOnlyDecimal = function(iIdDom){
	var oDomItem = $j("#"+iIdDom); 
	if (oDomItem.length>0){
		oDomItem.val(oDomItem.val().replace(/[^0-9.-]/g, ''));
	}
}

/**
 * Checks if input is formatted as decimal, allowing ',' and '.'. Delete value if not.
 * 
 * @param string iIdDom Input dom id 
 * 
 * @return void
 */
UtilsRegex.inputEmptyIfNotDecimalCommaAllowed = function(iIdDom){
	var oDomItem = $j("#"+iIdDom);
	if (oDomItem.length>0){
		if (!oDomItem.val().match("^([0-9]+([\.,][0-9]+)?)?$")) {
			oDomItem.val("");
		}
	}
}

/**
 * V�rifie si une valeur est de type alphanum�rique
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isAlphanumeric = function(sValue) {
	return sValue == "" || sValue.match("^[A-Za-z0-9\s]+$");
}

/**
 * V�rifie si une valeur est de type alphanum�rique
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isMail = function(sValue) {
	return sValue.match("^[A-Za-z0-9._-]+@[A-Za-z0-9._-]{2,}\.[A-Za-z]{2,4}$");
}

/**
 * V�rifie si une valeur est de type date (s�parateur '-')
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isEnglishDate = function(sValue) {
	if (sValue=="0000-00-00")return true;
	var reg=new RegExp("^[0-9]{4}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$","g");
	return reg.test(sValue);
}

/**
 * V�rifie si une valeur est de type datetime (s�parateur '-' et ':')
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isEnglishDatetime = function(sValue) {
	if (sValue=="0000-00-00 00:00:00")return true;
	return sValue.match("^([0-9]{4})-([1-9]|0[1-9]|1[012])[-]([1-9]|0[1-9]|[12][0-9]|3[01]) ([0-9]|[01][0-9]|2[0-3]):([0-9]|[012345][0-9]):([0-9]|[012345][0-9])$");
}

/**
 * V�rifie si une valeur est de type date ou datetime (s�parateur '-' et ':')
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isDateOrDatetime = function(sValue) {
	if (sValue=="0000-00-00 00:00:00")return true;
	return (ShaUtilsRegex.isRegexDate(sValue) || UtilsRegex.isRegexDatetime(sValue));
}

/**
 * V�rifie si une valeur est de type nombre entier
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isInteger = function(sValue) {
	return sValue.match("^(-){0,1}[0-9]+$");
}

/**
 * V�rifie si une valeur est de type nombre entier positif
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isPositiveInteger = function(sValue) {
	return sValue.match("^[0-9]+$");
}

/**
 * V�rifie si une valeur est de type nombre d�cimale (s�parateur '.')
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isDecimal = function(sValue) {
	return sValue.match("^(-){0,1}[0-9.]+$");
}

/**
 * V�rifie si une valeur est de type alphab�tique
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isAlpha = function(sValue) {
	return sValue == "" || sValue.match("^[A-Za-z\s]+$");
}

/**
 * V�rifie si une valeur est de type variable (alphanum & '-' & '_' )
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isVariable = function(sValue) {
	return sValue.match("^[A-Za-z0-9-_]+$");
}

/**
 * V�rifie si une valeur est de type variable (alphanum & '-' & '_' )
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isURL = function(sValue) {
	return sValue.match("^[A-Za-z0-9-_]+\s$/\\.-_+:=?&#");
}

/**
 * V�rifie si une valeur est de type URL (alphanum & '-' & '_' & '/' & '.')
 *
 * @param string sValue Value
 *
 * @return boolean
 */
UtilsRegex.isPath = function(sValue) {
	return sValue == "" || sValue.match("^[A-Za-z0-9-_.\/]+$");
}
	