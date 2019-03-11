<?php


/**
 * String lib
 *
 * PHP version 5.3
 *
 * @category   Core
 * @package    ShaUtils
 * @subpackage Php
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Shaoline-php copyright
 * @link        No link
 *
 */
class ShaUtilsCapcha
{
	/**
	 * Return rendom code for capcha
	 *
	 * @param int $iSize Code size
	 *
	 * @return string
	 */
	public static function getRandomCode($iSize) {
		$sPossible = '23456789bcdfghjkmnpqrstvwxyz';
		$sCode = '';
		$i = 0;
		$iPossibleLength = strlen($sPossible)-1;
		while ($i < $iSize) {
			$sCode .= substr($sPossible, mt_rand(0, $iPossibleLength), 1);
			$i++;
		}
		return $sCode;
	}

	/**
	 * Générate capcha
	 *
	 * @param string $sCode         Code to draw
	 * @param int    $iWidth        Picture width
	 * @param int    $iHeight       Picture height
	 * @param int    $iNoiseDensity Noise intensity
	 * @param int    $iBackColorR   Background color R
	 * @param int    $iBackColorG   Background color G
	 * @param int    $iBackColorB   Background color B
	 * @param int    $iFontColorR   Font color R
	 * @param int    $iFontColorG   Font color G
	 * @param int    $iFontColorB   Font color B
	 * @param int    $iNoiseColorR  Noise color R
	 * @param int    $iNoiseColorG  Noise color G
	 * @param int    $iNoiseColorB  Noise color B
	 *
	 * @return string
	 */
	public static function getCapchaPicture($sCode, $iWidth, $iHeight, $iNoiseDensity = 100, $iBackColorR = 255, $iBackColorG = 255, $iBackColorB = 255, $iFontColorR = 0, $iFontColorG = 0, $iFontColorB = 0,$iNoiseColorR = 0, $iNoiseColorG = 0, $iNoiseColorB = 0) {
	   	$sFont = ShaContext::getPathToResources().'/css/capcha.ttf';

		$sFontSize = $iHeight * 0.75;
		$oImage = imagecreate($iWidth, $iHeight) or die('Cannot initialize new GD image stream');

		$sBackgroundColor = imagecolorallocate($oImage, $iBackColorR, $iBackColorG, $iBackColorB);
		$sTextColor = imagecolorallocate($oImage, $iFontColorR, $iFontColorG, $iFontColorB);
		$sNoiseColor = imagecolorallocate($oImage, $iNoiseColorR, $iNoiseColorG, $iNoiseColorB);

		for ( $i=0; $i<$iNoiseDensity; $i++ ) {
			imagefilledellipse($oImage, mt_rand(0, $iWidth), mt_rand(0, $iHeight), 1, 1, $sNoiseColor);
		}

		for ( $i=0; $i<$iNoiseDensity; $i++ ) {
			imageline($oImage, mt_rand(0, $iWidth), mt_rand(0, $iHeight), mt_rand(0, $iWidth), mt_rand(0, $iHeight), $sNoiseColor);
		}

		$oImageTest = imagecreate($iWidth, $iHeight);
		$textbox = imagettftext ($oImageTest , $sFontSize , 0 , 0 , 0 , $sTextColor , $sFont , $sCode );

		/*
		[0] => 0 // lower left X coordinate
        [1] => -1 // lower left Y coordinate
        [2] => 198 // lower right X coordinate
        [3] => -1 // lower right Y coordinate
        [4] => 198 // upper right X coordinate
        [5] => -20 // upper right Y coordinate
        [6] => 0 // upper left X coordinate
        [7] => -20 // upper left Y coordinate

        array(8) {
        [0]=> int(0)
        [1]=> int(9)
        [2]=> int(245)
        [3]=> int(9)
        [4]=> int(245)
        [5]=> int(-53)
        [6]=> int(0)
        [7]=> int(-53) }
		 */

		$iTextWidth = $textbox[4] - $textbox[6];
		$iTextHeight = min($textbox[5], $textbox[7]);

		imagedestroy($oImageTest);

		$iX = ($iWidth - $iTextWidth) / 2;
		$iY = ($iHeight - $iTextHeight) / 2;

		imagettftext($oImage, $sFontSize, 0, $iX, $iY, $sTextColor, $sFont , $sCode) || die('Error in imagettftext function');
		//imagettftext($oImage, $sFontSize, 0, $iX, $iY, $sTextColor, $sFont , $sCode) || die('Error in imagettftext function');

		ob_start();
		//header("Content-type: image/jpeg");
		imagejpeg($oImage) or die ("Error during picture generation");
		$sDatas = ob_get_clean();
		imagedestroy($oImage);

		return "<img src='data:image/jpeg;base64,".base64_encode($sDatas)."'>";
	}


}
?>