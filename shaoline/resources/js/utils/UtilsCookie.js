/**
 * Class UtilsCookie (common cookie object)
 * 
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
function UtilsCookie() {}

/**
 * Set cookie
 * 
 * @param string sName             Cookie name
 * @param string sValue	            Cookie value
 * @param int    iExpirtationHours Cookie duration live (in hours)
 * 
 * @return void
 */
UtilsCookie.setCookie = function (sName, sValue, iExpirtationHours) {
	var oDate = new Date();
	oDate.setTime(oDate.getTime() + (iExpirtationHours * 60 * 60 * 1000));
	var sExpires = "expires=" + oDate.toGMTString();
	document.cookie = sName + "=" + sValue + "; " + sExpires;
}

/**
 * Return cookie value
 * 
 * @param string sName Cookie name
 * 
 * @returns string
 */
UtilsCookie.getCookie = function (sName) {
	var name = sName + "=";
	var sContent; 
	var aCookie = document.cookie.split(';');
	for(var i = 0; i < aCookie.length; i++) {
		
		sContent = aCookie[i].trim();
		if (0 == sContent.indexOf(sName)) {
			return sContent.substring(sName.length + 1, sContent.length);
		}
		
	}
	return "";
}

/**
 * Delete cookies
 * 
 * @param string sName Cookie name
 * 
 * @return void
 */
UtilsCookie.drop = function (sName) {
	UtilsCookie.setCookie(sName, "", -1);
}