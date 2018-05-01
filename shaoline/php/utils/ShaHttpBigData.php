<?php

namespace Fnp\BackendBundle\Utils;

/**
 * <Description>
 * 
 * 	Use this class to manage multi query post construction (for big data)
 *  You can put more than one big data post field 
 * 
 * </Description>
 * 
 * <Api>
 * 
 * 		- JSON read($dataJson); => Read JSON input, save data if it is http big data, return data if transmition finished
 * 
 * </Api>
 * 
 * <Sample>
 * 
 * 		$dataJson = { 
 * 			'a' : 1, 
 * 			'b' : 2, 
 * 			'c' : {
 * 				'puid'	: 'THIS_IS_AN_UNIC_ID_1', 
 * 				'ppi'	: 0, 
 * 				'ppc'	: 2, 
 * 				'ppd' 	: '012345'
 * 			},
 * 			'd' : {
 * 				'puid'	: 'THIS_IS_AN_UNIC_ID_2', 
 * 				'ppi'	: 0, 
 * 				'ppc'	: 3, 
 * 				'ppd' 	: 'abcdefghij'
 * 			},
 * 		}
 * 		read($dataJson) => null
 * 
 * 		$dataJson = { 
 * 			'a' : 1, 
 * 			'b' : 2, 
 * 			'c' : {
 * 				'puid'	: 'THIS_IS_AN_UNIC_ID_1', 
 * 				'ppi'	: 1, 
 * 				'ppc'	: 2, 
 * 				'ppd' 	: '64789'
 * 			},
 * 			'd' : {
 * 				'puid'	: 'THIS_IS_AN_UNIC_ID_2', 
 * 				'ppi'	: 1, 
 * 				'ppc'	: 3, 
 * 				'ppd' 	: 'klmnopqrst'
 * 			},
 * 		}
 * 		read($dataJson) => null
 * 	
 * 		$dataJson = { 
 * 			'a' : 1, 
 * 			'b' : 2, 
 * 			'c' : {
 * 				'puid'	: 'THIS_IS_AN_UNIC_ID_1', 
 * 				'ppi'	: 0, 
 * 				'ppc'	: 0, 
 * 				'ppd' 	: ''
 * 			},
 * 			'd' : {
 * 				'puid'	: 'THIS_IS_AN_UNIC_ID_2', 
 * 				'ppi'	: 2, 
 * 				'ppc'	: 3, 
 * 				'ppd' 	: 'uvwxyz'
 * 			},
 * 		}
 * 		read($dataJson) => { 'a' : 1, 'b' : 2, 'c' : '0123456789', 'd' : 'abcdefghijklmnopqrstuvwxyz'}}
 * 
 * </Sample>
 * 
 * <Versions>
 * 
 * 	...
 * 	1.0.0	|	2015-08-20	|	Creation	|	Bastien Duhot <bastien.duhot@free.fr>
 * 	...
 * 
 * </Versions>
 */
class ShaHttpBigData {
	
	const RESULT_OK 				= 0;
	const RESULT_TOO_BIG_PART 		= 1;
	const RESULT_IO_ERROR 			= 2;
	const RESULT_BAD_PARAMETER 		= 3;
	
	const POST_UNIC_ID 		= "puid";
	const POST_PART_INDEX 	= "ppi";
	const POST_PART_COUNT 	= "ppc";
	const POST_PART_DATA	= "ppd";
	
	private $_postUnicId;		// Post unic ID (PUID)
	private $_postPartIndex;	// Post part index
	private $_postPartCount;	// Qty of part
	private $_filesPath;		// Path to tmp files
	private $_sizeLimit;		// Max part size
	
	/**
	 * Constructor
	 * 
	 * @param string $filesPath Path to save tmp files
	 */
	public function ShaHttpBigData($filesPath, $sizeLimit) {
		$this->_filesPath 	= $filesPath;
		$this->_sizeLimit 	= $sizeLimit;
	}
	
	/**
	 * Check if it is bi data bpost part
	 * 
	 * @param JSON $dataJson
	 * 
	 * @return bool
	 */
	private function _isShaHttpBigData($dataJson) {
		return (
			isset(property_exists($dataJson, self::POST_ID)) 			&&
			isset(property_exists($dataJson, self::POST_PART_INDEX)) 	&&
			isset(property_exists($dataJson, self::POST_PART_COUNT)) 	&&
			isset(property_exists($dataJson, self::POST_PART_DATA))
		);
	}
		
	/**
	 * Send data with big part  to URL using POST or GET methods
	 * If field value is too big (> $maxPartSize), send it in multi time
	 * 
	 * @param string $url		: URL to contact
	 * @param array  $data		: Data to send
	 * @param int 	 $size		: Size
	 * @param string $method	: Methode used
	 * 
	 * @return int : Error code
	 */
	public function write($url, $data, $size, $method = "POST") {
		
		if (!is_array($data)){
			return BAD_PARAMETER;
		}
		
		$json = json_encode($data);
		
		$partProgress = array();
		$finsih = true;
		while ($finish) {
			
			$tmpPost = array();
			
			//For each JSON attributes
			foreach ($attributes as $key => $value) {
					
				
					
				if (!is_object($value) && !is_array($value) && strlen($value) > $size){
					if (isset($partProgress[$key])){
						
					}
				} else {
					$tmpPost[$key] = $value;
				}
					
			}
		}
		
		
	}
	
	/**
	 * 
	 * Read data, save big post part and return null or return final json if all pat are recovery
	 * 
	 * @param JSON $dataJson
	 * 
	 * @return JSON | NULL
	 */
	public function read($dataJson) {
		
		$qtyBigDataField = 0;
		$qtyBigDataFieldFilled = 0;
		$attributes = get_object_vars($dataJson);
		
		//For each JSON attributes
		foreach ($attributes as $key => $value) {

			//If not big data part do nothing
			if (!$this->isFnpHttpBigData($value)) {
				continue;
			}
			
			$qtyBigDataField++;
			
			$this->_postUnicId		= $dataJson[self::PAGE_ID];
			$this->_postPartIndex 	= $dataJson[self::PAGE_INDEX];
			$this->_postPartCount 	= $dataJson[self::POST_PART_DATA];
			
			//Open files
			$filename = $this->_filesPath. "puid_" . $this->_postUnicId . "_" . $key;
			$fileHandle = fopen($filename, "a+");
			if (!file_exists($filename)) {
				return RESULT_IO_ERROR; 
			}
			
			//Check part limit
			$size = filesize($filename);
			if ( ($size + strlen($dataJson[self::POST_DATA])) > $this->_sizeLimit) {
				return RESULT_TOO_BIG_PART;
			}
			
			//Save part
			fwrite($fileHandle, $dataJson[self::POST_DATA]);
			
			//Close file
			fclose($fileHandle);
			
			//Check if all data found for this part
			if ($this->_postPartIndex == $this->_postPartCount) {
				$qtyBigDataFieldFilled++;
				$dataJson[$key] = file_get_contents($filename);
			}
		}
		
		//All part found
		if ($qtyBigDataField == $qtyBigDataFieldFilled) {
			
			$filename = $this->_filesPath. "puid_" . $this->_postUnicId . "_" . $key;
			foreach ($attributes as $key => $value) {
				if (file_exists($filename)){
					unlink( $filename );
				}
			}
			
			return $dataJson;
		} 
		
		//All part not still found
		return null;
	}
	
}

?>