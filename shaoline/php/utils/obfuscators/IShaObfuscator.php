<?php

interface IShaObfuscator {
	
	public static function getIncludes();
	public function config($script);
	public function pack();
	public static function getExtensions();
	
}