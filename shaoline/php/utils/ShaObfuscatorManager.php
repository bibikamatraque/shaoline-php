<?php

/**
 * Obfuscator manager
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
class ShaObfuscatorManager {
	
	private $_shaObfuscator = null;
	private $_srcPath = "";
	private $_files = array();
	
	/**
	 * Set base path
	 * 
	 * @return ShaJsManager
	 */
	public function setSrcPath($path){
		$this->_srcPath = $path;
		return $this;
	}

	
	/**
	 * Add JS file
	 *
	 * @return ShaJsManager
	 */
	public function addFiles($files){
		foreach ($files as $file){
			$this->addFile($file);
		}
		return $this;
	}
	
	/**
	 * Clear files
	 */
	public function clearFiles(){
		$this->_files = array();
	}
	
	/**
	 * Add JS file
	 * 
	 * @return ShaJsManager
	 */
	public function addFile($file){
		$this->_files[] = $file;
		return $this;
	}
	
	/**
	 * Return include path
	 */
	public function getIncludes(IShaObfuscator $shaObfuscator, $dst) {
		$result = "";
		$include = $shaObfuscator::getIncludes();
		$extensions = $shaObfuscator::getExtensions();
		foreach ($this->_files as $file) {
			$fileInfo = pathinfo($file);
			if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $extensions)){
			    //$file = ShaUtilsString::replace($file, ".css",  ShaPage::getCacheSuffix() . ".css");
			    //$file = ShaUtilsString::replace($file, ".js",  ShaPage::getCacheSuffix() . ".js");
			    $file = ShaUtilsString::replace($file, "/resources/",  '/resources_' . ShaPage::getCacheSuffix() . "/");
				$result .= ShaUtilsString::replace($include, "[URL]", $dst."/".$file).PHP_EOL;
			} else {
			    $result .= $file.PHP_EOL;
			}
		}
	
		return $result;
	}
	
	/**
	 * Minimize and obfuscate code
	 * 
	 * @param IShaObfuscator $shaObfuscator
	 *
	 * @return ShaJsManager
	 */
	public function process(IShaObfuscator $shaObfuscator, $dst, $obfuscate){

		$extensions = $shaObfuscator::getExtensions();
		$fullContent = "";
		
		$length = 0;
        ShaTreatmentInfo::setInfo( 'TOTAL', 'PUSH_RESOURCE', count($this->_files));
        ShaTreatmentInfo::setInfo( 'CURRENT', 'PUSH_RESOURCE', 0);
        foreach ($this->_files as $file) {
			
			$srcFile = $this->_srcPath . $file;
			$fileInfo = pathinfo($srcFile);
					
			if (count($fileInfo) != 4) {
				continue;
			}

			if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $extensions)) {
				
				$content = file_get_contents($srcFile);
				if ($obfuscate) {
				
					$obfuscator = new $shaObfuscator();
					$obfuscator->config($content);
					$newContent = $obfuscator->pack();
					
					$length += strlen($content);
					$fullContent .= $newContent;
				
				} else {
					
					$dstFile = $dst.$file;
					//$dstFile = ShaUtilsString::replace($dstFile, ".css",  ShaPage::getCacheSuffix() . ".css");
					//$dstFile = ShaUtilsString::replace($dstFile, ".js",  ShaPage::getCacheSuffix() . ".js");
					$dstFile = ShaUtilsString::replace($dstFile, "/resources/",  '/resources_' . ShaPage::getCacheSuffix() . "/");
					if (!is_file($dstFile)){ ShaUtilsFile::createFile($dstFile);}
					file_put_contents($dstFile, $content);
					
				}

			}
            ShaTreatmentInfo::incrementInfo('CURRENT', 'PUSH_RESOURCE', 1);
		}

		if ($obfuscate) {
			//ShaUtilsLog::enableEcho();
			ShaUtilsLog::info("Starting obfuscation ... (size: ".strlen($fullContent).") ");
			//$obfuscator = new $shaObfuscator();
			//$obfuscator->config($fullContent);
			if (!is_file($dst)){ ShaUtilsFile::createFile($dst); }
			//$newContent = $obfuscator->pack();
			//file_put_contents($dst, $newContent);
			file_put_contents($dst, $fullContent);
			$onePurcent = $length / 100;
			$purcent =  ($onePurcent == 0) ? 0 : round( strlen($newContent) / $onePurcent, 2 ) ;
			ShaUtilsLog::info("Ending obfuscation ... (size: ".strlen($newContent)." => ".$purcent."%) ");
		}
		
		return $this;
	}

	
}