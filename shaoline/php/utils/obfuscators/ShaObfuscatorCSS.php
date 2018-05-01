<?php

class ShaObfuscatorCSS implements IShaObfuscator{
	
	private $_script;
	
	public static function getIncludes(){
		return '<link type="text/css" rel="stylesheet" href="[URL]">';
	}
	
	public static function getExtensions(){
		return array("css");
	}
	
	public function config($script) {
		$this->_script = $script;
	}
	
	public function pack() {
		//return $this->_script;
		return $this->_compress($this->_script);
	}
	
	private function _compress($buffer) {
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		return $buffer;
	}
}
?>
