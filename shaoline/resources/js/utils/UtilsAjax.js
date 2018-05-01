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
function UtilsAjax() {}

/**
 * Call controller action and do action
 * 
 * @param string sRoute  Url to call
 * @param array  aParams Post params
 * @param bool   bAsync	  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajax = function(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync){	
	
	if (fSuccessFunction==undefined){
		fSuccessFunction = function(oShaResponse){
			alert("No success function found in 'callControllerAction(...)'");
		}
	}
	
	if (fErrorFunction==undefined){
		fErrorFunction = function(oShaResponse){
			alert(oShaResponse.ShaResponseText);
		};
	}
	
	if (bAsync==undefined){
		bAsync=true;
	}
	
	if (aParams==undefined){
		aParams={};
	}
	
	$.ajax({
		beforeSend: function(xhr) {
			xhr.overrideMimeType("text/plain; charset=ISO-8859-1");
		},
        type	: "POST",
        url		: sRoute,
        data    : aParams,
        async	: bAsync,
        error	: fErrorFunction,
        success	: fSuccessFunction
	});
	
}

/**
 * Call controller action and put result into div
 * 
 * @param string sRoute  Url to call
 * @param string divId   Div dom id
 * @param array  aParams Post params
 * @param bool   bAsync  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajaxToDiv = function(sRoute, divId, aParams, bAsync){
	fSuccessFunction = function(sShaResponse){
		var oDom = document.getElementById(divId);
		if (oDom!=undefined){
			oDom.innerHTML = sShaResponse;
		}
	};
	
	fErrorFunction =  function(oShaResponse){
		var oDom = document.getElementById(divId);
		if (oDom!=undefined){
			oDom.innerHTML = oShaResponse.ShaResponseText;
		}
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}


/**
 * Call controller action and put result into popin
 * 
 * @param string sRoute  Url to call
 * @param array  aParams Post param array
 * @param bool   bAsync  False for synchrone call (default = true)
 * @param string sTitle  Popin = asynchrone
 * @param string sColor  Popin color (default = UtilsPopin.CONST_COLOR_GOOD)
 * 
 * @return void
 */
UtilsAjax.ajaxToPopin = function(sRoute, sTitle, aParams, bAsync, sColor){
	
	if (sColor == undefined){
		sColor = UtilsPopin.CONST_COLOR_GOOD;
	}
	
	fSuccessFunction = function(sShaResponse){
		UtilsPopin.show(sTitle, sShaResponse, sColor);
	};
	
	fErrorFunction =  function(oShaResponse){
		UtilsPopin.show(sTitle, oShaResponse.ShaResponseText, UtilsPopin.CONST_COLOR_ERROR);
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}


/**
 * Call controller action and put result into div (if error open error popin)
 * 
 * @param string sRoute  Url to call
 * @param string divId   Div dom id
 * @param array  aParams Post params
 * @param bool   bAsync  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajaxToDivOrErrorPopin = function(sRoute, divId, aParams, bAsync){
	fSuccessFunction = function(sShaResponse){
		var oDom = document.getElementById(divId);
		if (oDom!=undefined){
			oDom.innerHTML = sShaResponse;
		}
	};
	
	fErrorFunction =  function(oShaResponse){
		UtilsPopin.show("Erreur", oShaResponse.ShaResponseText, UtilsPopin.CONST_COLOR_ERROR);
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}

/**
 * Call controller action and display popin if error, if not, do nothing
 * 
 * @param string sRoute  Url to call
 * @param array  aParams Post params
 * @param bool   bAsync  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajaxToPopinIfError = function(sRoute, aParams, bAsync){
	fSuccessFunction = function(sShaResponse){
		//Do nothing
	};
	
	fErrorFunction =  function(oShaResponse){
		UtilsPopin.show("Erreur", oShaResponse.ShaResponseText, UtilsPopin.CONST_COLOR_ERROR);
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}

/**
 * Call controller action and put result before div (if error open error popin)
 * 
 * @param string sRoute  Url to call
 * @param string divId   Div dom id
 * @param array  aParams Post params
 * @param bool   bAsync  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajaxBeforeDivOrErrorPopin = function(sRoute, divId, aParams, bAsync){
	fSuccessFunction = function(sShaResponse){
		var oDom = $("#" + divId);
		if (oDom.length>0){
			oDom.before(sShaResponse);
		}
	};
	
	fErrorFunction =  function(oShaResponse){
		UtilsPopin.show("Erreur", oShaResponse.ShaResponseText, UtilsPopin.CONST_COLOR_ERROR);
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}

/**
 * Call controller action and clear div (if error open error popin)
 * 
 * @param string sRoute  Url to call
 * @param string divId   Div dom id
 * @param array  aParams Post params
 * @param bool   bAsync  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajaxDeleteDivOrErrorPopin = function(sRoute, divId, aParams, bAsync){
	fSuccessFunction = function(sShaResponse){
		var oDom = $("#" + divId);
		if (oDom.length>0){
			oDom.remove();
		}
	};
	
	fErrorFunction =  function(oShaResponse){
		UtilsPopin.show("Erreur", oShaResponse.ShaResponseText, UtilsPopin.CONST_COLOR_ERROR);
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}


/**
 * Call controller action and refresh page (if error open error popin)
 * 
 * @param string sRoute  Url to call
 * @param string divId   Div dom id
 * @param array  aParams Post params
 * @param bool   bAsync  False for synchrone call (default = true)
 * 
 * @return void
 */
UtilsAjax.ajaxRefreshPageOrErrorPopin = function(sRoute, aParams, bAsync){
	fSuccessFunction = function(sShaResponse){
		window.location.reload();
		window.location.href=window.location.href;
	};
	
	fErrorFunction =  function(oShaResponse){
		UtilsPopin.show("Erreur", oShaResponse.ShaResponseText, UtilsPopin.CONST_COLOR_ERROR);
	};

	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, bAsync);
	
}


/**
* Call controller action and return true if return code == 200, false if not	 
* Warning : synchrone function 
*  
* @param string sRoute  Url to call 
* @param array  aParams Post params 
*  
* @return void 
*/
UtilsAjax.ajaxBoolean = function(sRoute, aParams){
	var bResult;
	
	fSuccessFunction = function(sShaResponse){
		bResult = true;
	};
	
	fErrorFunction =  function(oShaResponse){
		bResult = false;	 
	};
	
	UtilsAjax.ajax(sRoute, aParams, fSuccessFunction, fErrorFunction, false);
	return bResult;	 
}

