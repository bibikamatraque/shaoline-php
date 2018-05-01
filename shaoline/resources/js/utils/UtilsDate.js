/**
 * Class UtilsDate
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
function UtilsDate() {}


/**
 * Convert date format from 1/2/3 to 3/2/1
 * 
 * @param string sDate      Date to convert
 * @param string sSeparator Input seperator (default '/')
 * @param string sSeparator Output seperator (default '/')
 * 
 * Ex : UtilsDate.inverseFormat('12/08/2012', '/', '-'); => '2012-08-12'
 * Ex : UtilsDate.inverseFormat('12/08/2012', '/', '-'); => '2012-08-12'
 * 
 * @return void
 */
UtilsDate.inverseFormat = function(sDate, sInputSeparator, sOutputSeparator){
	
	if (sDate == undefined){
		return '';
	}
	
	if (sInputSeparator == undefined){
		sInputSeparator = "/";
	}
	if (sOutputSeparator == undefined){
		sOutputSeparator = "/";
	}
	
	var aDateItems = sDate.split(sInputSeparator);
	if (aDateItems.length != 3) {
		console.log("(ShaUtilsDate.inverseFormat) Bad entry date format '" + sDate + "'");
		return ''; 
	}
	
	return aDateItems[2] + sOutputSeparator +  aDateItems[1] + sOutputSeparator +  aDateItems[0];
}

/**
 * Convert date format from jj/mm/yyyy to yyyy/mm/jj
 * 
 * @param string sDate      Date to converte
 * @param string sSeparator Input seperator (default '/')
 * @param string sSeparator Output seperator (default '/')
 * 
 * Ex : UtilsDate.convertFrenchToEnglish('12/08/2012', '/', '-'); => '2012-08-12'
 * 
 * @return void
 */
UtilsDate.convertFrenchToEnglish = function(sDate, sInputSeparator, sOutputSeparator){
	return UtilsDate.inverseFormat(sDate, sInputSeparator, sOutputSeparator);
}

/**
 * Convert date format from yyyy/mm/jj to jj/mm/yyyy
 * 
 * @param string sDate      Date to converte
 * @param string sSeparator Input seperator (default '/')
 * @param string sSeparator Output seperator (default '/')
 * 
 * Ex : UtilsDate.convertEnglishToFrench('2012/08/12', '/', '-'); => '12-08-2012'
 * 
 * @return void
 */
UtilsDate.convertEnglishToFrench = function(sDate, sInputSeparator, sOutputSeparator){
	return UtilsDate.inverseFormat(sDate, sInputSeparator, sOutputSeparator);
}

/**
 * Create datepicker on defined elements
 * 
 * @param string sJqueryCondition Jquery selection
 * @param string date fomrat
 * @param string sMinDate Date minimum (0=> date courante)
 * 
 * @return void
 */
UtilsDate.activeDatePicker = function(sJqueryCondition, sFormat, sMinDate){
	
	if (sFormat == undefined){
		sFormat = "dd/mm/yy";
	}
	
	if (sMinDate == undefined){
		sMinDate = "";
	}
	
	$(sJqueryCondition).datepicker({
		monthNames: ["Janvier","F&eacute;vrier","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre"],
		dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
		weekHeader: "S",
		nextText: "Mois suivant",
		prevText: "Mois pr&eacute;c&eacute;dent",
		buttonImage : '/bus/public/images_server/icones/fam/calendar.png',
		showAnim: 'slideDown',
		minDate: sMinDate,
		showOn : 'both',
		buttonImageOnly : true,
		dateFormat : sFormat,
		showWeek : true
	});
}

/**
 * Return true if periode overlap
 * 
 *  @param string sStartDateA English formated start date A
 *  @param string sStopDateA  English formated start date A
 *  @param string sStartDateB English formated stop  date B
 *  @param string sStopDateB  English formated stop  date B
 *  
 *  @return boolean
 */
UtilsDate.hasOverlap = function (sStartDateA, sStopDateA, sStartDateB, sStopDateB){

	return (!
				(
					(sStartDateA < sStopDateA && sStopDateA < sStartDateB && sStartDateB < sStopDateB) ||
					(sStartDateB < sStopDateB && sStopDateB < sStartDateA && sStartDateA < sStopDateA)
				)
			);
}

/**
 * Return string formated date
 * WARNING : Need UtilsString.js !!!!
 * 
 * @param Date   oDate   Date object Ex : date()
 * @param String sFormat output format
 * 		Y => 2014   Years
 * 		y => 14		Years		@deprecated
 * 		M => 07		Month
 * 		m => 7		Month
 *  	D => 07		Day
 * 		d => 7		Day
 * 		H => 07		Hour
 * 		h => 7		Hour
 * 		I => 07		Minute
 * 		i => 7		Minute
 * 		S => 07		Second
 * 		s => 7		Second
 * 
 * @return string
 */
UtilsDate.dateToString = function (oDate, sFormat) {

	var sResult = "";
	var sChar;
	var iFormatSize = sFormat.length;
	for (var i=0;i < iFormatSize; i++) {
		sChar = sFormat.substring(i,i+1);
		if (sChar == "Y") sResult += oDate.getFullYear();
		else if (sChar == "y") sResult += oDate.getYear();		//deprecated
		else if (sChar == "M") sResult += UtilsString.completeWidthChar(oDate.getMonth() + 1, '0', 2, UtilsString.CONST_WAY_LEFT);
		else if (sChar == "m") sResult += oDate.getMonth();
		else if (sChar == "D") sResult += UtilsString.completeWidthChar(oDate.getDate(), '0', 2, UtilsString.CONST_WAY_LEFT);
		else if (sChar == "d") sResult += oDate.getDate();
		else if (sChar == "H") sResult += UtilsString.completeWidthChar(oDate.getHours(), '0', 2, UtilsString.CONST_WAY_LEFT);
		else if (sChar == "h") sResult += oDate.getHours();
		else if (sChar == "I") sResult += UtilsString.completeWidthChar(oDate.getMinutes(), '0', 2, UtilsString.CONST_WAY_LEFT);
		else if (sChar == "i") sResult += oDate.getMinutes();
		else if (sChar == "S") sResult += UtilsString.completeWidthChar(oDate.getSeconds(), '0', 2, UtilsString.CONST_WAY_LEFT);
		else if (sChar == "s") sResult += oDate.getSeconds();
		else sResult += sChar;
	}
	return sResult;
}

/**
 * Test if format looks like 'YYYY-MM-DD'
 * 
 * @param string sDate      Date to test
 * @param string sSeparator Seperator (default '/')
 * 
 * @return void
 */
UtilsDate.isEnglishDate = function(sDate, sSeparator){
	if (sDate == undefined){
		return false;
	}
	
	if (sSeparator == undefined){
		sSeparator = "/";
	}
	
	var aDateItems = sDate.split(sSeparator);
	if (aDateItems.length != 3) {
		return false;
	}
	
	return (
			(4 == aDateItems[0].length) &&
			(2 == aDateItems[1].length) &&
			(2 == aDateItems[2].length)
		);
}

/**
 * Test if format looks like 'DD-MM-YYYY'
 * 
 * @param string sDate      Date to test
 * @param string sSeparator Seperator (default '/')
 * 
 * @return void
 */
UtilsDate.isFrenchDate = function(sDate, sSeparator){
	return UtilsDate.isEnglishDate(ShaUtilsDate.inverseFormat(sDate, sSeparator, sSeparator), sSeparator);
}

