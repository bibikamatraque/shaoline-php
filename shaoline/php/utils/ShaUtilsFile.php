<?php

/**
 * File lib
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
class ShaUtilsFile
{

    /**
     * Read content of file
     *
     * @param string $path File path
     *
     * @return string File content
     * @throws Exception
     */
    public static function getFileContent($path)
    {
        $f = fopen($path, 'rb');
        if (!is_resource($f)){
            throw new Exception("Disabled to open file : ".$path);
        }

        $data = '';
        while (!feof($f)) {
            $data .= fread($f, 1024);
        }
        fclose($f);
        return $data;
    }

    /**
     * Return array with file name of folder
     *
     * @param string $path Folder path
     * @param boolean $reccurcive Is reccurcive
     *
     * @return array
     */
    public static function getFiles($path, $reccurcive = false)
    {
        $output = array();
        self::_getFolderFiles($path, $output, $reccurcive);
        return $output;
    }

    /**
     * Return array with file name of folder
     *
     * @param string $path Path to scan
     * @param array &$output Enter into bfolders
     * @param array $reccurcive Output result
     *
     * @return void
     */
    private static function _getFolderFiles($path, &$output = null, $reccurcive = false)
    {

        if (!isset($output)) {
            $output = array();
        }
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }
            if (is_dir($path . '/' . $result)) {
                if ($reccurcive) {
                    self::_getFolderFiles(ShaUtilsString::replace($path . '/' . $result, "//", "/"), $output, true);
                }
            } else {
                $output[] = ShaUtilsString::replace($path . '/' . $result, "//", "/");
            }
        }
    }

    /**
     * Create new file
     *
     * @param string $file File path
     *
     * @return void
     */
    public static function createFile($file)
    {
    	$fileInfo = pathinfo($file);
    	
    	if (!is_dir($fileInfo['dirname'])){
    		self::createFolder($fileInfo['dirname']);
    	}
    	if (!file_exists($file)){
    		$fp = fopen($file, 'wb');
    		fclose($fp);
    	}
    }

    /**
     * Write content into file
     *
     * @param string $file File path
     * @param string $content Content to put
     *
     * @return void
     */
    public static function writeContent($file, $content)
    {
        file_put_contents($file, $content);
    }

    /**
     * Delete all old files
     *
     * @param string $path Folder to scan
     * @param int $duration Duration
     *
     * @return void
     */
    public static function clearFilesByAge($path, $duration)
    {
        $limit = mktime(0, 0, 0, date("m"), date("d") - $duration, date("Y"));
        $files = self::getFiles($path);
        foreach ($files as $file) {
            $fileDate = new DateTime(date("F d Y H:i:s.", filemtime($file)));
            $fileDate = getdate(
                mktime(0, 0, 0, $fileDate->format("m"), $fileDate->format("d"), $fileDate->format("Y"))
            );
            if ($fileDate < $limit && is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Create folder and all required sub folder
     */
    public static function createFolder($path){

        $pathPart = explode("/", $path);
        if (count($pathPart) == 1) {
            $pathPart = explode("\\", $path);
        }
        $currenPath = "";
        foreach ($pathPart as $part){
            $currenPath .= $part. "/";
            if (!file_exists($currenPath)){
                mkdir($currenPath, 0755);
            }
        }

    }
    

    /**
     * Copy files
     */
    public static function copyFiles($src, $dst){
    
    	$files = ShaUtilsFile::getFiles($src, true);
    	foreach ($files as $file){
    		$path = dirname($file);
    		$path = ShaUtilsString::replace($path, $src, "");
    		$fileName = basename($file);
    		self::createFolder($dst.$path."/");
    		copy($file, $dst."/".$path."/".$fileName);
    	}
    
    }
    
    /**
     * Delete dir 
     */
    public static function rmDir($dir) {
    	if (is_dir($dir)) {
    		$objects = scandir($dir);
    		foreach ($objects as $object) {
    			if ($object != "." && $object != "..") {
					$name = str_replace("//", "/", $dir."/".$object);
    				if (is_dir($name)) {
						//echo "delete $name\n";
    					self::rmDir($name);
                    } else {
						if (is_file($name)){
							if (unlink ($name)){
								//echo "'$name'\n";
							}
						}
					}
    			}
    		}
    		reset($objects);
    		@rmdir($dir);
    	}
    }
    
    
    /**
     * Reytunr file extension
     * 
     * @param sring $file 
     * 
     * @return string
     */
    public static function getExtension($file){
        $part = explode('.', $file);
        return $part[count($part) - 1];
    }

}

?>
