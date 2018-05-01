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
function UtilsNetwork() {}

/**
 * Call WEB page with POST paramteters
 */
UtilsNetwork.post = function(URL, PARAMS) {
	var temp=document.createElement("form");
	temp.action=URL;
	temp.method="POST";
	temp.style.display="none";
	for(var x in PARAMS) {
		var opt=document.createElement("textarea");
		opt.name=x;
		opt.value=PARAMS[x];
		temp.appendChild(opt);
	}
	document.body.appendChild(temp);
	temp.submit();
	return temp;
}

/**
 * Update div content
 */
UtilsNetwork.refreshDiv = function(j,divId){
    j=j.replace("\r\n","");
    var div = document.getElementById(divId);
    if (div!=undefined)
        div.innerHTML = j;
}

/**
 * Display asynchrone result in popin
 * @param : j = div|title|content|js|button
 */
/*function renderPopin(j){
    j=j.replace("\r\n","");
    var msg = j.split("|");
    if (msg.length>=3){
        var js  = "";if (msg.length>3)js=msg[3];
        var btn  = "";if (msg.length>4)btn=msg[4];
        var color  = "blue";if (msg.length>5)color=msg[5];
        var tree  = "";if (msg.length>6)tree=msg[6];
        drawPopin(0,color,msg[2],msg[1],js,btn,msg[0],true,true);
        if (tree!=""){
        	jQueryTree(tree);
        	windowDeleteHeightIfNotStillNecessary(msg[0]);
        }
    }else{
        drawPopin(0,'red',msg[0],'Error','','','cms_error',true,true);
    }
}
*/
UtilsNetwork.renderPopin = function(j){

	j=j.replace("\r\n","");
	try {
		var oPopinDescription = JSON.parse(j);
	} catch (e){
		drawPopin(0,'red',j,'Error','','','cms_error',true,true);
		return;
	}
	if (oPopinDescription.error != undefined){
		 drawPopin(0,'red',oPopinDescription.message,'Error','','','cms_error',true,true);
	} else {
		drawPopin(0,oPopinDescription.color,oPopinDescription.content,oPopinDescription.title,oPopinDescription.btnAction,oPopinDescription.btnLib,oPopinDescription.id,true,true);
		if (oPopinDescription.directJsAction!=undefined){
			eval(oPopinDescription.directJsAction);
            UtilsWindow.windowDeleteHeightIfNotStillNecessary(oPopinDescription.id);
		}
	}
}

/**
 * Ask confirmation beforelaunch ShaOperation
 */
UtilsNetwork.cms_askConfirmation = function(text, fonction){
    drawPopin(0,'blue',text,"Are you sure ?",fonction,'Ok',"confirmation-popin",true,true);
}

/**
 * Reload Page
 */
UtilsNetwork.cms_reloadPage = function(page){
    location.href = page ;
}

/**
 * reload current pagepage
 */
UtilsNetwork.cms_reloadCurrentPage = function(){
    cms_reloadPage(location.href);
}

/**
 * List all js listener
 */
UtilsNetwork.getAllEvents = function() {
    var all = document.getElementsByTagName ("*");
    var _return = "";

    for (var i = 0; i < all.length; i ++) {
        for (var ii in all[i]) {
            if (typeof all[i][ii] === "function" && /^on.+/.test (ii)) { // Unreliable
                _return += all[i].nodeName + " -> " + ii + "\n";
            }
        }
    }

    alert(_return);
}