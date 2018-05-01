
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


/**
 * SlideUp or Down start menu depending of menu slide state
 */
function cms_changeStartMenuState() {
	var div = document.getElementById('cms_admin_taskbar_support');
	if (div == undefined)
		return;
	if (div.style.display == 'none') {
		div.style.display = 'block';
	} else {
		div.style.display = 'none';
	}
}



//######################
//MENUS TYPE 1 MANAGER
//######################

var MENUS_TYPE_1 = new Array();

function createMenuType1(prefixId, hDirection, vDirection, first, hUsePanel, vUsePanel, hDecalage, vDecalage, recurciveIndex){
	if (MENUS_TYPE_1[prefixId]==undefined){
		MENUS_TYPE_1[prefixId] = new MenuType1(prefixId, hDirection, vDirection, first, hUsePanel, vUsePanel, hDecalage, vDecalage, recurciveIndex);
	}
}

function closeMenuType1(prefixId){
	if (MENUS_TYPE_1[prefixId]!=undefined){
		MENUS_TYPE_1[prefixId].close();
	}
}

function cms_displayAndClearMenuPanel(prefixId, id){
	if (MENUS_TYPE_1[prefixId]!=undefined){
		MENUS_TYPE_1[prefixId].displayAndClearMenuPanel(prefixId, id);
	}
}

function cms_hideMenuType1(prefixId){
	 $j("#"+prefixId).mouseleave(function (me, prefixId) {
		 closeMenuType1(this.id);
     });
}

function MenuType1(prefixId, hDirection, vDirection, first, hUsePanel, vUsePanel, hDecalage, vDecalage, recurciveIndex) {
	this.prefixId         = prefixId;
	this.hDirection       = hDirection;
	this.vDirection       = vDirection;
	this.first            = first;
	this.hUsePanel        = hUsePanel;
	this.vUsePanel        = vUsePanel;
	this.hDecalage        = hDecalage; 
	this.vDecalage        = vDecalage; 
	this.recurciveIndex   = recurciveIndex;
	this.lastOpenedPanel  = "";
	return this;
 }

 /**
  * Return true if menu type content A is a parent of  menu type content B
  */
 MenuType1.prototype.isMenuParentPanel = function(idParent, idchildren) {
 	if (idParent == undefined || idchildren == undefined)
 		return false;
 	return (idchildren.substr(0, idParent.length) == idParent);
 };

 MenuType1.prototype.isMenuBrotherPanel = function(idA, idB) {
 	var parentA = this.getMenuParentPanel(idA);
 	var parentB = this.getMenuParentPanel(idB);
 	if (parentA == undefined || parentB == undefined)
 		return false;

 	return (parentA == parentB);
 };

 MenuType1.prototype.getMenuParentPanel = function(id) {
 	if (id == undefined) {
 		return undefined;
 	}
 	var element = id.split("_");
 	if (element.length <= 0) {
 		return undefined;
 	}
 	var result = "";
 	for ( var i = 0; i < element.length - 1; i++) {
 		result += element[i] + "_";
 	}
 	result = result.substr(0, result.length - 1);
 	return result;
 };

 MenuType1.prototype.getMenuRootPanel = function(id) {
 	var element = id.split("_");
 	var index = 0;
 	var result = "";
 	while (index<element.length && (element[index] % 1 !== 0)){
 		if (result!="")result+="_";
 		result+=element[index];
 		index++;
 	}
 	if (index<element.length && (element[index] % 1 === 0)){
 		if (result!="")result+="_";
 		result+=element[index];
 	}
 	return result;
 };

 MenuType1.prototype.close = function() {
 	this.hideMenuChildPanel(this.prefixId, '0');
 	this.lastOpenedPanel = "0";
 };

 MenuType1.prototype.hideMenuChildPanel = function(prefixId, id) {
 	var index = 0;
 	var child = document.getElementById(prefixId + "_" + id + "_" + index);
 	while (child != undefined) {
 		
 		if (this.lastOpenedPanel == id + "_" + index){
 			this.lastOpenedPanel = id;
 		}
 		
 		child.style.display = 'none';
 		this.hideMenuChildPanel(prefixId, id + "_" + index);
 		index++;
 		child = document.getElementById(prefixId + "_" + id + "_" + index);
 	}
 };

 MenuType1.prototype.hideMenuPanelAndChild = function(prefixId, id) {
 	this.hideMenuChildPanel(prefixId, id);
 	var element = document.getElementById(prefixId + "_" + id);
 	if (element != undefined) {
 		element.style.display = 'none';
 	}
 };

 MenuType1.prototype.displayAndClearMenuPanel = function(prefixId, id) {

 	var element;

 	if (this.lastOpenedPanel == undefined) {
 		this.lastOpenedPanel = "";
 		this.displayMenuPanel(prefixId, id);
 		return;
 	}

 	//Il existe une div déjà ouverte
 	if (this.lastOpenedPanel != id) {

 		var isChildren = this.isMenuParentPanel(this.lastOpenedPanel, id);
 		var isParent = this.isMenuParentPanel(id,this.lastOpenedPanel);
 		var isBrother = this.isMenuBrotherPanel(id,this.lastOpenedPanel);

 		//If it is child
 		if (isChildren) {
 			//Hide nothing
 		}
 		//If it is parent
 		if (isParent) {
 			//Hide all child of content to display
 			this.hideMenuChildPanel(prefixId, id);
 		}

 		//If it is parent
 		if (isBrother) {
 			//Hide onlyy the last create panel
 			element = document.getElementById(prefixId + "_" + this.lastOpenedPanel);
 			if (element != undefined) {
 				element.style.display = 'none';
 			}
 		}

 		//If nor child nor parent
 		if (!isChildren && !isParent && !isBrother) {
 			//Hide all preview branch
 			var parent = this.getMenuRootPanel(this.lastOpenedPanel);
 			this.hideMenuChildPanel(prefixId, parent);
 			element = document.getElementById(prefixId + "_" + parent);
 		}

 		this.displayMenuPanel(prefixId, id);
 	}
 };

 MenuType1.prototype.isDisplay = function(id){
	 var panel = $j('#' + this.prefixId + '_' + id);
	 return panel.css('display') == 'block';
 };
 
 MenuType1.prototype.displayMenuPanel = function(prefixId, id) {
	
	//Check if all parents are displayed
	var parentId = this.getMenuParentPanel(id);
	if (parentId!="" && !this.isDisplay(parentId)){
		this.displayMenuPanel(this.prefixId, parentId);
	}
 	//Display the new panel
	if (!this.isDisplay(id)){
		this.repositionPanel(this.prefixId, id);
	 	var panel = $j('#' + this.prefixId + '_' + id);
	 	if (panel.length > 0) {
	 		panel.css('display', 'block');
	 	}
	 	
		this.lastOpenedPanel = id;
	}
 };
 
 
MenuType1.prototype.repositionPanel = function(prefixId, id){

	if (this.recurciveIndex==undefined)this.recurciveIndex=1;
	if (this.vDecalage==undefined)this.vDecalage=0;
	if (this.hDecalage==undefined)this.hDecalage=0;
	var index = 0;
	var element = $j("#" + prefixId + "_" + id);
	var top, left;
	var parent, parentLeft, parentTop, parentW, parentH;
	var parentPanel, parentPanelLeft, parentPanelTop, parentPanelW, parentPanelH;
	var parentId = this.getMenuParentPanel(id);
	
	var elementW = UtilsForm.getJqueryRealWidth(element);
	var elementH = UtilsForm.getJqueryRealHeight(element);
	
	parentPanel= $j("#" + prefixId + "_" + parentId);
	/*parentPanelLeft = getOffsetLeftDiff(parentPanel);
	parentPanelTop = getOffsetTopDiff(parentPanel);*/
	parentPanelLeft = parentPanel.position().left;
	parentPanelTop = parentPanel.position().top;
	parentPanelW = UtilsForm.getJqueryRealWidth(parentPanel);
	parentPanelH = UtilsForm.getJqueryRealHeight(parentPanel);
	
	parent = $j("#" + prefixId + "_" + id + "_item");
	/*parentLeft = getOffsetLeftDiff(parent);
	parentTop = getOffsetTopDiff(parent);*/
	parentLeft = parent.position().left + parentPanelLeft + parentPanelW;
	parentTop = parent.position().top + parentPanelTop;
	parentW = UtilsForm.getJqueryRealWidth(parent);
	parentH = UtilsForm.getJqueryRealHeight(parent);
	
	var elementHeight = UtilsForm.getJqueryRealHeight(element);
	var elementWidth = UtilsForm.getJqueryRealWidth(element);
	
	if (this.vUsePanel){
		if (this.vDirection=="top") top = parentPanelTop -  elementHeight + this.vDecalage* this.recurciveIndex;
		if (this.vDirection=="bottom") top = parentPanelTop + parentPanelH +  this.vDecalage* this.recurciveIndex;
		if (this.vDirection=="none") top = parentPanelTop - parentPanelH + this.vDecalage;
	} else {
		if (this.vDirection=="top") top = parentTop - elementHeight +  this.vDecalage* this.recurciveIndex - elementH / 2;
		if (this.vDirection=="bottom") top = parentTop + parentH +  this.vDecalage* this.recurciveIndex - elementH / 2;
		if (this.vDirection=="none") top = parentTop +  this.vDecalage - elementH / 2;
	}
	/*if (top < 0){
		top = 0;
	}
	var iWindowsHeight = window.innerHeight;
	if (parentPanelTop + top + elementH > iWindowsHeight){
		top = iWindowsHeight - parentPanelTop - elementH;
	}*/
	
	element.css({ top: top+'px' });
	
	if(this.hUsePanel){
		if (this.hDirection=="left") left = parentPanelLeft + parentPanelW +  this.hDecalage* this.recurciveIndex;
		if (this.hDirection=="right") left = parentPanelLeft - elementWidth +  this.hDecalage* this.recurciveIndex;
		if (this.hDirection=="none") left = parentPanelLeft +  this.hDecalage;
	} else {
		if (this.hDirection=="left") left = parentLeft + parentW + this.hDecalage* this.recurciveIndex;
		if (this.hDirection=="right") left = parentLeft - elementWidth +  this.hDecalage* this.recurciveIndex;
		if (this.hDirection=="none") left = parentLeft +  this.hDecalage;
	}
	
	element.css({ left: left+'px' });
 
};





/*
function cms_repositionMenuPanel(prefixId, hDirection, vDirection, first, hUsePanel, vUsePanel, hDecalage, vDecalage, recurciveIndex){
	if (recurciveIndex==undefined)recurciveIndex=1;
	if (vDecalage==undefined)vDecalage=0;
	if (hDecalage==undefined)hDecalage=0;
	var index = 0;
	var element = $j("#" + prefixId + "_" + index);
	var top, left;
	var parent, parentLeft, parentTop, parentW, parentH;
	var parentPanel, parentPanelLeft, parentPanelTop, parentPanelW, parentPanelH;
	var root = $j("#"+prefixId);
	while (element!=undefined && element.length>0){

		if (!first){

			parent = $j("#" + prefixId + "_" + index + "_item");
			parentLeft = getOffsetLeftDiff(parent);
			parentTop = getOffsetTopDiff(parent);
			parentW = getJqueryRealWidth(parent);
			parentH = getJqueryRealHeight(parent);
			alert("l = "+parentLeft+", t = "+parentTop);
			
			parentPanel= $j("#" + prefixId);
			parentPanelLeft = getOffsetLeftDiff(parentPanel);
			parentPanelTop = getOffsetTopDiff(parentPanel);
			parentPanelW = getJqueryRealWidth(parentPanel);
			parentPanelH = getJqueryRealHeight(parentPanel);
			
			if (vUsePanel){
				if (vDirection=="top") top = parentPanelTop - getJqueryRealHeight(element) + vDecalage*recurciveIndex;
				if (vDirection=="bottom") top = parentPanelTop + parentPanelH + vDecalage*recurciveIndex;
				if (vDirection=="none") top = parentPanelTop + vDecalage;
			} else {
				if (vDirection=="top") top = parentTop - getJqueryRealHeight(element) + vDecalage*recurciveIndex;
				if (vDirection=="bottom") top = parentTop + parentH + vDecalage*recurciveIndex;
				if (vDirection=="none") top = parentTop + vDecalage;
			}
		
			element.css({ top: top+'px' });
			
			if(hUsePanel){
				if (hDirection=="left") left = parentPanelLeft + parentPanelW + hDecalage*recurciveIndex;
				if (hDirection=="right") left = parentPanelLeft - getJqueryRealWidth(element) + hDecalage*recurciveIndex;
				if (hDirection=="none") left = parentPanelLeft + hDecalage;
			} else {
				if (hDirection=="left") left = parentLeft + parentW + hDecalage*recurciveIndex;
				if (hDirection=="right") left = parentLeft - getJqueryRealWidth(element) + hDecalage*recurciveIndex;
				if (hDirection=="none") left = parentLeft + hDecalage;
			}
			
			element.css({ left: left+'px' });

		}
		cms_repositionMenuPanel(prefixId + "_" + index, hDirection, vDirection, false, hUsePanel, vUsePanel, hDecalage, vDecalage, index+1);
		index++;
		element = $j("#" + prefixId + "_" + index);
	}
}*/


//######################
//MENUS TYPE 2 MANAGER
//######################

LAST_OPENED_V2_MENU_CONTENT = new Array();

function cms_hideMenuType2(iId, iCurrentIndex){
	
	 $j("#shaoline_menu_type_2_panel_level_1_"+iId+"_"+iCurrentIndex).mouseleave(function () {
	 	var previous = document.getElementById("shaoline_menu_type_2_panel_level_1_"+iId+"_"+iCurrentIndex);
		var previousTitle = $j("#shaoline_menu_type_2_item_level_0_"+iId+"_"+iCurrentIndex);
		if (previous!=undefined){
			previous.style.display = "none";
			previousTitle.removeClass("selected");
		}
		LAST_OPENED_V2_MENU_CONTENT[iId] = -1;
     });
	
}

function cms_displayAndClearMenuType2(iId, iCurrentIndex){
	
	if (LAST_OPENED_V2_MENU_CONTENT[iId]==iCurrentIndex){
		return;
	}
	
	//Hide previous
	if (LAST_OPENED_V2_MENU_CONTENT[iId]>-1){
		var previous = document.getElementById("shaoline_menu_type_2_panel_level_1_"+iId+"_"+LAST_OPENED_V2_MENU_CONTENT[iId]);
		var previousTitle = $j("#shaoline_menu_type_2_item_level_0_"+iId+"_"+LAST_OPENED_V2_MENU_CONTENT[iId]);
		if (previous!=undefined){
			previous.style.display = "none";
			previousTitle.removeClass("selected");
		}
		
		LAST_OPENED_V2_MENU_CONTENT[iId] = iCurrentIndex;
	}
	//Display new
	var parent = $j("#shaoline_menu_type_2_item_level_0_"+iId+"_"+iCurrentIndex);
	var next = document.getElementById("shaoline_menu_type_2_panel_level_1_"+iId+"_"+iCurrentIndex);
	var parentTitle = $j("#shaoline_menu_type_2_item_level_0_"+iId+"_"+iCurrentIndex);
	if (parent.length>0 && next!=undefined){
		next.style.left = parent.position().left+"px";
		next.style.top = parent.outerHeight()+"px";
		next.style.display = "block";
		parentTitle.addClass("selected");
		LAST_OPENED_V2_MENU_CONTENT[iId] = iCurrentIndex;
	}

}
