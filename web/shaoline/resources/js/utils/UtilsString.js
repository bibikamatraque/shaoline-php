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
function UtilsString() {
}

/**
 * Convert ASCII text to char text
 * param: string text Ascii text to convert
 * return: string converting text
 */
UtilsString.AsciiTochar = function (text, n) {
	if (n == undefined){
		n = 5;
	}
    if (text == undefined)
        return "";
    var result = "";
    for (i = 0; i < text.length; i += n)
        result += "" + String.fromCharCode(text.substr(i, n));
    return UtilsString.utf8Decode(result);
}

/**
 * Convert a string to UTF8
 * param: string text Ascii text to convert
 * return: string converting text
 */
UtilsString.utf8Decode = function (utftext) {
    var string = "";
    var i = 0;
    var c = c1 = c2 = 0;
    while (i < utftext.length) {

        c = utftext.charCodeAt(i);

        if (c < 128) {
            string += String.fromCharCode(c);
            i++;
        }
        else if ((c > 191) && (c < 224)) {
            c2 = utftext.charCodeAt(i + 1);
            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            i += 2;
        }
        else {
            c2 = utftext.charCodeAt(i + 1);
            c3 = utftext.charCodeAt(i + 2);
            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }
    return string;
}


/**
 * Convert char text to ASCII text
 * param: string text Char text to convert
 * return: string converting text
 */
UtilsString.getASCII = function (text) {
    if (text == undefined)
        return "";
    text = "" + text;
    var reponse = "";
    var lettre = "";
    var code = 0;
    for (var i = 0; i < text.length; i++) {
        lettre = text.substr(i, 1);
        code = lettre.charCodeAt(0);
        reponse += "" + UtilsString.formatN(code, 5);
    }
    return reponse;
}

/**
 * Complete a string formated number with zero to obteine a N sized string
 * param: interger text Number to format
 * param: interge n Total size wanted
 * return: formated string
 */
UtilsString.formatN = function (text, n) {
    text = text + "";
    return repete('0', n - text.length) + text;
}

/**
 * Return N occurence of string
 * param: lettre String to multiply
 * param: n Number of occurence wanted
 * return: string
 */
UtilsString.repete = function (lettre, n) {
    var result = "";
    for (var i = 0; i < n; i++) {
        result += "" + lettre;
    }
    return result;
}

/**
 * Clear formulaire field when clicking
 */
UtilsString.fieldCleaner = function () {


    $j("input[type=text].jcleanner").unbind('focus');
    $j("input[type=text].jcleanner").focus(function () {
        var emptyText = $j(this).attr('alt');
        if ($j(this).val() == emptyText) {
            $j(this).val("");
        }

    });


    $j("input[type=text].jcleanner").unbind('blur');
    $j("input[type=text].jcleanner").blur(function () {
        if ($j(this).val().length == 0) {
            var emptyText = $j(this).attr('alt');
            $j(this).val(emptyText);
        }
    });


    $j("input[type=password].jcleanner").unbind('focus');
    $j("input[type=password].jcleanner").focus(function () {
        var emptyText = $j(this).attr('alt');
        if ($j(this).val() == emptyText) {
            $j(this).val("");
        }

    });


    $j("input[type=password].jcleanner").unbind('blur');
    $j("input[type=password].jcleanner").blur(function () {
        if ($j(this).val().length == 0) {
            var emptyText = $j(this).attr('alt');
            $j(this).val(emptyText);
        }
    });

    $j("textarea").unbind('focus');
    $j("textarea").focus(function () {
        var emptyText = $j(this).attr('alt');
        if ($j(this).val() == emptyText) {
            $j(this).val("");
        }

    });

    $j("textarea").unbind('blur');
    $j("textarea").blur(function () {
        if ($j(this).val().length == 0) {
            var emptyText = $j(this).attr('alt');
            $j(this).val(emptyText);
        }
    });

}

UtilsString.forceIntValue = function (text) {
    var result = "";
    for (var i = 0; i < text.length; i++) {
        if (text[i] % 1 === 0) {
            result += text[i];
        }
        if (text[i] == ".") {
            break;
        }
    }
    return (result == "") ? 0 : parseInt(result);
}

UtilsString.encodeBase64 = function(data) {
	  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	    ac = 0,
	    enc = '',
	    tmp_arr = [];

	  if (!data) {
	    return data;
	  }

	  do {
	    o1 = data.charCodeAt(i++);
	    o2 = data.charCodeAt(i++);
	    o3 = data.charCodeAt(i++);

	    bits = o1 << 16 | o2 << 8 | o3;

	    h1 = bits >> 18 & 0x3f;
	    h2 = bits >> 12 & 0x3f;
	    h3 = bits >> 6 & 0x3f;
	    h4 = bits & 0x3f;

	    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
	  } while (i < data.length);

	  enc = tmp_arr.join('');

	  var r = data.length % 3;

	  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
	}

UtilsString.decodeBase64 = function(data) {
	  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	    ac = 0,
	    dec = '',
	    tmp_arr = [];

	  if (!data) {
	    return data;
	  }

	  data += '';

	  do {
	    h1 = b64.indexOf(data.charAt(i++));
	    h2 = b64.indexOf(data.charAt(i++));
	    h3 = b64.indexOf(data.charAt(i++));
	    h4 = b64.indexOf(data.charAt(i++));

	    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

	    o1 = bits >> 16 & 0xff;
	    o2 = bits >> 8 & 0xff;
	    o3 = bits & 0xff;

	    if (h3 == 64) {
	      tmp_arr[ac++] = String.fromCharCode(o1);
	    } else if (h4 == 64) {
	      tmp_arr[ac++] = String.fromCharCode(o1, o2);
	    } else {
	      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
	    }
	  } while (i < data.length);

	  dec = tmp_arr.join('');

	  return dec.replace(/\0+$/, '');
	}