<?php



/**
 * Graphique lib
 *
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  Shaoline-php copyright
 * @link     No link
 *
 */
class ShaUtilsGraphique
{
	/**
	 * Construct HTML code for progress bar
	 *
	 * @param int     $qty       Current value
	 * @param int     $total     Value max
	 * @param int     $maxLength Bar width
	 * @param boolean $showTxt   Display text or not
	 *
	 * @return HTML
	 */
	public static function drawPurcentageBar($qty, $total, $maxLength, $showTxt = true) {
		if ($total > 0) {
			$onePurcent = $total / 100;
			$purcent = round($qty / $onePurcent, 2);
		} else {
			$onePurcent = 1;
			$purcent = 0;
		}
		$purcentage = (round($purcent * $maxLength / 100));
		if ($purcent <= 33) {
			$color = "background-color:#1570EC;";
		} else if ($purcent > 33 && $purcent <= 66) {
			$color = "background-color:#1570EC;";
		} else {
			$color = "background-color:#1570EC;";
		}
		$pucentCoef = round($purcentage / 100, 2);
		$color.=" filter:alpha(opacity=" . $purcentage . ");opacity:" . $pucentCoef . "; -moz-opacity:" . $pucentCoef . ";";
		$html = "
				<div class='purcent-avancement-form'>
				<div class='purcent-avancement-border' style='width:" . $maxLength . "px'>
						<div class='purcent-avancement-bar' style='" . $color . "width:" . $purcentage . "px'></div>
								</div>";
		if ($showTxt)
			$html .= "<span>" . $qty . "/" . $total . " (" . $purcent . "%)</span>";
		$html .= "</div>";
		return $html;
	}

	/**
	 * Draw cadre structure
	 *
	 * @param string $sPrefix      DOM id prefix
	 * @param string $sContent     Content
	 * @param string $sTitle       Title
	 * @param string $bAddAndClear Add clear tag inside
	 *
	 * @return string
	 */
	public static function drawCadre($sPrefix, $sContent, $sTitle, $bAddAndClear = false) {

		$sContent = "
			<div class='cms_cadre_global $sPrefix'>
				<div class='top'>
					<span class='title'>$sTitle</span>
				</div>
				<div class='middle'>
					<div class='left'></div>
					<div class='middle'>
						$sContent
					</div>
					<div class='right'></div>
				</div>
				<div class='bottom'>
					<div class='left'></div>
					<div class='middle'></div>
					<div class='right'></div>
				</div>
			</div>
			";

		if ($bAddAndClear) {
			$sContent.="<div class='clear'></div>";
		}
		return $sContent;
	}

	/**
	 * Create carousel from array
	 *
	 * @param array $aConfig Configuration
	 * 		width => int
	 * 		height => int
	 * 		cssClass => tring
	 * 		domId => string
	 * 		btn => bool
	 * @param array $aSlides HTML array
	 *
	 * @return HTML
	 */
	public static function drawListLike($aConfig, $aSlides){

		$sCssStyle = "";
		if (isset($aConfig['width'])) {
			$sCssStyle.="width:".$aConfig['width']."px;";
		}
		if (isset($aConfig['height'])) {
			$sCssStyle.="height:".$aConfig['height']."px;";
		}
		$sCssClass= "";
		if (isset($aConfig['cssClass'])) {
			$sCssClass.=" class='".$aConfig['cssClass']."'";
		}

		if ($sCssStyle != ""){
			$sCssStyle.=" style='$sCssStyle' ";
		}

		$sResult = "<div id='".$aConfig['domId']."' $sCssStyle $sCssClass><ul>";



		foreach ($aSlides as $sSlide) {
			$sResult .= "<li $sCssStyle>".$sSlide."</li>";
		}
		$sResult .= "</ul></div>";

		if (isset($aConfig['btn']) && $aConfig['btn']) {
			$sResult .= "<div id='".$aConfig['domId']."_next'></div>";
			$sResult .= "<div id='".$aConfig['domId']."_prev'></div>";
		}

		return $sResult;
	}

	/**
	 * Launche carousel
	 *
	 * @param array $aConfig Carousel config
	 *  - pager
	 *  - pagerAnchorBuilder
	 *  - speed
	 *  - timeout 
	 *	- fx : 
	 * 		blindX, blindY, blindZ, cover, curtainX, curtainY, fade, fadeZoom,
	 * 		growX, growY, none, scrollUp, scrollDown, scrollLeft, scrollRight, scrollHorz, 
	 * 		scrollVert, shuffle, slideX, slideY, toss, turnUp, turnDown, turnLeft, 
	 * 		turnRight, uncover, wipe, zoom
	 *
	 * @return string
	 */
	public static  function launchCycle($aConfig) {

		if (!isset($aConfig['speed'])) 		$aConfig['speed'] 	= '1000';
		if (!isset($aConfig['fx']))			$aConfig['fx'] 		= 'scrollLeft';
		if (!isset($aConfig['timeout'])) 	$aConfig['timeout'] = '0';
		
		$domId = $aConfig['domId'];
		unset($aConfig['domId']);
		
		$code = array();
		foreach ($aConfig as $key => $value) {
			$code[] = $key.":'".$value."'";
		}
		
		$code[] = "pagerAnchorBuilder: function(){ return '<div></div>';}";
		
		return '$j("'.$domId.'").cycle({'.implode(",", $code).'});';
		

	}

	/**
	 * Launche carousel
	 *
	 * @param array $aConfig Carousel config
	 *  - speed 		: Transition duration (5)
	 *  - auto 			: Auto speed (1000)
	 *  - visible 		: Qty slides visibles (1)
	 *  - vertical 		: true for vertical scrolling
	 *  
	 * @return string
	 */
	public static  function launchCarousel($aConfig) {
	
		
		if (!isset($aConfig['speed'])) 		$aConfig['speed'] 		= '5';
		if (!isset($aConfig['auto'])) 		$aConfig['auto'] 		= '1000';
		if (!isset($aConfig['visible'])) 	$aConfig['visible'] 	= '1';
		//if (!isset($aConfig['circular'])) 	$aConfig['circular'] 	= '0';
		if (!isset($aConfig['vertical'])) 	$aConfig['vertical'] 	= 'true';
		if (!isset($aConfig['scroll'])) 	$aConfig['scroll'] 		= '-1';
		
		$domId = $aConfig['domId'];
		unset($aConfig['domId']);

		$code = array();
		foreach ($aConfig as $key => $value) {
			$code[] = $key.":".$value."";
		}
	
		return '$j("'.$domId.'").jCarouselLite({'.implode(",", $code).'});';
	}
	
	
	public static function distinctFirstChar($sText){
		$sLeft = substr($sText,0,1);
		$sRight = substr($sText,1,strlen($sText) - 1);
		return "<span class='left'>$sLeft</span><span class='right'>$sRight</span>";
	}

	public static function get64BitEncodedPicture($sPath){

		$oPicture = imagecreatefrompng($sPath);
		imagesavealpha($oPicture, true);

		ob_start();

		imagepng($oPicture) or die ("Error during picture generation");
		$sDatas = ob_get_clean();

		imagedestroy($oPicture);

		return "src='data:image/jpeg;base64,".base64_encode($sDatas)."'";

	}


	/**
	 * Create tab header
	 * @param unknown $iTabId          Id prefix
	 * @param unknown $aTablHeaderItem Header element
	 *
	 * @return HTML
	 */
	public static function createTabHeader($iTabId, $aTablHeaderItems){
		$sResult = "<ul id='cms_tabs_".$iTabId."' class='cms_tabs'>";

		$iIndex = 0;
		foreach ($aTablHeaderItems as $sTablHeaderItem){
			$sTabSelected = ($iIndex == 0) ? "tab_selected" : "";
			$sResult .= "<li id='cms_tab_header_".$iTabId."_".$iIndex."' class='cms_tab_header_".$iTabId." $sTabSelected' onclick='cms_tab(\"$iTabId\", $iIndex)'>".$sTablHeaderItem."</li>";
			$iIndex++;
		}

		$sResult .= "</ul><div class='clear'></div>";

		return $sResult;
	}

}

?>