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
function UtilsWindow() {
}

//Id de la fenetre en cour de selectionnee
var SELECTED_WINDOWS = "";
var LAST_SELECTED = "";
var CURRENT_MOUSE_X = 0;
var CURRENT_MOUSE_Y = 0;
var LAST_Z_INDEX = 50000;
var MASK_Z_INDEX = new Array();
var QTY_OPENED_WINDOWS = 0;

UtilsWindow.drawPopin = function (width, color, content, titre, action, btnText, id, activeDragDrop, activeMask, style) {

	if (style == undefined) style = "";
    if (id == undefined || id == "") id = "default";

    var borderColor;
    if (color == "blue")borderColor = '#208af1'; else borderColor = '#F8222A';

    var html = content;
    if (action != undefined && action != "" && btnText != undefined && btnText != "")
        html += '<br/><span class="cms_button cms_center" onclick="' + action + '">' + btnText + '</span>';

    UtilsWindow.createWindow(id, titre, color, width, 150, html, new Array(BTN_CLOSE_BLACK), style, activeDragDrop, activeMask);
    UtilsString.fieldCleaner();
    return id;
}


UtilsWindow.launchWindowsMoveSystem = function () {

    if (document.layers) { 
        document.captureEvents(Event.MOUSEMOVE);
        document.onmousemove = UtilsWindow.captureMousePosition;
    } else if (document.all) {
        document.onmousemove = UtilsWindow.captureMousePosition;
    } else if (document.getElementById) { 
        document.onmousemove = UtilsWindow.captureMousePosition;
    }

}


UtilsWindow.launchWindowsUpSystem = function () {

    if (document.layers) { 
        document.captureEvents(Event.MOUSEUP);
        document.onmouseup = UtilsWindow.stopDragAndDrop();
    } else if (document.all) {
        document.onmouseup = UtilsWindow.stopDragAndDrop();
    } else if (document.getElementById) {
        document.onmouseup = UtilsWindow.stopDragAndDrop();
    }

}


UtilsWindow.captureMousePosition = function (e) {
    CURRENT_MOUSE_X = e.clientX;
    CURRENT_MOUSE_Y = e.clientY;
    if (SELECTED_WINDOWS != "") {
        UtilsWindow.moveWindow(SELECTED_WINDOWS);
    }
}


UtilsWindow.stopDragAndDrop = function () {
    if (SELECTED_WINDOWS != "") {
        UtilsWindow.dropWindow(SELECTED_WINDOWS);
    }
}


UtilsWindow.closeWindow = function (_id) {
    var obj = document.getElementById("window-" + _id);
    if (obj != undefined && obj.parentNode != undefined) {
        var parent = obj.parentNode;
        parent.removeChild(obj);
        LAST_Z_INDEX--;
        QTY_OPENED_WINDOWS--;
        if (MASK_Z_INDEX.length > 0 && MASK_Z_INDEX[MASK_Z_INDEX.length - 1] == LAST_Z_INDEX) {
            MASK_Z_INDEX.splice(MASK_Z_INDEX.length - 1, 1);
            QTY_OPENED_WINDOWS--;
            if (MASK_Z_INDEX.length == 0) {
                $j("#cms_masque").css({ "display": "none" });
                QTY_OPENED_WINDOWS--;
            } else {
                $j("#cms_masque").css({ "z-index": MASK_Z_INDEX[MASK_Z_INDEX.length - 1] });
                $j("#cms_masque").css({ "display": "block" });
            }
            LAST_Z_INDEX--;
        }

    }
}


UtilsWindow.mimiserWindow = function (_id) {

}

UtilsWindow.getPositionObj = function (Obj) {
    var PosX = 0;
    var PosY = 0;

    PosX = Obj.offsetLeft;
    PosY = Obj.offsetTop;

    if (Obj.offsetParent) {

        while (Obj = Obj.offsetParent) {

            PosX += Obj.offsetLeft;
            PosY += Obj.offsetTop;
        }
    }

    return({left: PosX, top: PosY});
}


UtilsWindow.dragWindow = function (_id) {
    if (!event) var event = window.event;
    var obj = document.getElementById("isDraged-" + _id);
    var x = document.getElementById("xDelta-" + _id);
    var y = document.getElementById("yDelta-" + _id);
    var fenetre = document.getElementById("window-" + _id);
    if ((obj != undefined) && (x != undefined) && (y != undefined) && (fenetre != undefined)) {
        LAST_SELECTED = SELECTED_WINDOWS;
        SELECTED_WINDOWS = _id;
        var position = UtilsWindow.getPositionObj(fenetre);
        x.value = CURRENT_MOUSE_X - position.left;
        y.value = CURRENT_MOUSE_Y - position.top;
    }
}

UtilsWindow.dropWindow = function (_id) {
    SELECTED_WINDOWS = "";
}

UtilsWindow.moveWindow = function (_id) {
    var obj = document.getElementById("isDraged-" + _id);
    var x = document.getElementById("xDelta-" + _id);
    var y = document.getElementById("yDelta-" + _id);
    var fenetre = document.getElementById("window-" + _id);
    if ((obj != undefined) && (x != undefined) && (y != undefined) && (fenetre != undefined)) {
        fenetre.style.left = parseInt(CURRENT_MOUSE_X) - parseInt(x.value) + "px";
        fenetre.style.top = parseInt(CURRENT_MOUSE_Y) - parseInt(y.value) + "px";
        var last = document.getElementById("window-" + LAST_SELECTED);
        if (last != undefined) {
            last.style.zIndex = fenetre.style.zIndex_Z_INDEX - 1;
        }
        fenetre.style.zIndex = LAST_Z_INDEX;
    }
}

var BTN_CLOSE_BLACK = new Array();
BTN_CLOSE_BLACK['title'] = 'close';
BTN_CLOSE_BLACK['img'] = 'shaoline/resources_' + SHA_RESOURCE_SUFFIX + '/img/cms_btn_close.png';
BTN_CLOSE_BLACK['onClick'] = 'UtilsWindow.closeWindow("_id_")';

var BTN_MINIMISER_BLACK = new Array();
BTN_MINIMISER_BLACK['title'] = 'minimiser';
BTN_MINIMISER_BLACK['img'] = 'shaoline/resources_' + SHA_RESOURCE_SUFFIX + '/img/cms_btn_close.png';
BTN_MINIMISER_BLACK['onClick'] = 'UtilsWindow.mimiserWindow("_id_")';

var BORDER_DEFAULT = "border-default";
var BORDER_ROUND = "border-round";


UtilsWindow.createWindow = function (name, titre, color, width, top, contenu, menuElements, moreStyle, dragDropOn, activeMask) {
    var fenetre = document.getElementById("window-" + name);
    if (fenetre != undefined) {
        UtilsWindow.closeWindow(name);
    }

    if (dragDropOn == undefined || dragDropOn == "" || dragDropOn == false)
        dragDropOn = "0";
    else
        dragDropOn = "1";
    if (activeMask == undefined || activeMask == "" || activeMask == false)
        activeMask = "0";
    else
        activeMask = "1";

    LAST_Z_INDEX++;

    if (activeMask == "1") {
        MASK_Z_INDEX[MASK_Z_INDEX.length] = LAST_Z_INDEX;
        $j("#cms_masque").css({ "z-index": LAST_Z_INDEX });

        $j("#cms_masque").css({ "display": "block" });
        LAST_Z_INDEX++;
        QTY_OPENED_WINDOWS++;
    }

    var position = "";
    if (dragDropOn == "1")
        position = "position:fixed;";

    var menu = "<div class='window-menu' id='menu-" + name + "'>";

   
    //TODO use parameter tio active it
    //Maximize/minimize button
    //menu += "<div id='window-maximize-"+name+"' class='window-btn windows-maximize' >";
    //menu += "<img src='shaoline/resources_" + SHA_RESOURCE_SUFFIX + "/img/cms_maximize.png' onclick=\"UtilsWindow.maximize('"+name+"')\" />";
    //menu += "</div>";
	
    if (menuElements.length) {
        for (var i = 0; i < menuElements.length; i++) {
            menu += "<div class='window-btn' title='" + menuElements[i]['title'] + "' onclick='" + menuElements[i]['onClick'].replace("_id_", name) + "' >";
            menu += "<img alt='" + menuElements[i]['id'] + "' src='" + menuElements[i]['img'] + "' />";
            menu += "</div>";
        }
    }

    menu += "</div>";

    var title = "<div class='windows-titre'>";
    title += titre;
    title += "</div>";

    var dragdrop = "";
    if (dragDropOn == "1") {
        dragdrop += ' onmousedown="' + "UtilsWindow.dragWindow('" + name + "')" + '" ';
        dragdrop += ' onmouseup="' + "UtilsWindow.dropWindow('" + name + "')" + '" ';
    }

    var cssWidth = "";
    if (width > 0) {
        cssWidth = "width:" + width + "px;";
    }

	contenu = contenu.replace("[SHA_POPIN_ID]", name);
	
    var html = "";
    html = "<div class='window-popin " + color + "' id='window-" + name + "' style='display:none;" + cssWidth + "top:" + top + "px;" + moreStyle + ";" + position + "'>";
    html += "<input id='isDraged-" + name + "' type='hidden' value='0'/>";
    html += "<input id='xDelta-" + name + "' type='hidden' type='x' value='0'/>";
    html += "<input id='yDelta-" + name + "' type='hidden' type='y' value='0'/>";

    //html += "<div class='windows-header' style='background: url(\"shaoline/resources/img/cms_"+color+".png\") repeat-x scroll 0 0 #F3F3F3;' "+dragdrop+">"+title+menu+"</div>";
    html += "<div class='windows-header' " + dragdrop + ">" + title + menu + "</div>";
    html += "<div class='windows-content' id='window-content-" + name + "' >" + contenu + "</div>";
    html += "<div class='windows-footer'></div>";
    html += "<div style='clear:both'></div>";
    html += "</div>";
    var zone = document.getElementById('cms_persistent_div');
    zone.innerHTML += html;

    //Auto Z
    QTY_OPENED_WINDOWS++;
    LAST_SELECTED = name;
    var newWindows = document.getElementById("window-" + name);
    newWindows.style.zIndex = LAST_Z_INDEX;

    UtilsWindow.windowAutoSize(name);
    UtilsWindow.windowsCenter(name);
    newWindows.style.display = 'block';

}

UtilsWindow.windowAutoSize = function (name) {

    var newWindows = $j("#window-" + name);
    var windowsContent = $j("#window-content-" + name);
    var documentHeight = document.body.offsetHeight;
    //Auto height
    if (newWindows.height() >= (documentHeight - 50)) {
        windowsContent.css("height", (documentHeight - 50) + "px");
        windowsContent.css("overflow-y", "scroll");
        UtilsWindow.windowsCenter(name);
    }
}

/**
 * Delete height and scrolle if possible
 *
 * @param string name Window name
 */
UtilsWindow.windowDeleteHeightIfNotStillNecessary = function (name) {

    var windowsContent = document.getElementById("window-content-" + name);
    if (windowsContent == undefined) { return;}

    var height = UtilsForm.getInnerHeight(windowsContent);
    var documentHeight = document.body.offsetHeight;
    if (height <= (documentHeight)) {
        windowsContent.style.height = "auto";
        windowsContent.style.maxHeight = (documentHeight - 40) + "px";
        windowsContent.style.overflowY = "none";
        UtilsWindow.windowsCenter(name);
    }

}

/**
 * Maximize windows
 *
 * @param string name Window name
 */
UtilsWindow.maximize = function(name){
    var windowsContent = document.getElementById("window-content-" + name);
    if (windowsContent == undefined) { return; }

    var screenHeight = document.body.offsetHeight - 40;
    var screenWidth = document.body.offsetWidth;
    windowsContent.style.height = screenHeight + "px";
    windowsContent.style.width = screenWidth + "px";
    UtilsWindow.windowsCenter(name);

    var windowsMaximizeBtn = document.getElementById("window-maximize-" + name);
    if (windowsMaximizeBtn == undefined) { return; }
    windowsMaximizeBtn.innerHTML = "<img src='shaoline/resources_" + SHA_RESOURCE_SUFFIX + "/img/cms_maximize.png' onclick=\"UtilsWindow.minimize('"+name+"')\" />";

}

/**
 * Minimize window
 *
 * @param name
 */
UtilsWindow.minimize = function(name){
    var windowsContent = document.getElementById("window-content-" + name);
    if (windowsContent == undefined) { return; }

    windowsContent.style.height = "auto";
    windowsContent.style.width = "auto";
    UtilsWindow.windowAutoSize(name);
    UtilsWindow.windowsCenter(name);

    var windowsMaximizeBtn = document.getElementById("window-maximize-" + name);
    if (windowsMaximizeBtn == undefined) { return; }
    windowsMaximizeBtn.innerHTML = "<img src='shaoline/resources_" + SHA_RESOURCE_SUFFIX + "/img/cms_maximize.png' onclick=\"UtilsWindow.maximize('"+name+"')\" />";

}

/**
 * Recenter window
 *
 * @param string name Widnows name
 */
UtilsWindow.windowsCenter = function (name) {
    var newWindows = document.getElementById("window-" + name);
    if (newWindows == undefined) {
        return;
    }
    var documentHeight = document.body.offsetHeight;
    var documentWidth = document.body.offsetWidth;

    //Top decalage
    var topDecalage = Math.round((documentHeight / 2) - ($j("#window-" + name).height() / 2));
    newWindows.style.top = topDecalage + "px";

    //Left decalage
    var leftDecalage = Math.round((documentWidth / 2) - ($j("#window-" + name).width() / 2));
    newWindows.style.left = leftDecalage + "px";

}
