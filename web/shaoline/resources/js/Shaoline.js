var DISPLAY_TYPE_TEXT = 0;
var DISPLAY_TYPE_LINK = 1;
var DISPLAY_TYPE_RTF = 2;
var DISPLAY_TYPE_PICTURE = 3;
var DISPLAY_TYPE_LINKEDPICTURE = 4;


/**
 * Class Shaoline
 * This class define structure SQL ShaBddField
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duhot@free.fr
 * @author     Bastien DUHOT <Contact bastien.duhot@free.fr>
 * @version    1.0
 */
function Shaoline(){}

Shaoline.currentEditedElement = null;

Shaoline.fieldCheckerOkPicto = '<img class="zone_checker_picto" alt="error" src="plugins/MonRef/resources/img/checkTrue.png">';
Shaoline.fieldCheckerKoPicto = '<img class="zone_checker_picto" alt="error" src="plugins/MonRef/resources/img/checkFalse.png">';

Shaoline.setRsaPublicKey = function(key){
	this.rsaPublicKey = key;
};

Shaoline.rsaCrypt = function(msg){
    var key = RSA.getPublicKey(this.rsaPublicKey);
    result = RSA.encrypt(msg, key);
    return result;
};

/**
 * Scan all dom elements with GcId attribute and send gc ids to server
 * to update ttl in database
 */
Shaoline.checkGc = function(){

    var managedItems = $j("[gcid]");
    var managedItemList = '0;';
    var aValue = "";
    for (var i=0;i<managedItems.length;i++){
        aValue = managedItems[i].attributes.gcid.value.split(";");
        for (var j=0;j<aValue.length; j++){
            managedItemList += aValue[j]+';';
        }

    }
    UtilsJquery.jQueryAjax(
        'cmsAsynchrone.php',
        'Shaoline.error(j)', //Test
        {
            command : 'checkGc',
            managedItemList : managedItemList
        },
        false
    );
};

/**
 * Ask to server for reporting datas
 */
Shaoline.getConstantReporting = function() {
    UtilsJquery.jQueryAjax(
        'cmsAsynchrone.php',
        "Shaoline.getConstantReportingOk(j)",
        {
            command : 'getConstantReporting'
        },
        false
    );
};

/**
 * Display reporting datas into 'cms_constant_reporting' dev
 *
 * @param j
 */
Shaoline.getConstantReportingOk = function(j) {
    j = j.replace("\r\n", "");
    var div = document.getElementById('cms_constant_reporting');
    if (div != undefined) {
        if (j == "") {
            div.style.display = 'none';
            div.style.padding = '0';
            setTimeout(function() {Shaoline.getConstantReporting();}, 10000);
        } else {
            div.style.display = 'block';
            div.style.padding = '5px';
            setTimeout(function() {Shaoline.getConstantReporting();}, 2000);
        }
        div.innerHTML = j;
    }
};

/**
 * Display eror popin
 */
Shaoline.error = function(j){
    j = j.replace("\r\n", "");
    if (j != ""){
        UtilsWindow.drawPopin(300, 'red', j, "Erreur", "", "", "cms_editor", true, true);
    }
};

//###################
// CMS LIST AND ACTION MANAGER
//###################


Shaoline.doExtendedAction = function(gcId, additionalParameters, activeMask) {
	if (activeMask == undefined){
		activeMask = true;
	}

	var parameters = JSON.parse(UtilsString.decodeBase64(additionalParameters));
	var param = new Object;
	var value, parameter;
	for (var i=0; i < parameters.length; i++) {
		parameter = parameters[0];
		var obj =  $j('#' + parameter.inputName);
		if (obj.length > 0){
			value = obj[0].value;
			eval("param." + parameter.paramName + " = '" + value + "';");
		}
	}
	Shaoline.doAction(gcId, activeMask, param);
	
};

/**
 * Call doAction function
 */
Shaoline.doAction = function(gcId, activeMask, additionalParameters) {
    if (activeMask == undefined){
        activeMask = true;
    }
    if (activeMask){
        $j("#cms_waiting_masque").css("display", "block");
    }

    var parameters =   {
        command : 'doAction',
        config : gcId
    }

    if (additionalParameters != undefined){
        for (p in additionalParameters){
            parameters[p] = additionalParameters[p];
        }
    }

    UtilsJquery.jQueryAjax(
        "cmsAsynchrone.php",
        "Shaoline.doActionResult(j)",
        parameters
        , true);
};

Shaoline.doActionResult = function(j) {

    //Hide mask
    $j("#cms_waiting_masque").css("display", "none");

    //Clean result
    var asynchroneResult = j;
    asynchroneResult = asynchroneResult.replace("\r", "");
    asynchroneResult = asynchroneResult.replace("\n", "");
    asynchroneResult = asynchroneResult.replace("\t", "");

    try {
        var result = JSON.parse(asynchroneResult);

        // REDIRECTION //
        if (result.renderer == "NOTHING" ){
            //DO NOTHING
        } else if (result.renderer == "REDIRECT" ){

            if (result.content == "SHA_CURRENT") {
                window.location.href = window.location.href
            } else {
                window.location.href = result.content;
            }
            return;

        }

        // DIV //
        else if (result.renderer == "DIV"){

            var domZone = document.getElementById(result.domId);
            if (domZone){
                domZone.innerHTML = result.content;
            }

        }

        // POPIN //
        else if (result.renderer == "POPIN"){

            var popinId = Shaoline.drawResultPopin(result);

        }

        // FULL //
        else if (result.renderer == "FULL"){

            document.write(result.content);

        }

        // ERROR //
        else {
            UtilsWindow.drawPopin(0, 'red', result.content, "Error", "", "", "cms-editor-error", true, true);
            return;
        }

        // DIRECT JS ACTIONS //
        if (result.jsActions != undefined){
            var resizeZoneId = (result.domId != undefined) ? result.domId : "";
            for ( var i=0; i < result.jsActions.length; i++) {
                try {
                    eval(result.jsActions[i]);
                    if (result.renderer == "POPIN"){
                        UtilsWindow.windowDeleteHeightIfNotStillNecessary(resizeZoneId);
                    }
                } catch (e){
                    UtilsWindow.drawPopin(0, 'red', "Error when execute : " + result.jsActions[i] + " : " + e.message, "Error", "", "", "cms-editor-error", true, true);
                    return;
                }
            }
        }

        // OTHER GC ACTIONS //
        if (result.phpActions != undefined){
            for ( var i=0; i < result.phpActions.length; i++) {
                Shaoline.doAction(result.phpActions[i]);
            }
        }


    } catch (e){
        UtilsWindow.drawPopin(0, 'red', asynchroneResult, "Error", "", "", "cms-editor-error", true, true);
        return;
    }

};

Shaoline.drawResultPopin = function(result) {

    return UtilsWindow.drawPopin(
        result.popin.width, //width
        result.popin.color, //color
        result.content,     //content
        result.popin.title, //title
        undefined,
        undefined,
        result.domId,       //id
        result.popin.dragdrop,    //activeDragDrop
        result.popin.activeMask,   //activeMask
        result.popin.style
    );
	
};

//###################
// FORMULAIRE
//###################

/**
 * Get input value depending input type
 *
 * @param obj Dom object
 *
 * @returns string
 */
Shaoline.getInputValue = function(obj) {
    if (obj.type == "checkbox" || obj.type == "radio") {
        return obj.checked;
    } else if (obj.type == "textarea") {
        return obj.value;
    } else if (obj.type == "select-multiple" || obj.type == "select"){
    	var result = Array();
    	for (var i = 0; i < obj.selectedOptions.length; i++) {
    		result.push(obj.selectedOptions[i].value)
    	}
    	return result;
    } else if (obj.type == "file") {
    	var fileInput = document.getElementById(obj.id);
    	if (fileInput == undefined) { return ""; }
    	files = fileInput.files;
    	return (files.length > 0) ? files[0] : "";
    } else {
        return obj.value;
    }
};


/**
 * Get all form values and send them to server for validation
 *
 * @param gcId Garbage id
 * @param domId ShaForm dom id
 */
Shaoline.submitForm = function(gcId, domId) {

    $j("#cms_waiting_masque").css("display", "block");

    var index = 0;
    var values = new Array();
    var item = document.getElementsByName(domId + "_" + index);
    var qty;
    
    var data = new FormData();
    while (item.length > 0) {
        values[index] = new Array();
        qty = item.length
        for ( var i = 0; i < qty; i++) {
        	if (item[i].type == "file"){
        		data.append("FILE_"+index, Shaoline.getInputValue(item[i]));
        	} else {
        		if (item[i].hasAttribute("shaRsa")){
        			values[index][i] = Shaoline.rsaCrypt(Shaoline.getInputValue(item[i]));
        		} else {
        			values[index][i] = Shaoline.getInputValue(item[i]);
        		}
        	}
        }
        index++;
        item = document.getElementsByName(domId + "_" + index);
    }

    
    data.append("command", 'submitForm');
    data.append("config", gcId);
    data.append("values", JSON.stringify(values));
    
    UtilsJquery.jQueryFormDataAjax(
        "cmsAsynchrone.php",
        "Shaoline.doActionResult(j)",
        data,
        true
    );

};

/**
 * Check form field egalite and update form_checker zone
 * @param fieldA
 * @param fieldB
 * @param jsOk
 * @param jsKo
 */
Shaoline.checkFields = function(fieldName, encodedParam) { 
	
	encodedParam = UtilsString.decodeBase64(encodedParam);
	var parameters = JSON.parse(encodedParam, true);
	
	var field = $j("#" + fieldName);
	var checker = $j("#form_checker_" + fieldName);
	
	for (var i=0; i < parameters.length; i++){
		
		if (field.length > 0 && checker.length > 0){
			
			param = parameters[i];
			field = field[0];
			//checker = checker[0];

			if (param.type == "minSize"){
				if (field.value.length < param.value){
					checker.html(Shaoline.fieldCheckerKoPicto + " : " + param.msg);
					return;
				} else {
					checker.html("");
				}
			}
			if (param.type == "maxSize"){
				if (field.value.length > param.value){
					checker.html(Shaoline.fieldCheckerKoPicto + " : " + param.msg);
					return;
				} else {
					checker.html("");
				}
			}
			if (param.type == "pattern"){
				if (!field.value.match(param.value)){
					checker.html(Shaoline.fieldCheckerKoPicto + " : " + param.msg);
					return;
				} else {
					checker.html("");
				}
			}
			if (param.type == "bis"){
				var valueB = $j("#" + param.value);
				if (valueB.length > 0) {
					if (field.value == valueB[0].value){
						checker.html(Shaoline.fieldCheckerOkPicto);
					} else {
						checker.html(Shaoline.fieldCheckerKoPicto + " : " + param.msg);
					}
					return;
				}
			}
			if (param.type == "needLower"){
				//TODO
			}
			if (param.type == "needUpper"){
				//TODO
			}
			if (param.type == "needNumber"){
				//TODO
			}
		}
	}
		
};


/**
 * Get field and do in /no int formField checker
 * 
 * @param gcId
 * @param field
 */
Shaoline.formFieldChecker = function (actionGcId, field) {
	var postalCodeZone = document.getElementById(field);
	if (postalCodeZone == undefined) {
		return;
	}
	Shaoline.doAction(actionGcId, true, {
		value : postalCodeZone.value
	});
};


/**
 * Global tab manager
 */
Shaoline.currentTab = 0;
Shaoline.selectTab = function (newTab){
    $j("#onglet" + newTab).addClass("selected");
    $j("#onglet" + Shaoline.currentTab).removeClass("selected");
    Shaoline.currentTab = newTab;
};

/**
 * Update textbox zone in file uploader
 */
Shaoline.updateFileField = function(idFile, idText) {
	var e = $j("#"+idFile);
    var t = $j("#"+idText);
    if (e.length > 0 && t.length > 0) {
        t[0].value = e[0].value;
    }
};


/**
 * Display edit picto
 */
Shaoline.manageEditPicto = function(event, gcId){
	Shaoline.editPicto = Shaoline.editPicto || $j('#cms_edit_picto');
	var target = event.currentTarget;
	var pos = UtilsWindow.getPositionObj(target);
	Shaoline.editPicto.unbind('click');
	var param = {gcId: gcId};
        Shaoline.currentEditedElement = $j(event.currentTarget);
	Shaoline.editPicto.bind('click', 
            function (){ 
                Shaoline.doAction(param.gcId); 
            }
	);
	Shaoline.editPicto.css('left', '' + pos.left + 'px');
	Shaoline.editPicto.css('top', '' + pos.top + 'px');
	Shaoline.editPicto.show();
};