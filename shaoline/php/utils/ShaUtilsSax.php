<?php



/**
 * SAX lib
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
class ShaUtilsSax
{

	//File to parse
	private $_sPath;
	//Curent deep in file
	private $_iDepth;
	 
	//Functon start for parsing
	private $_fStockedStartFunction;
	//Functon stop for parsing
	private $_fStockedEndFunction;
	//Functon char for parsing
	private $_fStockedCharFunction;
	
	private $_sAvancementFile;
	private $_oAvancementFile;
	private $_iTotalBytes;
	private $_iTotalBytesPurcent;
	private $_iHumanSize;
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this -> depth = 0;
		$this -> _sAvancementFile = "";
	}
	
	/**
	 * Set file to parse
	 *
	 * @param string $sPath XML path
	 *
	 * @return void
	 */
	public function setFile($sPath)
	{
		$this -> _sPath = $sPath;
	}
	
	/**
	 * Inteprete end start
	 * 
	 * @param object $oParser Current parser
	 * @param string $sName   Tag name
	 * @param array  $aAttrs  Tag attributes
	 * 
	 * @return void
	 */
	private function _launchParseStartFunction($oParser, $sName, $aAttrs){
		call_user_func_array($this->_fStockedStartFunction, array($oParser, $sName, $aAttrs));
		$this->_iDepth++;
		if ("" != $this->_sAvancementFile) {
			$iCurrentByte = xml_get_current_byte_index($oParser);
			fwrite($this->_oAvancementFile, basename($this->_sPath). ":" .$iCurrentByte . "/" . $this->_iTotalBytes . "(".$this->_iHumanSize." ".($iCurrentByte/$this->_iTotalBytesPurcent).")\n");
		}
	}
	
	/**
	 * Inteprete end tag
	 *
	 * @param object $oParser Current parser
	 * @param string $sName   Tag name
	 *
	 * @return void
	 */
	private function _launchParseStopFunction($oParser, $sName){
		call_user_func_array($this->_fStockedEndFunction, array($oParser, $sName));
		$this->_iDepth--;
	}
	
	/**
	 * Inteprete tag value
	 *
	 * @param object $oParser Current parser
	 * @param string $aData   Tag value
	 *
	 * @return void
	 */
	private function _launchParseCharFunction($oParser, $aData){
		call_user_func_array($this->_fStockedCharFunction, array($oParser,  $aData));
	}
	
	/**
	 * Print XML
	 * 
	 * @return string
	 */
	public function __toString(){
		$this->_parse("_toStringStart", "_toStringEnd");
	}
	
	/**
	 * Launch specific parsing 
	 * 
	 * @param function $fStartFunction Tag starting interpreter
	 * @param function $fEndFunction   Tag ending interpreter
	 * @param function $fCharFunction  Tag value interpreter
	 * 
	 * @return void
	 */
	public function parse($fStartFunction, $fEndFunction, $fCharFunction){
		$this->_fStockedStartFunction = $fStartFunction;
		$this->_fStockedEndFunction = $fEndFunction;
		$this->_fStockedCharFunction = $fCharFunction;
		$this->_parse("_launchParseStartFunction", "_launchParseStopFunction", "_launchParseCharFunction");
	}

	/**
	 * Parse le fichier et met le resultat dans Result
	 * 
	 * @param function $fStartFunction Tag starting interpreter
	 * @param function $fEndFunction   Tag ending interpreter
	 * @param function $fCharFunction  Tag value interpreter
	 * 
	 * @return void
	 */
	private function _parse($fStartFunction, $fEndFunction, $fCharFunction)
	{
		if ("" != $this->_sAvancementFile) {
			$this->_oAvancementFile = fopen($this->_sAvancementFile, "w"); 	
		}
		
		$oXmlParser = xml_parser_create();
		xml_set_object($oXmlParser, $this);
		xml_set_element_handler($oXmlParser, $fStartFunction, $fEndFunction);
		xml_set_character_data_handler($oXmlParser, $fCharFunction);
		
		$this->_iTotalBytes = filesize($this -> _sPath);
		$this->_iTotalBytesPurcent = $this->_iTotalBytes / 100;
		$this->_iHumanSize = $this->_formatSizeUnits($this->_iTotalBytes);
		
		if (!($oHandle = fopen($this -> _sPath, "r"))) {
			die("could not open XML input");
		}

		while ($data = fread($oHandle, 4096)) {
			if (!xml_parse($oXmlParser, $data, feof($oHandle))) {
				die(
					sprintf(
						"XML error: %s at line %d",
						xml_error_string(xml_get_error_code($oXmlParser)),
						xml_get_current_line_number($oXmlParser)
					)
				);
			}
		}

		fclose($oHandle);
		xml_parser_free($oXmlParser);
		
		if ("" != $this->_sAvancementFile) {
			fclose($this->_oAvancementFile);
		}
	}
	 
	/**
	 * Start function for print function
	 * 
	 * @param object $oParser Current parser
	 * @param string $sName   Tag name
	 * @param array  $aAttrs  Tag attributes
	 * 
	 * @return void
	 */
	private function _toStringStart($oParser, $sName, $aAttrs)
	{
		echo "<$sName";
		for ($i = 0; $i < $this -> depth; $i++) {
			echo "  ";
		}
		$this -> depth++;
		foreach ($aAttrs as $attribute => $text) {
			echo " $attribute='$text' ";
		}
		echo "><br/>";
	}
	 

	/**
	 * Stop function for print function
	 * 
	 * @param object $oParser Current parser
	 * @param string $sName   Tag name
	 *
	 * @return void
	 */
	private function _toStringEnd($oParser, $sName) {
		echo "</$sName>";
		$this -> depth--;
	}
	
	/**
	 * Value function for print function
	 * 
	 * @param object $oParser Current parser
	 * @param string $aData   Tag value
	 * 
	 * @return void
	 */
	private function characterData($oParser, $aData) {
		$data = trim($aData);
		 
		if (strlen($aData) > 0) {
			for ($i = 0; $i < $this -> depth; $i++) {
				echo "  ";
			}

			echo 'T :'.$aData."<br/>";
		}
	}
	
	/**
	 * Set file avancement
	 * 
	 * @param string $sAvancementFile Avancement value
	 * 
	 * @return void
	 */
	public function setAvancementFile($sAvancementFile) {
		$this->_sAvancementFile = $sAvancementFile;
	}
	
	/**
	 * FOrmat size to humain readable data
	 * 
	 * @param int $bytes Bytes qty
	 * 
	 * @return string
	 */
	private function _formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}
	
		return $bytes;
	}
}
?>