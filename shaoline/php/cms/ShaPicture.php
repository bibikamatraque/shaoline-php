<?php

/**
 * ShaPicture
 *
 * @category   Class
 * @package    Shaoline
 * @subpackage Component
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaPicture {

    const CONST_MAX_WIDTH = 15000;
    const CONST_MAX_HEIGHT = 15000;

    public static function getPicture($picturePath, $pictureWidth, $pictureHeight = null, $forceRewrite = false){
    	
        $picturePath = ShaUtilsString::replace($picturePath, "..", "");
        $picturePath = ShaUtilsString::replace($picturePath, "//", "/");
        $picturePath = ShaUtilsString::replace($picturePath, "\\\\", "\\");

        if ($pictureHeight == null){
            $pictureHeight = $pictureWidth;
        }

        $pictureWidth = (int)$pictureWidth;
        $pictureHeight = (int)$pictureHeight;

        if ($pictureWidth < 1 || $pictureWidth > self::CONST_MAX_WIDTH){
        	ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Image width to important : $pictureWidth ($picturePath)");
            return "shaoline/resources/img/cms_no_picture.png";
        }
        if ($pictureHeight < 1 || $pictureHeight > self::CONST_MAX_HEIGHT){
        	ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Image height to important : $pictureHeight ($picturePath)");
            return "shaoline/resources/img/cms_no_picture.png";
        }

        //. \ + * ? [ ^ ] $ ( ) { } = ! < > | : -
        /*if (!preg_match("/^[A-Za-z0-9_\\\\\/\-\.]+$/", $picturePath)){
         	ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Bad image path : $picturePath");
            return "shaoline/resources/img/cms_no_picture.png";
        }*/

        if (!is_file(ShaContext::getPathToWeb().$picturePath)){
        	if (is_file(ShaContext::getPathToWeb()."shaoline/resources/img/cms_no_picture.png")){
        		ShaUtilsLog::warn(__CLASS__."::".__FUNCTION__." : Original picture not found : '".ShaContext::getPathToWeb().$picturePath."'");
        		return self::getPicture("shaoline/resources/img/cms_no_picture.png", $pictureWidth, $pictureHeight);
        	} else {
        		ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Basic image not found : 'shaoline/resources/img/cms_no_picture.png'");
        		return "shaoline/resources/img/cms_no_picture.png";
        	}
        }

        $pathInfo = pathinfo($picturePath);

        $ext = $pathInfo['extension'];
        if ($ext != 'png' && $ext != 'jpeg' && $ext != 'bmp' && $ext !='jpg'){
        	ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Bad picture extension : $ext ($picturePath)");
            return "shaoline/resources/img/cms_no_picture.png";
        }

        $finalPath =  $pathInfo['dirname'] ."/" . $pathInfo['filename'] . "_" . $pictureWidth . "_" . $pictureHeight . "." . $ext;

        if ($forceRewrite || !is_file($finalPath)){
        	//ShaUtilsLog::verbose('convert "'.ShaContext::getPathToWeb().$picturePath.'" -resize '.$pictureWidth.'x'.$pictureHeight.'\\! "'.ShaContext::getPathToWeb().$finalPath.'"');
            exec('convert "'.ShaContext::getPathToWeb().$picturePath.'" -resize '.$pictureWidth.'x'.$pictureHeight.' "'.ShaContext::getPathToWeb().$finalPath.'"');
        }

        return $finalPath;

    }
    
    public static function convertPicture($currentPath, $newPath){

        //ShaUtilsLog::verbose(__CLASS__.":".__FUNCTION__.' : convert "'.$currentPath.'" "'.$newPath.'"');
        exec('convert "'.$currentPath.'" "'.$newPath.'"');

    }

    public static function getFormat($path){

        $pathPart = explode(".", $path);
        if (count($pathPart) > 0){
            return $pathPart[count($pathPart) - 1];
        }
        return "";

    }
    

}

?>