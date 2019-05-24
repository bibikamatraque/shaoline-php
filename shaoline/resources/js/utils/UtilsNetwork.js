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
 * Return true if user is robot
 */
UtilsNetwork.isRobot = function(){
	var botPattern = "(googlebot\/|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis)";
	var re = new RegExp(botPattern, 'i');
	if (re.test(navigator.userAgent)) {
	   return true;
	}
	return false;
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