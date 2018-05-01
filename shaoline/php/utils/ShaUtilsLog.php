<?php
/**
 * Logger class
 * 
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  mon-referendum.com copyright
 * @link     No link
 *
 */
class ShaUtilsLog
{
		
	//Log levels
	const CONST_LOG_LEVEL_NOLOG = 0;
	const CONST_LOG_LEVEL_FATAL = 1;
	const CONST_LOG_LEVEL_ERROR = 2;
	const CONST_LOG_LEVEL_SECURITY = 3;
	const CONST_LOG_LEVEL_WARN = 4;
	const CONST_LOG_LEVEL_INFO = 5;
	const CONST_LOG_LEVEL_VERBOSE = 6; 
	
	private static $_logFolder = null;						//Log folder
	private static $_logFilePrefix = null;					//Log file prefix
	private static $_fullPath = "";							//Full file path
	private static $_logLevel = self::CONST_LOG_LEVEL_NOLOG;	//Current log level
	private static $_echoActived = false;
	

    public static function getHumanLogLevel($logLevel){
        switch ($logLevel){
            case self::CONST_LOG_LEVEL_NOLOG: return "NO_LOG";
            case self::CONST_LOG_LEVEL_FATAL: return "FATAL";
            case self::CONST_LOG_LEVEL_SECURITY: return "SECURITY";
            case self::CONST_LOG_LEVEL_ERROR: return "ERROR";
            case self::CONST_LOG_LEVEL_WARN: return "WARN";
            case self::CONST_LOG_LEVEL_INFO: return "INFO";
            case self::CONST_LOG_LEVEL_VERBOSE: return "VERBOSE";
            default: return "UNKNOWN(".$logLevel.")";
        }
    }


	/**
	 * Configure logger
	 * 
	 * @param string $sLogFolder     Folder path
	 * @param string $sLogFilePrefix File prefix 
	 * @param int    $iLogLevel      Log level
	 * 
	 * @return void
	 * @throws Exception
	 */
	public static function config($sLogFolder, $sLogFilePrefix, $iLogLevel){
		if ( !is_string($sLogFolder) ) {
			throw new Exception("You must define a string type folder path for log file in your function ! : config(\$sLogFolder, \$sLogFilePrefix, \$iLogLevel)");
		}
		if ( !is_string($sLogFilePrefix) || strlen($sLogFilePrefix)<=0 ) {
			throw new Exception("You must define a string type prefix for log file in your function ! : config(\$sLogFolder, \$sLogFilePrefix, \$iLogLevel)");
		}
		if ( !ShaUtilsString::isRegexPositiveInteger($iLogLevel) && $iLogLevel >=0 && $iLogLevel <= 5 ) {
			throw new Exception("You must define a int type log level : config(\$sLogFolder, \$sLogFilePrefix, \$iLogLevel)");
		}
		self::$_logFolder = $sLogFolder;
		self::$_logFilePrefix = $sLogFilePrefix;
		self::$_logLevel = $iLogLevel;
		if (substr(self::$_logFolder, -1)!="/" && substr(self::$_logFilePrefix, 0)!="/") {
			self::$_logFolder.="/";
		}
		self::$_fullPath = self::$_logFolder.self::$_logFilePrefix;
	}
	

	/**
	 * Configure logger if not already configured
	 * 
	 * @param string $sLogFolder     Folder path
	 * @param string $sLogFilePrefix File prefix 
	 * @param int    $iLogLevel      Log level
	 * 
	 * @return void
	 * @throws Exception
	 */
	public static function configIfNot($sLogFolder, $sLogFilePrefix, $iLogLevel){
		if (!self::_checkConfig()) {
			//echo "New log configuration configIfNot($sLogFolder, $sLogFilePrefix, $iLogLevel) ...".cr();
			self::config($sLogFolder, $sLogFilePrefix, $iLogLevel);
		}
	}
	
	/**
	 * Check if logger configuration is good
	 * 
	 * @return boolean
	 */
	private static function _checkConfig(){
		if (!isset(self::$_logFolder)) {
			echo "No path defined for log file !".cr();
			return false;
		}
		if (!isset(self::$_logFilePrefix)) {
			echo "No prefix defined for log file !".cr();
			return false;
		}
		return true;
	}
	
	/**
	 * enable 'echo' when loging
	 * 
	 * @return void
	 */
	public static function enableEcho(){
		self::$_echoActived = true;		
	}
	
	/**
	 * disable 'echo' when loging
	 * 
	 * @return void
	 */
	public static function diableEcho(){
		self::$_echoActived = true;
	}
	
	/**
	 * Test if an element is object (or array) and convert to string
	 *  
	 * @param variant $vObj Element to test and convert to string
	 * 
	 * @return string
	 */
	private static function _testIfObjAndConvertToString($vObj){
		$sResult=$vObj;
		if (is_object($vObj) || is_array($vObj)) {
			ob_start();
			var_dump($vObj);
			$sResult = ob_get_clean();
		}
		return $sResult;
	}
	
	/**
	 * Write msg in logger
	 *
	 * @param string $sMsg      Message to log
	 * @param int    $iLogLevel Log level
	 *
	 * @return void
	 */
	private static function _internalLog($sMsg, $iLogLevel){
		//Check config
		if (self::_checkConfig()) {
		
			$login = "UNREGISTRED|";
			if (ShaContext::getUser() != null){
				$login = (ShaContext::getUser()->isAuthentified()) ? ShaContext::getUser()->getValue("user_login")."|" : "UNREGISTRED|";	
			}
			
			$sMsg = Date("Y-m-d H:i:s")."|".self::getHumanLogLevel($iLogLevel)."|".ShaContext::getClientIp()."|".$login.$sMsg.PHP_EOL;

			//Check level
			if ($iLogLevel <= self::$_logLevel) {
				if (self::$_echoActived) {
					echo $sMsg;
				}

				$filePath = self::$_fullPath .'_'.Date("Y_m_d");
				ShaUtilsFile::createFile($filePath);
				error_log($sMsg, 3, $filePath);
			}
			
		}
	}
	
	/**
	 * Write msg in logger
	 *
	 * @param string $sMsg      Message to log
	 * @param int    $iLogLevel Log level
	 *
	 * @return void
	 */
	public static function log($sMsg, $iLogLevel){
		$sMsg = self::_testIfObjAndConvertToString($sMsg);
		self::_internalLog($sMsg, $iLogLevel);
	}
	
	/**
	 * Write msg in logger and add a EOL
	 *
	 * @param string $sMsg      Message to log
	 * @param int    $iLogLevel Log level
	 *
	 * @return void
	 */
	/*public static function logLn($sMsg, $iLogLevel){
		$sMsg = self::_testIfObjAndConvertToString($sMsg);
		self::_internalLog($sMsg.PHP_EOL, $iLogLevel);
	}*/
	
	/**
	 * Write fatal level msg in logger 
	 * 
	 * @param string $sMsg Message to log
	 * 
	 * @return void
	 */
	public static function fatal($sMsg){
		self::log($sMsg, self::CONST_LOG_LEVEL_FATAL);
	}
	
	
	/**
	 * Write fatal level msg in logger and add a EOL
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	/*public static function fatalLn($sMsg){
		self::logLn($sMsg, self::CONST_LOG_LEVEL_ERROR);
	}*/
	
	/**
	 * Write fatal level msg in logger
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	public static function error($sMsg){
		self::log($sMsg, self::CONST_LOG_LEVEL_ERROR);
	}
	
	/**
	 * Write fatal level msg in logger and add a EOL
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	/*public static function errorLn($sMsg){
		self::logLn($sMsg, self::CONST_LOG_LEVEL_ERROR);
	}*/
	

	/**
	 * Write security level msg in logger
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	public static function security($sMsg){
		self::log($sMsg, self::CONST_LOG_LEVEL_SECURITY);
	}
	
	/**
	 * Write security level msg in logger and add a EOL
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	/*public static function securityLn($sMsg){
	 self::logLn($sMsg, self::CONST_LOG_LEVEL_SECURITY);
	 }*/
	/**
	 * Write fatal level msg in logger
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	public static function warn($sMsg){
		self::log($sMsg, self::CONST_LOG_LEVEL_WARN);
	}
	
	/**
	 * Write fatal level msg in logger and add a EOL
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	/*public static function warnLn($sMsg){
		self::logLn($sMsg, self::CONST_LOG_LEVEL_WARN);
	}*/
	
	
	/**
	 * Write fatal level msg in logger
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	public static function info($sMsg){
		self::log($sMsg, self::CONST_LOG_LEVEL_INFO);
	}
	
	/**
	 * Write fatal level msg in logger and add a EOL
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	/*public static function infoLn($sMsg){
		self::logLn($sMsg, self::CONST_LOG_LEVEL_INFO);
	}*/
	
	
	/**
	 * Write fatal level msg in logger
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	public static function verbose($sMsg){
		self::log($sMsg, self::CONST_LOG_LEVEL_VERBOSE);
	}
	
	/**
	 * Write fatal level msg in logger and add a EOL
	 *
	 * @param string $sMsg Message to log
	 *
	 * @return void
	 */
	/*public static function verboseLn($sMsg){
		self::logLn($sMsg, self::CONST_LOG_LEVEL_VERBOSE);
	}*/

	

	/**
	 * Return daily logs file and stats
	 *
	 * @return array(
	 * 	'log' 		=> log content
	 *  'size' 		=> log size
	 *  'qtyLines' 	=> Qty lines
	 *  'qtyDebug'	=> Qty of DEBUG LINES
	 *  'qtyInfo'	=> Qty of INFO LINES
	 *  'qtyWarn'	=> Qty of WARN LINES
	 *  'qtyError'	=> Qty of ERROR LINES
	 *  'qtyFatal'	=> Qty of FATAL LINES
	 * )
	 */
	public static function getDailyLogs(){
		$file = ShaContext::getConf()->get("env/log_path").'/log_'.Date("Y_m_d");
		$content = file_get_contents($file);
		$size = strlen($content);
		$lines = substr_count($content, '\n');
	
		$debug = substr_count($content, 'DEBUG');
		$info = substr_count($content, 'INFO');
		$warn = substr_count($content, 'WARN');
		$error = substr_count($content, 'ERROR');
		$fatal = substr_count($content, 'FATAL');
	
		return array(
			'log' 		=> $content,
			'size' 		=> $size,
			'qtyLines' 	=> $lines,
			'qtyDebug'	=> $debug,
			'qtyInfo'	=> $info,
			'qtyWarn'	=> $warn,
			'qtyError'	=> $error,
			'qtyFatal'	=> $fatal
		);
	
	}
	
	/**
	 * Return log file list
	 */
	public static function showLogFilesGcId(){
		$showLogDetail = new ShaOperationAction();
		$showLogDetail
		->setDaoClass("ShaUtilsLog")
		->setDaoMethod("showLogFiles")
		->save()
		;
		return $showLogDetail->getDomEvent();
	}
	
	/**
	 * Return log file list
	 */
	public static function showLogFiles(){
	
		$html = "<ul class='cmsLogList'>";
		$logPath = ShaContext::getConf()->get("env/log_path");
		$files = ShaUtilsFile::getFiles($logPath, false);
		foreach ($files as $file) {
	
			$file = ShaUtilsString::replace($file, $logPath, "");
			$showLogDetail = new ShaOperationAction();
			$showLogDetail
				->setDaoClass("ShaUtilsLog")
				->setDaoMethod("displayLogDetail")
				->setParameters(
					array('file' => $file)
				)
				->save()
			;
			if (strlen($file) > 4 && substr($file, 0, 4) == "log_"){
				$html .= "<li ".$showLogDetail->getDomEvent().">".$file."</li>";
			}
		}
		$html .= "</ul>";
	
		
		$response = new ShaResponse();
		$response
			->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
			->setContent($html)
			->getPopin()
				->setTitle("Log")
				->setColor("blue")
		;
		$response->render();
		return;		
	}
	
	public static function displayLogDetail($params) {
		
		$html = "<ul class='cmsLogList'>";
		
		if (!is_array($params)  || !isset($params['file'])){
			ShaResponse::inlinePopinResponse("Bad param in 'displayLogDetail'", 'Error', 'red')->render();
			return;
		}
		
		$file = ShaContext::getConf()->get("env/log_path").'/'.$params['file'];
		if (!is_file($file)){
			ShaResponse::inlinePopinResponse("File not found '".$file."'", 'Error', 'red')->render();
			return;
		}
		$content = file_get_contents($file); 
		$lines = explode("\n", $content);	
		foreach ($lines as $line) {
			if (strpos($line, "|FATAL|") !== false){
				$class = "CmsFatalLogLine";
			}
			if (strpos($line, "|ERROR|") !== false){
				$class = "CmsErrorLogLine";
			}
			if (strpos($line, "|SECURITY|") !== false){
				$class = "CmsSecurityLogLine";
			}
			if (strpos($line, "|WARN|") !== false){
				$class = "CmsWarnLogLine";
			}
			if (strpos($line, "|INFO|") !== false){
				$class = "CmsInfoLogLine";
			}
			if (strpos($line, "|DEBUG|") !== false){
				$class = "CmsDebugLogLine";
			}
			$html .= "<li class='$class'>".$line."</li>";
		}
		
		$html .= "<ul>";
		
		if (count($lines) == 0){
			$html .= ShaContext::t("No log for this periode");
		}
		
		$response = new ShaResponse();
		$response
			->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
			->setContent($html)
			->getPopin()
			->setTitle("Log")
			->setColor("blue")
		;
		$response->render();
		return;
		
	}
	
}