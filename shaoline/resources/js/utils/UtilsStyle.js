/**
 * Class UtilsAjax (common popin object)
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
function UtilsStyle() {}

/**
 * Class UtilsStyle (common style object)
 * 
 * @category   
 * @package    
 * @subpackage 
 * @author     bduhot <bastien.duhot@free.fr>
 * @license    
 * @version    1.0.0
 * @link       no link
 *
 */
function UtilsStyle() {}

UtilsStyle._selectedRules = new Array();

/**
 * Create selected css rules on elements
 * 
 * @param sName           Rule name
 * @param sJquerySelector Jquery selector rule
 * @param sCssClassOn     css classs when active
 * @param sCssClassOff     css classs when inactive
 * 
 * @return void
 */
UtilsStyle.creatSelectedRules = function (sJquerySelector, sCssClassOn, sCssClassOff) {
	
	if (sCssClassOff == undefined){
		sCssClassOff = "";
	}
	
	UtilsStyle._selectedRules[sJquerySelector] = new Object();
	UtilsStyle._selectedRules[sJquerySelector]['cssClassOn'] = sCssClassOn;
	UtilsStyle._selectedRules[sJquerySelector]['cssClassOff'] = sCssClassOff;
	
	$j(sJquerySelector).live('click', function(e) {
		sJquerySelector = ohandleObj = e.handleObj.selector;
		$j(sJquerySelector).removeClass(ShaUtilsStyle._selectedRules[sJquerySelector]['cssClassOn']);
		$j(sJquerySelector).addClass(ShaUtilsStyle._selectedRules[sJquerySelector]['cssClassOff']);
		$j(this).removeClass(ShaUtilsStyle._selectedRules[sJquerySelector]['cssClassOff']);
		$j(this).addClass(ShaUtilsStyle._selectedRules[sJquerySelector]['cssClassOn']);
	});
	
}




