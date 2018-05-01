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
function UtilsJquery() {}

/**
 * Create jCarouselLite
 */
UtilsJquery.jCarouselLite = function(id,timeout,speed,visible,vertical){
    $j(id).jCarouselLite({
        vertical: vertical,
        auto: timeout,
        speed: speed,
        visible: visible,
        scroll: -1
    });
}
/**
 * Create jCycle
 */
UtilsJquery.jCycle = function(id,timeout,speed,scroll,nbslide){
    $j(id).cycle({
        fx: scroll,
        speed: speed,
        timeout: timeout,
        nbslide : nbslide		
    });
}

/**
 * Display waiting mask
 */
UtilsJquery.waiting = function(){
    $j("#waitingMasque").css({
        "z-index": "60000"
    });
    $j("#waitingMasque").css({
        "display": "block"
    });
    document.body.style.cursor = 'wait';
}

/**
 * Hide waiting mask
 */
UtilsJquery.unwaiting = function(){
    document.body.style.cursor = 'default';
    $j("#waitingMasque").css({
        "display": "none"
    });
}

/**
 * Asynchrone JS call
 * param: page string PHP page to call
 * param: fonction string Function to call is success
 * param: param array List of param
 * param: activeMask boolean If true, display mask during calling
 */
UtilsJquery.jQueryAjax = function(page, fonction, param, activeMask){
    if (activeMask==undefined)
        activeMask=true;
    if (activeMask)
        UtilsJquery.waiting();
    $j.ajax({
        url: page,
        data: param,
        dataType: 'text',
        type: "post",
        async: true,
        cache: false,
        success: function(j) {
            j = j.replace("\r\n","");
            UtilsJquery.unwaiting();
            eval(fonction);
        }
    });
}


/**
 * Asynchrone JS call
 * param: page string PHP page to call
 * param: fonction string Function to call is success
 * param: param array List of param
 * param: activeMask boolean If true, display mask during calling
 */
UtilsJquery.jQueryFormDataAjax = function(page, fonction, param, activeMask){
    if (activeMask==undefined)
        activeMask=true;
    if (activeMask)
        UtilsJquery.waiting();
    $j.ajax({
 	   type: 'POST',   
	   url: page,   
	   data: param,
	   cache: false,
	   async: false,
	   dataType: 'text',
       processData: false,
       contentType: false,
       success: function(j) {
            j = j.replace("\r\n","");
            UtilsJquery.unwaiting();
            eval(fonction);
        }
    });
}


/**
 * Return random unic id
 */
UtilsJquery.getUnicId = function() {
    return "id" + Math.random().toString(16).slice(2);
}

/**
 * Asynchrone JS call for Multipart content
 * param: page string PHP page to call
 * param: fonction string Function to call is success
 * param: param array List of param
 * param: activeMask boolean If true, display mask during calling
 */
/*UtilsJquery.jQueryFileUpload = function(page, fileFieldDomId, activeMask) {
	
	var result = "";
    if (activeMask==undefined)
        activeMask=true;
    if (activeMask)
        UtilsJquery.waiting();
  
	var fileInput = document.getElementById(fileFieldDomId);
	if (fileInput == undefined) {
		return;
	}
	files = fileInput.files;
	var data = new FormData();
	$j.each(files, function(key, value) {
	    data.append(key, value);
	});
	data.append("command", "addFile");
	
	$j.ajax({   
	   type: 'POST',   
	   url: page,   
	   data: data,
	   cache: false,
	   async: false,
	   dataType: 'json',
       processData: false,  // tell jQuery not to process the data
       contentType: false,   // tell jQuery not to set contentType
	   success: function(response) {
			j = j.replace("\r\n","");
			result = j;
	   }
	}); 
	
	return result;
}*/

/**
 * Asynchrone JS call
 * param: page string PHP page to call
 * param: fonction string Function to call is success
 * param: param array List of param
 * param: activeMask boolean If true, display mask during calling
 */
UtilsJquery.jQueryPostAjax = function(page,div,param,activeMask){
    if (activeMask==undefined)
        activeMask=true;
    if (activeMask)
        UtilsJquery.waiting();
    $j.ajax({
        url: page,
        data: param,
        dataType: 'text',
        type: "post",
        async: true,
        cache:false,
        success: function(j) {
            j = j.replace("\r\n","");
            UtilsJquery.unwaiting();
            var zone = document.getElementById(div);
            if (zone!=undefined){
                zone.innerHTML = j; 
            }
        }
    });
}

/**
 * 
 * @param file
 * @param command
 * @param param
 * width#color#content#titre#id#
 */
UtilsJquery.jQueryAjaxPopin = function(file,param){
	 $j.ajax({
        url: file,
        data: param,
        dataType: 'text',
        type: "post",
        async: true,
        cache:false,
        success: function(j) {
            j = j.replace("\r\n","");

            var elements = j.split("#");
            drawPopin(elements[0],elements[1],elements[2],elements[3],"","",elements[4],1,1);
        }
    });
}

/**
 * SlideUp or slideDown a div depending of slide state
 */
UtilsJquery.jQueryUpDown = function(divId){
    var div = document.getElementById(divId);
    if (div==undefined)
        return;
    if (div.alt=='down')
        $j(divId).slideUp("slow");
    else
        $j(divId).slideDown("slow");
}

/**
 * Return last z-index defined
 */
UtilsJquery.getLastKnownZIndex = function(parent){
    while (parent != undefined && parent != null && parent.length>0 && parent[0].parentElement!=undefined && parent[0].style.zIndex == 0)
    {
        parent = parent.parent();
    }
    return parent[0].style.zIndex;
}

/**
 * Construct tree
 */
UtilsJquery.jQueryTree = function(domId){

	$j(domId).treeview({
		animated: "fast",
		collapsed: true,
		unique: true
	});
}