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
function UtilsForm() {}

UtilsForm.collapseMultipleList = function(id){
    var isCollapse = document.getElementById('is-collapse-'+id);
    if (isCollapse==undefined)return;
    var list = document.getElementById('ul-'+id);
    if (list==undefined)return;
    list.style.zIndex = list.parentNode.style.zIndex + parseInt(1);
    if (isCollapse.value=='0'){
        $j('#ul-'+id).slideDown("fast");
        isCollapse.value = '1';
    }else{
        $j('#ul-'+id).slideUp("fast");
        isCollapse.value = '0';
    }
}

UtilsForm.getRealWidth = function(dom){
    return UtilsForm.getJqueryRealWidth($j(dom))
};

UtilsForm.getRealHeight = function(dom){
    return UtilsForm.getJqueryRealHeight($j(dom))
};

UtilsForm.getJqueryRealWidth = function(dom){
    var widthDom = dom.outerWidth(true);

    var html = $j('<span style="postion:absolute;width:auto;left:-9999px">' + (dom.html()) + '</span>');
    if (!dom) {
        html.css("font-family", dom.css("font-family"));
        html.css("font-size", dom.css("font-size"));
    }
    $j('body').append(html);
    var width = html.outerWidth(true);
    html.remove();
    return Math.max(width,widthDom);
};



UtilsForm.getJqueryRealHeight = function(dom){
    var heightDom = dom.outerHeight(true);
    var html = $j('<span style="postion:absolute;width:auto;left:-9999px">' + (dom.html()) + '</span>');
    if (!dom) {
        html.css("font-family", dom.css("font-family"));
        html.css("font-size", dom.css("font-size"));
    }
    $j('body').append(html);
    var height = html.outerHeight(true);
    html.remove();
    return Math.max(height,heightDom);
};


UtilsForm.getInnerWidth = function(dom){
    return UtilsForm.getJqueryInnerWidth($j(dom))
};

UtilsForm.getInnerHeight = function(dom){
    return UtilsForm.getJqueryInnerHeight($j(dom))
};

UtilsForm.getJqueryInnerWidth = function(dom){

    var html = $j('<span style="postion:absolute;width:auto;left:-9999px">' + (dom.html()) + '</span>');
    if (!dom) {
        html.css("font-family", dom.css("font-family"));
        html.css("font-size", dom.css("font-size"));
    }
    $j('body').append(html);
    var width = html.outerWidth(true);
    html.remove();
    return width;
};

UtilsForm.getJqueryInnerHeight = function(dom){
    var html = $j('<span style="postion:absolute;width:auto;left:-9999px">' + (dom.html()) + '</span>');
    if (!dom) {
        html.css("font-family", dom.css("font-family"));
        html.css("font-size", dom.css("font-size"));
    }
    $j('body').append(html);
    var height = html.outerHeight(true);
    html.remove();
    return height;
};

UtilsForm.getOffsetTopDiff = function(child){
    var top = child.position().top;
    var cssTop = parseFloat(forceIntValue(child.css("top")));
    var marginTop = parseFloat(forceIntValue(child.css("margin-top")));
    return top+cssTop+marginTop;
}

UtilsForm.getOffsetLeftDiff = function(child){
    var left = child.position().left;
    var cssLeft = parseFloat(forceIntValue(child.css("left")));
    var marginLeft = parseFloat(forceIntValue(child.css("margin-right")));
    return left+cssLeft+marginLeft;
}

//###################
//TAB MANAGER
//###################
UtilsForm.cms_tab = function(iTabId, iTabToSelect){
    //Hide all elements
    $j(".cms_tab_header_" + iTabId).removeClass("tab_selected");
    $j(".cms_tab_body_" + iTabId).removeClass("tab_selected");
    //Select header tab
    $j("#cms_tab_header_" + iTabId + "_" + iTabToSelect).addClass("tab_selected");
    $j("#cms_tab_body_" + iTabId + "_" + iTabToSelect).addClass("tab_selected");
}

/**
 * Display div if hidden and hide div if visible
 */
UtilsForm.showHide = function(domId) {
    var zone = document.getElementById(domId);
    if (zone != undefined){
        if (zone.style.display == 'none')
            zone.style.display = 'block';
        else
            zone.style.display = 'none';
    }
}

/**
 * Fill option list width values
 *
 * @param domId
 * @param datas
 */
UtilsForm.fillOptionList = function(domId, datas, decode) {
	
	if (decode == undefined){
		decode = 0;
	}
	
	if (decode == 1){
		datas = JSON.parse(UtilsString.decodeBase64(datas));
	}
	
    var optionList = document.getElementById(domId);
    /** @type HTMLSelectElement optionList */
    if (optionList == undefined) {
        return;
    }
    if (optionList.options != undefined){
		while (optionList.options.length > 0) {
		    optionList.remove(0);
		}
    }
  
    /** @type string index**/
    for (var index in datas) {
        var option = document.createElement("option");
        /** @type HTMLOptionElement option */
        option.value = datas[index][0];
        option.text = datas[index][1];
        optionList.add(option);
    }
}

/**
 * Return true if option list exist and have selected value
 *
 * @param domId
 */
UtilsForm.getOptionListValue = function(domId){
    var optionList = document.getElementById(domId);
    /** @type HTMLSelectElement optionList */
    if (optionList == undefined || optionList.selectedIndex == undefined){
        return undefined;
    } else {
        return optionList.options[optionList.selectedIndex].value;
    }
}

/**
 * Get two field of JSON objects list and return associative array
 *
 * @param objects
 * @param libPattern Ex: ...%field%...%field%...
 *
 * @return Array
 */
UtilsForm.getArrayFromObject = function(objects, fieldForKey, libPattern){
    var result = [];
    for (var index in objects){
        var entry = [];
        var key, value;
        var object = objects[index];

        eval("key = objects[index]." + fieldForKey + ";");
        
        value = "";
        fields = libPattern.split('%');
        for (fieldIndex in fields){
        	if (object.hasOwnProperty(fields[fieldIndex])){
        		eval("value += object." + fields[fieldIndex] + ";");
        	} else {
        		value += fields[fieldIndex];
        	}
        }
        
        entry.push(key);
        entry.push(value);

        result.push(entry);
    }
    return result;
}

/*
 function createMultipleSelectionList($id,$titre,$mappingKeyValues,$cssClass,$width,$type){
 $html = "<div class='multipleSelection".$cssClass."' id='".$id."' style='width:".$width."px'  >";
 $html .= "<input type='hidden' id='is-collapse-".$id."' value='0'>";
 $html .= "<h2 onclick=\"collapseMultipleList('".$id."')\">".$titre."</h2><ul id='ul-".$id."' style='width:".$width."px;display:none'>";
 $cpt=0;
 foreach ($mappingKeyValues as $mappingKeyValue){
 $html.= "<li>";
 if ($type=="checkbox"){
 $html .= "<input id='".$mappingKeyValue['id']."' type='checkbox' value='".$mappingKeyValue['key']."' >";
 }else if ($type=="radiobox"){
 $checked = ($cpt==0)? "checked=checked":"";
 $html .= "<input id='".$mappingKeyValue['id']."' ".$checked." type='radio' name='".$id."' value='".$mappingKeyValue['key']."' >";
 }
 $html.= "<span>".$mappingKeyValue['value']."</span>
 </li>";
 $cpt++;
 }

 $html .= "</ul></div>";
 return $html;
 }*/