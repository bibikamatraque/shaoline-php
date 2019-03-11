<?php
/**
 * Cms init
 *
 * @category Init
 * @package  Shaoline
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  Shaoline-php copyright
 * @link     No link
 *
 */

//Force PHP setting
ini_set("allow_url_fopen", 1);
ini_set("allow_url_include", true);
ini_set('max_execution_time', 0);
ini_set("memory_limit","512M");

header('Access-Control-Allow-Origin: *');
header('X-Frame-Options: SAMEORIGIN');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST');

// respond to preflights
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'POST') {
	exit;
}

//Load all classes
require_once "autoload.php";

//Load config
ShaContext::getConf()->loadFromYamlFile(__DIR__."/../conf/config.yml");

//Configure bddConnector
ShaContext::init();

//Security
if (ShaContext::rsaActivated()) {
	ShaRsa::updateKeys(
		ShaParameter::get('RSA_KEY_TIMEOUT'),
		ShaParameter::get('RSA_KEY_QTY')
	);
	
	if (!ShaSession::has("rsaKeyId") || ( ShaSession::get("rsaKeyId") == null) ) {
		ShaSession::set("rsaKeyId", ShaRsa::getRandomKey(ShaParameter::get('RSA_KEY_TIMEOUT')));
	} 
}

?>