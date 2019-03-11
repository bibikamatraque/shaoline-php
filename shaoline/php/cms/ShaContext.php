<?php

/**
 * Global Cms functions
 *
 * @category   Class
 * @package    Core
 * @subpackage Core
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @link       No link
 *
 */
class ShaContext
{

    //private static function getObfuscatedAdminCssFile() { return 'shaoline/resources/css/shaoline.admin.min' . ShaPage::getCacheSuffix() . '.css'; }
    private static function getObfuscatedAdminCssFile() { return 'shaoline/resources_' .  ShaPage::getCacheSuffix() . '/css/shaoline.admin.min.css'; }
    private static $_adminCssFiles = array(
        'shaoline/resources/css/reset.css',
        'shaoline/resources/css/desktop.css',
        'shaoline/resources/css/utils.css',
        'shaoline/resources/css/cms.css',
        'shaoline/resources/css/menu.css',
        'shaoline/resources/css/windows.css',
        'shaoline/resources/css/list.css',
        'shaoline/resources/css/formulaire.css',
        'shaoline/resources/css/tree.css',
        'shaoline/resources/utils/jquery/css/jquery-ui-1.10.3.custom.min.css',
        'shaoline/resources/utils/jquery/css/jquery.treeview.css',
        'shaoline/resources/utils/jquery/css/jquery-picklist.css',
        'shaoline/resources/utils/jquery/css/jquery.jqplot.css'
    );
	
	//private static function getObfuscatedCssFile() { return 'shaoline/resources/css/shaoline.min' . ShaPage::getCacheSuffix() . '.css'; }
    private static function getObfuscatedCssFile() { return 'shaoline/resources_' .  ShaPage::getCacheSuffix() . '/css/shaoline.min.css'; }
	private static $_cssFiles = array(
	    'shaoline/resources/css/reset.css',
	    'shaoline/resources/css/utils.css',
	    'shaoline/resources/css/cms.css',
	    'shaoline/resources/css/menu.css',
	    'shaoline/resources/css/windows.css',
	    'shaoline/resources/utils/jquery/css/jquery-ui-1.10.3.custom.min.css',
	    'shaoline/resources/utils/jquery/css/jquery.treeview.css',
	    'shaoline/resources/utils/jquery/css/jquery-picklist.css',
	    'shaoline/resources/utils/jquery/css/jquery.jqplot.css'
	);
	
	//private static function getObfuscatedAdminJsFile() { return 'shaoline/resources/js/shaoline.admin.min' . ShaPage::getCacheSuffix() . '.js'; }
	private static function getObfuscatedAdminJsFile() { return 'shaoline/resources_' .  ShaPage::getCacheSuffix() . '/js/shaoline.admin.min.js'; }
	private static $_adminJsFiles = array(
	    
	    'shaoline/resources/utils/rsa/jsbn.js',
	    'shaoline/resources/utils/rsa/rsa.js',
	    //'shaoline/resources/utils/rsa/sha1.js',
	    'shaoline/resources/utils/jquery/js/jquery-1.11.1.min.js',
	    'shaoline/resources/utils/jquery/js/jcycle.pack.js',
	    'shaoline/resources/utils/jquery/js/jcarousel-lite.js',
	    'shaoline/resources/utils/jquery/js/jquery-asynchrone-upload.js',
	    'shaoline/resources/utils/jquery/js/jquery.ui-1.11.2.js',
	    'shaoline/resources/utils/jquery/js/jquery.treeview.js',
	    'shaoline/resources/utils/jquery/js/jquery.cookie.js',
	    'shaoline/resources/utils/jquery/js/jquery.ui.widget.min.js',
	    'shaoline/resources/utils/jquery/js/jquery-picklist.min.js',
	    'shaoline/resources/utils/jquery/js/jquery.noconflict.js',
	    'shaoline/resources/js/utils/UtilsJquery.js',
	    'shaoline/resources/js/utils/UtilsString.js',
	    'shaoline/resources/js/utils/UtilsNetwork.js',
	    'shaoline/resources/js/utils/UtilsForm.js',
	    'shaoline/resources/js/utils/UtilsMenu.js',
	    'shaoline/resources/js/utils/UtilsRegex.js',
	    'shaoline/resources/js/utils/UtilsStyle.js',
	    'shaoline/resources/js/utils/Utils3D.js',
	    'shaoline/resources/js/utils/UtilsWindow.js',
	    'shaoline/resources/js/utils/UtilsAjax.js',
	    'shaoline/resources/js/Shaoline.js',
	    'shaoline/resources/utils/google/jsapi.js',
	    'shaoline/resources/utils/google/google.js',
	    'shaoline/resources/utils/jquery/js/jquery.jqplot.min.js',
	    'shaoline/resources/utils/jquery/js/jqplot.dateAxisRenderer.min.js',
	    'shaoline/resources/utils/jquery/js/jqplot.highlighter.min.js',
	    'shaoline/resources/utils/jquery/js/jqplot.pieRenderer.min.js',
	    'shaoline/resources/utils/jquery/js/jqplot.categoryAxisRenderer.min.js',
	    'shaoline/resources/utils/jquery/js/jqplot.barRenderer.min.js',
	    'shaoline/resources/utils/wbgl/three.min.js'
	);
	
	//private static function getObfuscatedJsFile() { return 'shaoline/resources/js/shaoline.min' . ShaPage::getCacheSuffix() . '.js'; }
	private static function getObfuscatedJsFile() { return 'shaoline/resources_' .  ShaPage::getCacheSuffix() . '/js/shaoline.min.js'; }
	private static $_jsFiles = array(
			
			'shaoline/resources/utils/rsa/jsbn.js',
			'shaoline/resources/utils/rsa/rsa.js',
			//'shaoline/resources/utils/rsa/sha1.js',
			'shaoline/resources/utils/jquery/js/jquery-1.11.1.min.js',
			'shaoline/resources/utils/jquery/js/jcycle.pack.js',
			'shaoline/resources/utils/jquery/js/jcarousel-lite.js',
			'shaoline/resources/utils/jquery/js/jquery-asynchrone-upload.js',
			'shaoline/resources/utils/jquery/js/jquery.ui-1.11.2.js',
			'shaoline/resources/utils/jquery/js/jquery.treeview.js',
			'shaoline/resources/utils/jquery/js/jquery.cookie.js',
			'shaoline/resources/utils/jquery/js/jquery.ui.widget.min.js',
			'shaoline/resources/utils/jquery/js/jquery-picklist.min.js',
			'shaoline/resources/utils/jquery/js/jquery.noconflict.js',
			'shaoline/resources/js/utils/UtilsJquery.js',
			'shaoline/resources/js/utils/UtilsString.js',
			'shaoline/resources/js/utils/UtilsNetwork.js',
			'shaoline/resources/js/utils/UtilsForm.js',
			'shaoline/resources/js/utils/UtilsMenu.js',
			'shaoline/resources/js/utils/UtilsRegex.js',
			'shaoline/resources/js/utils/UtilsStyle.js',
			'shaoline/resources/js/utils/Utils3D.js',
			'shaoline/resources/js/utils/UtilsWindow.js',
			'shaoline/resources/js/utils/UtilsAjax.js',
			'shaoline/resources/js/Shaoline.js',
			'shaoline/resources/utils/google/jsapi.js',
			'shaoline/resources/utils/google/google.js',
			'shaoline/resources/utils/jquery/js/jquery.jqplot.min.js',
			'shaoline/resources/utils/jquery/js/jqplot.dateAxisRenderer.min.js',
			'shaoline/resources/utils/jquery/js/jqplot.highlighter.min.js',
			'shaoline/resources/utils/jquery/js/jqplot.pieRenderer.min.js',
			'shaoline/resources/utils/jquery/js/jqplot.categoryAxisRenderer.min.js',
			'shaoline/resources/utils/jquery/js/jqplot.barRenderer.min.js'
	);
		
    //Default no picture URL
    public static function getConstantNoPicture() {
        return "shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/no_picture.png";
    }
    
    /** @type ShaConfiguration $_configuration */
    private static $_configuration = null;
    /** @type IBddConnector $_orm */
    private static $_bddConnector = null;
    /** @type  ShaTranslation $_translator */
    private static $_translator = null;
    /** @type ShaContent $_content */
    private static $_content = null;
    /** @type ShaLanguage $_language */
    private static $_language = 1;
    /** @type int $_contentId */
    private static $_contentId = 0;
    /** @var ShaUser $_currentUser */
    private static $_currentUser = null;
    /** @var bool $_rsaActivated */
    private static $_rsaActivated = null;


    /**
     * Return site absolute base path
     *
     * @return string
     */
    public static function getPathToBase(){
        return __DIR__."/../../../";
    }

    /**
     * Return site absolute base path
     *
     * @return string
     */
    public static function getPathToShaoline(){
        return self::getPathToBase()."shaoline/";
    }

    /**
     * Return site absolute base path
     *
     * @return string
     */
    public static function getPathToPlugins(){
        return self::getPathToBase()."plugins/";
    }

    /**
     * Return cms absolute path
     *
     * @return string
     */
    public static function getPathToCms(){
        return self::getPathToShaoline()."php/cms/";
    }

    /**
     * Return fwk absolute path
     *
     * @return string
     */
    public static function getPathToFwk(){
        return self::getPathToShaoline()."php/fwk/";
    }

    /**
     * Return fwk absolute path
     *
     * @return string
     */
    public static function getPathToUtils(){
        return self::getPathToShaoline()."php/utils/";
    }

    /**
     * Return componanets absolute path
     *
     * @return string
     */
    public static function getPathToComponents(){
        return self::getPathToCms()."components/";
    }

    /**
     * Return resources absolute path
     *
     * @return string
     */
    public static function getPathToResources(){
        return self::getPathToShaoline()."resources/";
    }

    /**
     * Return basic template absolute path
     *
     * @return string
     */
    public static function getPathToTemplates(){
        return self::getPathToBase()."php/templates/";
    }


    /**
     * Return web absolute path
     *
     * @return string
     */
    public static function getPathToWeb(){
        return self::getPathToBase()."web/";
    }


    /**
     * Return context configuration
     *
     * @return ShaConfiguration
     */
    public static function getConf() {
        if (!isset(self::$_configuration)) {
            self::$_configuration = new ShaConfiguration();
        }
        return self::$_configuration;
    }

    /**
     * Configure starting datas
     */
    public static function init() {

        if (self::$_configuration->get("env/apache_log") == "all"){
            ini_set("error_reporting", E_ALL);
        }

        //Init logger
        ShaUtilsLog::config(self::$_configuration->get("env/log_path"), 'log', ShaUtilsLog::CONST_LOG_LEVEL_INFO);
        
        //INIT DATA BASE
        $bddType = self::$_configuration->get("bdd/type");
        $bddHost = self::$_configuration->get("bdd/host");
        $bddPort = self::$_configuration->get("bdd/port");
        $bddLogin = self::$_configuration->get("bdd/login");
        $bddPwd = self::$_configuration->get("bdd/password");
        $bddBase = self::$_configuration->get("bdd/base");

        if (!isset($bddType)) { die("Fatal error : please define => bdd/type"); }
        if (!isset($bddHost)) { die("Fatal error : please define => bdd/host"); }
        if (!isset($bddPort)) { die("Fatal error : please define => bdd/port"); }
        if (!isset($bddLogin)) { die("Fatal error : please define => bdd/login"); }
        if (!isset($bddPwd)) { die("Fatal error : please define => bdd/password"); }
        if (!isset($bddBase)) { die("Fatal error : please define => bdd/base"); }
        if (!class_exists($bddType)) { die("Fatal error : bdd type not found : '$bddType'"); }

        if (!isset(self::$_bddConnector)) {
            self::$_bddConnector = new $bddType();
        }
        self::$_bddConnector->setHost($bddHost);
        self::$_bddConnector->setPort($bddPort);
        self::$_bddConnector->setLogin($bddLogin);
        self::$_bddConnector->setPassword($bddPwd);
        self::$_bddConnector->setBddName($bddBase);

        self::$_rsaActivated = self::$_configuration->get("security/rsa") == '1';
		
        //INIT ORM
        ShaOrm::setBddConnector(self::$_bddConnector);
        
        //LOAD ALL PLUGINS
        $plugins = self::$_configuration->get("plugins");
        if (is_array($plugins)) {
            foreach ($plugins as $plugin) {
                $path = "/../../../plugins/".$plugin.".php";
                if (file_exists($path)) {
                    ShaUtilsLog::fatal("Plugin main file not found (".$path.") !");
                    throw new Exception(ShaContext::t("Fatal error"));
                }
                require_once __DIR__.$path;
            };
        }

        if (isset($GLOBALS['install']) && $GLOBALS['install']){
            self::_clearAllDatabase();
            self::_setup();
            self::_setupMissingPlugins();
            header('Location: ' . self::getAdminPath().'.php');
            exit;			
        }
		
        //IF ADMIN PAGE, INSTALL MISSING COMPONENTS AND PLUGINS
        if (self::_isAdminPage()) {

            self::_setup();
            self::_setupMissingPlugins();
            
        } else {
            //IF NOT ADMIN PAGE AND NOT INSTALLED THEN GOTO ADMIN PAGE
            if (!self::_isInstalled()) {
                header('Location: '.self::getAdminPath().'.php');
                exit;
            }
        }

        //INSTANTIATE TRANSLATOR ENGINE
        self::_getTranslatorInstance();

        //PREPARE SESSION
        self::$_contentId = ShaSession::get("cms_content_id", 0);

        //CHECK NECESSARY DATAS
        if (!self::getConf()->has("site/url")) { die("Fatal error : site/url  not defined in config.yml"); }
        if (!self::getConf()->has("site/protocol")) { die("Fatal error : site/protocol  not defined in config.yml"); }

        //LOAD OR INIT CURRENT LANGUAGE
        self::setLanguage(1);
        if (ShaSession::has("cms_current_language_id")) {
            self::setLanguage(ShaSession::get("cms_current_language_id"));
        }

        //Try connect using magic key
        self::$_currentUser = new ShaUser();

        //Get all additional GET pramaeters
        ShaPage::regenerateGets();
        
        //Try connected using magic key
        self::$_currentUser->tryMagic();

        //LOAD OR INIT CURRENT USER AND CURRENT LANGUAGE
        if (ShaSession::has("cms_current_user_id")) {
            if (!self::$_currentUser->load(ShaSession::get("cms_current_user_id"))) {
                ShaSession::set("cms_current_user_id", null);
                self::$_currentUser = new ShaUser();
            } else {
                self::$_currentUser->setAuthentified(true);
            }
        }

        //LOAD ALL PLUGINS
        if (is_array($plugins)) {
            foreach ($plugins as $plugin) {
                if (method_exists ($plugin , "init" )){
                    $plugin::init();
                }
            };
        }
        
        //INIT GC TIMOUT
        ShaGarbageCollector::setTimeout(ShaParameter::get("GC_TIMEOUT"));

    }

	/**
	 * Clear all database 
	 */
	private static function _clearAllDatabase(){
		$bddBase = self::$_configuration->get("bdd/base");
		$query = "SELECT * FROM information_schema.referential_constraints WHERE constraint_schema = '" . $bddBase . "'";
		$oRecordset = ShaContext::bddSelect($query);
		while ($oRecord = $oRecordset->fetchAssoc()) {
                    self::bddExecute('ALTER TABLE "' . $oRecord['TABLE_NAME'] . '" DROP [CONSTRAINT|INDEX] "' . $oRecord['CONSTRAINT_NAME'] . '";');
		}	
		$oRecordset->close();
		
		$oRecordset = ShaContext::bddSelect('SHOW TABLES');
		while ($aRecord = $oRecordset->fetchArray()) {
                    self::bddExecute('DROP TABLE ' . $aRecord[0]);
		}	
		$oRecordset->close();
	}
	
	/**
	 * Return true of RSA is activated
	 */
	public static function rsaActivated(){
		return self::$_rsaActivated;
	}
	
    /**
     * Update user id in session
     */
    public static function updateUserId() {
        ShaSession::set("cms_current_user_id", self::getUser()->getValue("user_id"));
    }

    /**
     * Return current user
     *
     * @return ShaUser
     */
    public static function getUser() {
        return self::$_currentUser;
    }

	/**
	 * Return the backoffice file path
	 *
	 * @return string
	 */
	public static function getAdminPath(){
		return self::$_configuration->get("site/backoffice");
	}
	
    /**
     * Return true if current page is admin page
     *
     * @return bool
     */
    private static function _isAdminPage() {
        if (isset($_SERVER['REQUEST_URI'])) {
            $adminPageName = strtolower(self::getAdminPath().'.php');
            $adminPageNameSize = strlen($adminPageName);
            $url = strtolower(ShaUtilsString::noSpace($_SERVER['REQUEST_URI']));
            return (substr($url, strlen($url)-$adminPageNameSize, strlen($url)-1)==$adminPageName);
        }
        return false;
    }

    /**
     * Return translator object
     *
     * @return ShaTranslation
     * @throws Exception
     */
    private static function _getTranslatorInstance() {
        if (!isset(self::$_translator)) {
            if (!class_exists("ShaTranslation")) {
                throw new Exception("Fatal error : 'ShaTranslation' class not found and translator not defined ! Please contact your administrator !");
            }
            self::$_translator = new ShaTranslation();
        }
        return self::$_translator;
    }

    /**
     * Initalisation of all Cms object
     *
     * @return void
     */
    private static function _setupMissingPlugins() {

        //Load all others components
        $changing = false;
        $plugins = self::$_configuration->get("plugins");
        if (is_array($plugins)) {
            foreach ($plugins as $plugin) {
                /** @var ShaPlugin $obj */
                $obj = new $plugin();
                if ($obj->setup()){
                	$changing = true;
                }
                unset($obj);
            }
        }
        if ($changing){
        	ShaContext::updateWeb();
        	ShaPage::rebuildHtaccess();
        }

    }


    /**
     * Setup basic requierement
     *
     * @return void
     */
    private static function _setup() {

        if (!self::_isInstalled()) {

            /** @var ShaCmo $obj */
            $obj = new ShaPermission();unset($obj);
            $obj = new ShaLanguage();unset($obj);
            $obj = new ShaGroupPermission();unset($obj);

            //Instanciate each cms components
            self::instanciateAllCmoInFolder(__DIR__."/components");

            //Init datas
            ShaHelper::add()
                ->user("root", "12345", 1, 1)

                ->group('admin','Admin group got all right')
                ->group('webmaster','Webmaster group')
                ->group('user','Website user')

                ->userGroup('root', 'admin')

                //TODO : bad folder
                //->language(1,'French','shaoline/resources_" . ShaPage::getCacheSuffix() . /img/flags/FR.png','FR','fr-fr', '', 1)
                ->language(1,'English','shaoline/resources_" . ShaPage::getCacheSuffix() . /img/flags/GB.png','EN','en-gb', '', 1)

                ->parameter('MAINTENANCE', '0', '1 = maintenance mode, 0 if not')
                ->parameter('GOOGLE_ANALYTIC_ID', '', 'Google analytics ID')
                ->parameter('FACEBOOK_APP_KEY', '', 'Facebook ID')
                ->parameter('SITE_CATEGORY', 'not defined', 'Site category')

                //Deprecated
                ->parameter('PRODUCTION_DATABASE_HOST','','IP of production database')
                ->parameter('PRODUCTION_DATABASE_NAME','','Name of the production database')
                ->parameter('PRODUCTION_DATABASE_LOGIN','','Login for connect to production database')
                ->parameter('PRODUCTION_DATABASE_PASSWORD','','Password for connect to production database')
                ->parameter('PRODUCTION_DATABASE_PORT','3306','Port used to connect production database')
                

                //Mail
                ->parameter('MAIL_FROM_ADDRESS','','Mail from adresse')
                ->parameter('MAIL_FROM_NAME','','Mail from name')
                ->parameter('MAIL_ALLOWED','0','1 = Mail actived')
                ->parameter('MAIL_ADMINS','','Admin mail')
                ->parameter('MAIL_LOGO_URL',ShaContext::getPathToWeb().'shaoline/resources_' . self::getCacheSuffix() . '/img/logo.png','Mail logo url')
                
                //Smtp
				->parameter('SMTP_HOST','','Smtp ip')
				->parameter('SMTP_PORT','','Smtp port')
				->parameter('SMTP_NEED_AUTH','1','Smtp need authentifiaction')
				->parameter('SMTP_USER','','Smtp user')
				->parameter('SMTP_PASSWD','','Smtp password')
				
				//Preference
	            ->parameter('ADMIN_BLOCK_POSITION','bottom','Position of admin block (top, right, bottom, left)')
	            ->parameter('ADMIN_BLOCK_FONT_COLOR','#fff','Background color of admin block')
	            ->parameter('ADMIN_BLOCK_BACKGROUND_COLOR','#000','Font color of admin block')
	            ->parameter('DESKTOP_COLOR','#3D71B8','Desktop color')
	            
				//System
				->parameter('GC_TIMEOUT', '3600', 'Time of unsactivity elapse before delete GC managed object (in secondes)')
				->parameter('UPLOAD_PICTURE_MAX_SIZE', '1000000', 'Max file size for uploading')
				->parameter('MINIMIZE_RESOURCES', '0', 'Active or not JS and CSS minimisation')
				
				//Default TPL
				->parameter('HEADER_TPL', '', 'Path to header tpl file')
				->parameter('FOOTER_TPL', '', 'Path to footer tpl file')
				->parameter('INCLUDE_TPL', '', 'Path to include tpl file')

                //STATS
                ->parameter('QTY_DAILY_VISITS', '0', 'Qty of visits sinc.ui-widget-headere beginning of day')
                ->parameter('LAST_VISIT_DATETIME', date("Y-m-d H:i:s"), 'Datetime of last visit')

                //Security
                ->parameter('IP_SECURITY_TIMEOUT', 30, 'Timeout for security ip checker')
                ->parameter('IP_SECURITY_MAX_ATTEMPTS', 10, 'Max IP conn,ection attempts')
                ->parameter('RSA_KEY_TIMEOUT', 10 * 24 * 3600, 'Timeout for security ip checker')
                ->parameter('RSA_KEY_QTY', 50, 'Max IP conn,ection attempts')
                ->parameter('USER_MAGIC_DURATION', 24 * 3600, 'Magic key duration in seconds')
                
                ->application("USERS", "Users","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/users.png","ShaContext::getShowCmoObjectListGc('ShaUser')","system/users","User manager : use this application to manage your users and persmissions. ",1,1,0)
                ->application("GROUPS", "Groups","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/groups.png","ShaContext::getShowCmoObjectListGc('ShaGroup')","system/users","Groups manager : use this application to manager user group in your website. ",0,1,0)
                ->application("LANGUAGES", "Languages","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/languages.png","ShaContext::getShowCmoObjectListGc('ShaLanguage')","system/parameters","Language manager : use this application to active or desactive languages on your website. ",0,1,0)
                ->application("TRANSLATIONS", "Translations","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/translations.png","ShaContext::getShowCmoObjectListGc('ShaTranslation')","system/parameters","Translations manager : use this application to translate globale contents. ",1,1,0)
                ->application("CONTENTS", "Contents","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/contents.png","ShaContext::getShowCmoObjectListGc('ShaContent')","site/contents","Content manager : use this application to manage your website contents. ",1,1,0)
                ->application("PARAMETERS", "Parameters","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/parameters.png","ShaContext::getShowCmoObjectListGc('ShaParameter')","system/parameters","Parameters manager : use this application to manage your website parameters. ",0,1,0)
                ->application("APPLICATIONS", "Applications","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/applications.png","ShaContext::getShowCmoObjectListGc('ShaApplication')","system/parameters","Application manager : allow you to manage your applications. ",0,1,0)
                ->application("PERMISSIONS", "Permissions","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/permissions.png","ShaContext::getShowCmoObjectListGc('ShaPermission')","system/users","Permissions manager : use this application to manage the permissions of each users. ",0,1,0)
                ->application("MENUS", "Menus","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/menus.png","ShaContext::getShowCmoObjectListGc('ShaMenu')","site/contents","Menus manager : use this application to manage the menus of your website. ",0,1,0)
                ->application("PAGES", "Pages","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/pages.png","ShaContext::getShowCmoObjectListGc('ShaPage')","site/pages","Pages manager : use this application to manage the pages of your web site. ",0,1,0)
                //->application("", "Online","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/online.png","Controller::getShowCmoObjectListGc('CmsOnline')","site/contents","Online manager : use this application to synchronize your databases. ",0,1,0)
                //->application("", "Pushonline","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/push_online.png","CmsOnline::getPushOnlineGc()","site/contents","Click here to push online your content. ",0,1,0)
                ->application("DISCONNECT", "Disconnect","shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/disconnect.png","ShaUser::getDisconnectGc()","","Click here to disconnect your session. ",0,1,100)
                ->application("RESOURCES", "Deploy", "shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/resources.png", "ShaContext::getLoadResourcesGc()", "site/contents", "Use this application to push all resource into web folder", 0, 1, 1)
                ->application("ACCESS", "Access", "shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/htaccess.png", "ShaContext::getCreateHtaccessListGc()", "system/contents", "Use this application to rebuild htaccess file", 0, 1, 1)
                ->application("FLASH", "Flash MSG", "shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/flashMessage.png", "ShaContext::getShowCmoObjectListGc('ShaFlashMessage')", "site/contents", "Use this manage user flash messages", 0, 1, 1)
                ->application("LOGS", "Logs", "shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/icons/logs.png", "ShaUtilsLog::showLogFilesGcId()", "system/security", "Use this manage user consult logs", 0, 1, 1)
                 
                ->permission("CHANGE_MAINTENANCE_STATE", "Define if a user is allowed to change the maintenance state of website")
                ->permission("ACCESS_DESKTOP", "Define if a user is allowed to access admin desktop")
			
            ;
            //Fill root rules and applications
            self::addAllPermissionsToRoot();
            self::addAllApplicationsToRoot();

            ShaContext::updateWeb();
            ShaPage::rebuildHtaccess();

        }
    }

    /**
     * Instanciate all classes presents in folder
     *
     * @param $path
     */
    public static function instanciateAllCmoInFolder($path){
        $files = ShaUtilsFile::getFiles($path);
        foreach ($files as $file) {
            $className = ShaUtilsString::replace($file, ".php", "");
            $className = ShaUtilsString::replace($className, $path, "");
            $className = ShaUtilsString::replace($className, "/", "");
            $class = new ReflectionClass($className);
            if ($class->isInstantiable()) {
                $obj = new $className();
                unset($obj);
            }
        }
    }

    /**
     * Return URL base (Ex: 127.0.0.1/multilangue/)
     *
     * @return string
     */
    public static function getSiteUrl() {
        return self::$_configuration->get("site/url").self::$_configuration->get("site/folder");
    }

    /**
     * Return site protocol (ex: http://)
     *
     * @return string
     */
    public static function getSiteProtocol() {
        return self::$_configuration->get("site/protocol");
    }

    /**
     * return Complete basic URL of site
     *
     * @return string
     */
    public static function getSiteFullUrl() {
        return self::getSiteProtocol().self::getSiteUrl();
    }

    /**
     * Execute 'SELECT' query and return Recordset
     *
     * @param string $query
     *
     * @return IRecorset
     */
    public static function bddSelect($query) {
        return self::$_bddConnector->select($query);
    }


    /**
     * Execute 'SELECT' query and return Recordset
     *
     * @param string $query
     *
     * @return IRecorset
     */
    public static function bddExist($query) {
        return self::$_bddConnector->exist($query);
    }

    /**
     * Execute 'SELECT' query and return the single value
     *
     * @param string $query (the result has to contain only 1 row with 1 field)
     *
     * @return string
     */
    public static function bddSelectValue($query) {
        return self::$_bddConnector->selectValue($query);
    }

    /**
     * Execute 'INSERT' query and return last insered id
     *
     * @param string $query
     *
     * @return int
     */
    public static function bddInsert($query) {
        return self::$_bddConnector->insert($query);
    }

    /**
     * Execute 'UPDATE' query
     *
     * @param string $query
     */
    public static function bddUpdate($query) {
        self::$_bddConnector->update($query);
    }

    /**
     * Execute 'DELETE' query
     *
     * @param string $query
     */
    public static function bddDelete($query) {
        self::$_bddConnector->delete($query);
    }

    /**
     * Execute 'CREATE, ALTER' query
     *
     * @param string $query
     */
    public static function bddExecute($query) {
        self::$_bddConnector->execute($query);
    }

    /**
     * Return current BddConnector
     *
     * @return IBddConnector
     */
    public static function getBddConnector() {
        return self::$_bddConnector;
    }

    /**
     * Return the current language used
     *
     * @return int
     */
    public static function getLanguageId() {
        return ShaSession::get("cms_current_language_id", 1);
    }

    /**
     * Return the current language used
     *
     * @return int
     */
    public static function getLanguage() {
    	return self::$_language;
    }
    
    /**
     * Change current language used
     *
     * @param int $languageId Id of language
     *
     * @throws Exception
     * @return bool
     */
    public static function setLanguage($languageId) {

        self::$_language = new ShaLanguage();
        if (self::$_language->load(array('language_id' => $languageId))) {
            ShaSession::set("cms_current_language_id", $languageId);
            return true;
        } else {
        	self::setLanguage(1);
            return false;
        }
    }

    /**
     * Return the translation of a content (if not existe, creat an empty entry in the database)
     *
     * @param string $contentKey Id of content to translate
     *
     * @return string
     */
    public static function t($contentKey) {
        return (self::$_translator == null) ? $contentKey : self::$_translator->translate($contentKey);
    }

    /**
     * Return the translation of a content (if not exist, create an empty entry in the database)
     *
     * @param string $contentKey
     * @param array $values
     *
     * @return string
     */
    public static function tt($contentKey, $values) {

        if (!isset($values)) {
            return self::t($contentKey);
        }

        if (!is_array($values)) {
            $values = array($values);
        }

        $result = self::$_translator->translate($contentKey);
        $index = 0;
        foreach ($values as $value) {
            $result = ShaUtilsString::replace($result, "%".$index."%", $value);
            $index++;
        }
        return $result;
    }

    /**
     * Return the translation of a content (if not existe, creat an empty entry in the database) and add bold balise
     *
     * @param string $contentKey Id of content to translate
     *
     * @return string
     */
    public static function tb($contentKey) {
        return self::$_translator->translateBold($contentKey);
    }

    /**
     * Return the translation of a content (if not existe, creat an empty entry in the database) and add bold balise
     *
     * @param string $contentKey Id of content to translate
     *
     * @return string
     */
    public static function tj($contentKey) {
        return self::$_translator->translateJs($contentKey);
    }

    /**
     * Return translator object
     *
     * @return ShaContent
     */
    public static function getContent() {
        if (!isset(self::$_content)) {
            self::$_content = new ShaContent();
        }
        return self::$_content;
    }

    /**
     * Return next cms unic ID
     *
     * @return int
     */
    public static function getNextContentId() {
        self::$_contentId = ShaSession::get("cms_content_id", 0);
        self::$_contentId++;
        ShaSession::set("cms_content_id", self::$_contentId);
        return self::$_contentId;
    }

	/**
	 * Return true if Cms basic datas are already installed
	 *
	 * @return bool
	 */
	private static function _isInstalled() {
		return ShaOrm::isTableExistInBdd("shaoline_user_group");
	}

	/**
	 * Add all permission to root
	 *
	 * @return void
	 */
	public static function addAllPermissionsToRoot() {
		//Add permissions to root
		self::bddInsert(
			"INSERT INTO shaoline_group_application (group_key, application_key) (SELECT 'admin', application_key FROM shaoline_application WHERE application_key NOT IN (SELECT application_key FROM shaoline_group_application WHERE group_key = 'admin'))"
		);
	}

	/**
	 * Add all permission to root
	 *
	 * @return void
	 */
	public static function addAllApplicationsToRoot() {
		//Add permissions to root
		self::bddInsert("
            INSERT INTO shaoline_group_permission (group_key, permission_key)
			(SELECT 'admin', permission_key FROM shaoline_permission WHERE permission_key NOT IN (SELECT permission_key FROM shaoline_group_permission));
			"
		);
	}

	/**
	 *  Write basic HEAD  requierements
	 *
	 *  @return string
	 */
	public static function writeBasicHeadInfo() {

		//TODO : nofollow ??

		return "
<!-- Site basic URL -->
<base href='".self::getSiteFullUrl()."/' />

<!-- Meta tags -->
<meta http-equiv='content-type' content='text/html;charset=utf-8' />
<meta http-equiv='content-language' content='".self::$_language->drawCurrentLanguageAbr()."' />
<meta name='category' content='administration' />
<meta name='Revisit-After' content='10 days' />
<meta name='robots' content='noindex, nofollow' />
";

	}

	/**
	 * Write XHTML HTML balise requierements
	 *
	 * @return string
	 */
	public static function writeXHTMLHead() {
		return 'xmlns="http://www.w3.org/1999/xhtml"';
	}

	/**
	 * Write facebook HTML balise requierements
	 *
	 * @return string
	 */
	public static function writeFacebookHead() {
		return 'xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="' . self::$_language->getValue('language_abr') . '" lang="' . self::$_language->getValue('language_abr') . '"';
	}

	/**
	 * Return site URL with current language path
	 *
	 * @return string
	 */
	public static function getLanguageURLBase() {
		return self::getSiteFullUrl()."/".self::$_language->getValue('language_abr')."/";
	}

	/**
	 * Write link necessary for Cms
	 *
	 * @return string
	 */
	public static function insertCss() {

		$result = "";
        if (is_file(self::getPathToWeb().self::getObfuscatedCssFile())){
        	$result = '<link type="text/css" rel="stylesheet" href="'.self::getSiteFullUrl()."/".self::getObfuscatedCssFile().'" />';
        } else {
        	$shaObfuscatorManager = new ShaObfuscatorManager();
        	$shaObfuscatorManager->addFiles(self::$_cssFiles);
        	$result = $shaObfuscatorManager->getIncludes(new ShaObfuscatorCSS, self::getSiteFullUrl());
        }
        
        $result .= self::addPluginsCssFiles();

        return $result;
	}

	/**
	 * Write script necessary for Cms
	 *
	 * @return string
	 */
	public static function insertJs() {

		$result = "";
		if (is_file(self::getPathToWeb().self::getObfuscatedJsFile())){
			$result = '<script type="text/javascript" src="'.self::getSiteFullUrl()."/".self::getObfuscatedJsFile().'"></script>';
		} else {
			$shaObfuscatorManager = new ShaObfuscatorManager();
			$shaObfuscatorManager->addFiles(self::$_jsFiles);
			$result = $shaObfuscatorManager->getIncludes(new ShaObfuscatorJS, self::getSiteFullUrl());
		}
		$result .= self::addPluginsJsFiles();
		
		return $result;

	}


    /**
     * Write link necessary for Cms
     *
     * @return string
     */
    public static function insertAdminCss() {
    	
    	$result = "";
    	if (is_file(self::getPathToWeb().self::getObfuscatedAdminCssFile())){
    		$result = '<link type="text/css" rel="stylesheet" href="'.self::getSiteFullUrl()."/".self::getObfuscatedAdminCssFile().'" />';
    	} else {
    		$shaObfuscatorManager = new ShaObfuscatorManager();
    		$shaObfuscatorManager->addFiles(self::$_adminCssFiles);
    		$result = $shaObfuscatorManager->getIncludes(new ShaObfuscatorCSS, self::getSiteFullUrl());
    	}
    	
    	$result .= self::addPluginsCssFiles();
    
        return $result;
    }

    /**
     * Write script necessary for Cms
     *
     * @return string
     */
    public static function insertAdminJs() {
        
        //Export resource cachde suffix
        $result = "";
        $result .= '<script type="text/javascript">'.PHP_EOL;
        $result .= 'var SHA_RESOURCE_SUFFIX = "' . ShaPage::getCacheSuffix() . '";'.PHP_EOL;
        $result .= '</script>'.PHP_EOL;
        
		if (is_file(self::getPathToWeb().self::getObfuscatedAdminJsFile())){
			$result .= '<script type="text/javascript" src="'.self::getSiteFullUrl()."/".self::getObfuscatedAdminJsFile().'"></script>';
		} else {
			$shaObfuscatorManager = new ShaObfuscatorManager();
			$shaObfuscatorManager->addFiles(self::$_adminJsFiles);
			$result .= $shaObfuscatorManager->getIncludes(new ShaObfuscatorJS, self::getSiteFullUrl());
		}
		$result .= self::addPluginsJsFiles();
		
		return $result;
    }

    /**
	 * Write HTML div necessary for Cms
	 *
	 * @return string
	 */
	public static function insertHtmlDiv() {
		return "
		    <!-- Cms HTML requierements -->
			<div id='cms_masque' class='opacity_50'></div>
			<div id='cms_waiting_masque' class='opacity_50'></div>
			<div id='cms_persistent_div'></div>
            <div id='cms_edit_picto'><img alt='' src='shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/cms_btn_pen.png'></div>

		";
	}


    /**
	 * Add css of all actived plugins
	 *
	 * @return string
	 */
	public static function addPluginsCssFiles() {
		//Add CSS of each plugins
        $plugins = self::getConf()->get('plugins');
        $result="";
        if (is_array($plugins)) {
            foreach ($plugins as $plugin) {
                if (!is_dir(__DIR__."/../../php/plugins/".$plugin."/css")) {
                    return "";
                }
                $aCssFiles = ShaUtilsFile::getFiles(__DIR__."/../../php/plugins/".$plugin."/css");
                foreach ($aCssFiles as $sCssFile) {
                    $sFilesUrl = "php/plugins/".$plugin."/css/".basename($sCssFile);
                    $result .= '<link type="text/css" rel="stylesheet" href="'.$sFilesUrl.'" />'.PHP_EOL;
                }
            }
        }

        return $result;
	}

	/**
	 * Add js of all actived plugins
	 *
	 * @return string
	 */
	public static function addPluginsJsFiles() {

		//Add CSS of each plugins
        $plugins = self::getConf()->get('plugins');
        $result="";
        if (is_array($plugins)) {
            foreach ($plugins as $plugin) {
                if (!is_dir(__DIR__."/../plugins/".$plugin."/js")) {
                    return "";
                }
                $aJsFiles = ShaUtilsFile::getFiles(__DIR__."/../plugins/".$plugin."/js");
                foreach ($aJsFiles as $sJsFile) {
                    $sFilesUrl = "cms/php/plugins/".$plugin."/js/".basename($sJsFile);
                    $result .= '<script type="text/javascript" src="'.$sFilesUrl.'"></script>';
                }
            }
        }
        return $result;
	}

	/**
	 * Write HTML div necessary for Cms
	 *
	 * @return string
	 */
	public static function writeAdminHTMLDiv() {

		return "
			<!-- Cms ADMIN HTML requierments -->
			<div id='cms_admin_picto_support'></div>
			<div id='cms_masque' class='opacity_50'></div>
			<div id='cms_waiting_masque' class='opacity_50'></div>
			<div id='cms_persistent_div'></div>
			<div id='cms_constant_reporting'></div>

			<!-- Capture mouse motions -->
			<script type='text/javascript'>
			    ".ShaGarbageCollector::getGcJsCode()."
				UtilsWindow.launchWindowsMoveSystem();
				UtilsWindow.launchWindowsUpSystem();
			</script>
		";

	}

	/**
	 * Unserialize a CMOListing configuration
	 *
	 * @param string $config Configuration
	 *
	 * @return array [divSupport,class,cssClass,condition,start,limit,addDeleteBtn,addEditBtn,addAddBtn,field]
	 * @throws Exception
	 */
	public static function serializeListingParam($config) {

		$bUnic = (isset($config['unic']) && $config['unic']) ? true : false;
		$bKey = (isset($config['gc_key'])) ? $config['gc_key']: "";
		return ShaGarbageCollector::addItem(serialize($config), $bUnic, $bKey);
	}

	public static function getUploadTmpDir(){
		return ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
	}

    public static function deleteTmpFile($file){
    	//TODO : finish that
        //unlink(self::getUploadTmpDir().$file['tmp_name']);
    }

	/**
	 * Upload file into folder
     * !! Security warning : text extension !!
	 */
	public static function uploadFile($file) {

        $ext = ShaPicture::getFormat($file['name']);
        $tmpFolder = self::getPathToBase()."tmp/";
        if (!is_dir($tmpFolder)) {
            ShaUtilsLog::error("'tmp' folder does not exist !");
            self::deleteTmpFile($file);
            return "";
        }
        $newUrl = self::getPathToBase()."tmp/".uniqid().".".$ext;
        $result = "";
	    if (move_uploaded_file($file['tmp_name'], $newUrl)) {
            $result = $newUrl;
        } else {
            ShaUtilsLog::error("Error with 'move_uploaded_file' function (from : '".$file['tmp_name']."', to : '".$newUrl."')");
        }
        self::deleteTmpFile($file);
        return $result;
	}
	
	/**
	 * Draw language selector for admin page
	 *
	 * @return string
	 */
	public static function writeAdminLanguageSelector() {
        return "
			<div id='cms_admin_language_selector'>
				".ShaLanguage::drawLanguageSelect(true)."
			</div>
		";
	}


    /**
     * Return config for admin editing elements
     *
     * @param ShaCmo $cmo ShaDao instance
     * @param string $fieldName ShaBddField name
     * @param string $fieldType ShaBddField type
     *
     * @return string
     */
    public static function getEditPicto($cmo, $fieldName, $fieldType) {

        $name = "admin_picto_".self::getNextContentId();
        $primaryString = $cmo->getPrimaryKeysAsArray();

        $ShaOperation = new ShaOperationEditField();
        $ShaOperation
            ->setDaoClass(get_class($cmo))
            ->setPrimary($primaryString)
            ->setField($fieldName)
            ->setType($fieldType)
            ->save();

        return "
            name='$name'
            onMouseOut='Shaoline.hideAdminPicto()'
            onMouseOver=\"Shaoline.refreshAdminPicto('" . $name . "','".$fieldType."','" . $ShaOperation->getGcId() . "')\"
            ".$ShaOperation->getDomEvent()."
         ";

    }


    /**
     * Reyturn GCid element for defaut cms object list
     *
     * @param string $sClassName Class name
     *
     * @return string
     */
    public static function getShowCmoObjectListGc($sClassName) {

        $ShaOperation = new ShaOperationAction();
        $ShaOperation
            ->setDaoClass("ShaController")
            ->setDaoMethod("showCmoObjectList")
            ->addParameter('className', $sClassName)
            ->save()
        ;

        return $ShaOperation->getDomEvent();

    }

	
    /**
     * Reyturn GCid element for defaut cms object list
     *
     * @param string $sClassName Class name
     *
     * @return string
     */
    public static function getLoadResourcesGc() {

    	$response = new ShaResponse();
    	$response
    	    ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
    	    ->setContent(ShaContext::t("Resources correctly copied in 'web' folder"))
    	    ->getPopin()
    	    ->setColor("blue")
    	    ->setTitle(ShaContext::t("Copy of resources"))
    	;

        $ShaOperation = new ShaOperationAction();
        $ShaOperation
        	->setActiveConfirmation(true)
            ->setDaoClass("ShaContext")
            ->setDaoMethod("updateWeb")
        	->setShaResponse($response)
            ->save()
        ;

        return $ShaOperation->getDomEvent();

    }

    /**
     * Reyturn GCid element for defaut cms object list
     *
     * @param string $sClassName Class name
     *
     * @return string
     */
    public static function getCreateHtaccessListGc() {

    	$response = new ShaResponse();
    	$response
    		->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
    		->setContent(ShaContext::t(".htaccess build correcly"))
    		->getPopin()
    			->setColor("blue")
    			->setTitle(ShaContext::t("Rebuild htaccess"))
    	;

        $ShaOperation = new ShaOperationAction();
        $ShaOperation
       		->setActiveConfirmation(true)
            ->setDaoClass("ShaPage")
            ->setDaoMethod("rebuildHtaccess")
            ->setShaResponse($response)
            ->save()
        ;

        return $ShaOperation->getDomEvent();

    }


    /**
     * //Create autoload.php file if not exist
    if (!file_exists(__DIR__."/autoload.php")) {

    include_once "php/utils/ShaUtilsString.php";
    include_once "php/utils/ShaUtilsFile.php";

    $list = array();

    $files = ShaUtilsFile::getFiles(__DIR__."/php/core", true);
    foreach ($files as $file) { $list[] = $file; }

    $files = ShaUtilsFile::getFiles(__DIR__."/php/utils");
    foreach ($files as $file) { $list[] = $file; }

    //LOAD Cms PLUGINS
    foreach ($GLOBALS['PLUGINS'] as $pluginName) { $files[] = "../plugins/".$pluginName.".php'"; };

    foreach ($list as &$file) {

    $file = ShaUtilsString::replace($file, __DIR__, "");
    $file = "include_once '".substr($file, 1, strlen($file) - 1)."';";
    }

    $content = implode(PHP_EOL, $list);
    ShaUtilsFile::writeContent(__DIR__."/autoload.php" ,"<?php ".PHP_EOL." ".$content." ".PHP_EOL);

    }
     */


    /**
     * Configure JS 'drawPopin' function to display error message (adding javascript HTML balise)
     *
     * @param string $title Popin title
     * @param string $msg   Popin error message
     *
     * @return JS code
     */
    public static function cmsErrorPopinWithJsBalise($title, $msg) {
        return "
			<script type='text/javascript'>
				UtilsWindow.drawPopin(0,'red',UtilsString.AsciiTochar('" . ShaUtilsString::getASCII($msg) . "'),'" . $title . "','','','cms_error',true,true);
			</script>
		";
    }

    /**
     * Configure JS 'drawPopin' function to display error message
     *
     * @param string $title Popin title
     * @param string $msg   Popin error message
     *
     * @return string
     */
    public static function cmsErrorPopin($title, $msg) {
        return "UtilsWindow.drawPopin(0,'red',UtilsString.AsciiTochar('" . ShaUtilsString::getASCII($msg) . "'),'" . $title . "','','','cms_error',true,true);";
    }

    /**
     * Configure JS 'drawPopin' function to display message with ascii js decode instruction
     *
     * @param string $title          Popin title
     * @param string $msg            Popin message
     * @param string $id             Id of popin
     * @param string $js             JS function called when click
     * @param string $btnMsg         ShaButton text
     * @param string $color          Popin color [blue, red]
     * @param bool   $activeDragDrop True to active DragDrop listener
     * @param bool   $activeMask     True to active mask
     *
     * @return string
     */
    public static function cmsPopin($title, $msg, $id = 'cms_default_popin', $js = '', $btnMsg = '', $color = 'blue', $activeDragDrop = 'true', $activeMask = 'true') {
        return "UtilsWindow.drawPopin(0,'" . $color . "',UtilsString.AsciiTochar('" . ShaUtilsString::getASCII($msg) . "'),'" . $title . "','" . $js . "','" . $btnMsg . "','" . $id . "'," . $activeDragDrop . "," . $activeMask . ")";
    }


    /**
     * Configure JS 'drawPopin' function to display message  (adding javascript HTML balise)
     *
     * @param string $title          Popin title
     * @param string $msg            Popin message
     * @param string $id             Id of popin
     * @param string $js             JS function called when click
     * @param string $btnMsg         ShaButton text
     * @param string $color          Popin color [blue, red]
     * @param bool   $activeDragDrop True to active DragDrop listener
     * @param bool   $activeMask     True to active mask
     *
     * @return string
     */
    public static function cmsPopinWithJsBalise($title, $msg, $id = 'cms_default_popin', $js = '', $btnMsg = '', $color = 'blue', $activeDragDrop = 'true', $activeMask = 'true') {
        return "
			<script type='text/javascript'>
			" . self::cmsPopin($title, $msg, $id, $js, $btnMsg, $color, $activeDragDrop, $activeMask) . "
			</script>
		";
    }


    /**
     * Copy all resources  in web folder
     * do obfuscation and minimisations
     *
     * @return string feedback
     */
    public static function updateWeb(){

        ShaTreatmentInfo::clearGroupInfo('PUSH_RESOURCE');
        ShaUtilsLog::info("Starting update resources !");

        //Shaoline resources
        $previewsCacheSuffix = ShaPage::getCacheSuffix();
        ShaPage::updateCacheSuffix();
        
        //Delete old shaoline resource folder
        ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Delete shaoline resources folder ...');
        ShaUtilsFile::rmDir(self::getPathToWeb().'shaoline/resources_' . $previewsCacheSuffix);
        
        //Copy pictures
        ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Copying shaoling pictures ...');
        $shaolineSrc = self::getPathToBase().'shaoline/resources/img';   
        $shaolineDst = self::getPathToWeb().'shaoline/resources_' . ShaPage::getCacheSuffix() . '/img';
        ShaUtilsFile::copyFiles($shaolineSrc, $shaolineDst);

    	
    	//Prepare obfuscator
    	$shaObfuscatorManager = new ShaObfuscatorManager();
    	$shaObfuscatorManager->setSrcPath(self::getPathToBase());    
    	if (ShaParameter::get("MINIMIZE_RESOURCES") == "1"){
    		
    		//Copy CSS
            ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Pushing admin CSS');
    		$shaObfuscatorManager->addFiles(self::$_adminCssFiles);
    		$shaObfuscatorManager->process(new ShaObfuscatorCSS(), self::getPathToWeb().self::getObfuscatedAdminCssFile(), true);
    		$shaObfuscatorManager->clearFiles();
    		 
    		//Copy JS
            ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Pushing admin JS');
    		$shaObfuscatorManager->addFiles(self::$_adminJsFiles);
    		$shaObfuscatorManager->process(new ShaObfuscatorJS(), self::getPathToWeb().self::getObfuscatedAdminJsFile(), true);
    		$shaObfuscatorManager->clearFiles();
    		 
    		//Copy CSS
            ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Pushing CSS');
    		$shaObfuscatorManager->addFiles(self::$_cssFiles);
    		$shaObfuscatorManager->process(new ShaObfuscatorCSS(),  self::getPathToWeb().self::getObfuscatedCssFile(), true);
    		$shaObfuscatorManager->clearFiles();
    		
    		//Copy JS
            ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Pushing JS');
    		$shaObfuscatorManager->addFiles(self::$_jsFile);
    		$shaObfuscatorManager->process(new ShaObfuscatorJS(), self::getPathToWeb().self::getObfuscatedJsFile(), true);
    		$shaObfuscatorManager->clearFiles();
    		 
    		
    	} else {

    		//Copy CSS
            ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE' , 'Pushing CSS');
    		$shaObfuscatorManager->addFiles(self::$_adminCssFiles);
    		$shaObfuscatorManager->process(new ShaObfuscatorCSS(),  self::getPathToWeb(), false);
    		$shaObfuscatorManager->clearFiles();
    		
    		//Copy JS
            ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Pushing JS');
    		$shaObfuscatorManager->addFiles(self::$_adminJsFiles);
    		$shaObfuscatorManager->process(new ShaObfuscatorJS(), self::getPathToWeb(), false);
    		$shaObfuscatorManager->clearFiles();
    		
    	}

    	
    	//Plugins resources
    	$plugins = ShaContext::getConf()->get("plugins");
    	if (is_array($plugins)) {
    		foreach ($plugins as $plugin) {
                ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Pushing plugin ' + $plugin);
                
                //Delete old folder
                ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Delete plugin ' . $plugin . ' resources folder ...');
                ShaUtilsFile::rmDir(self::getPathToWeb().'plugins/'.$plugin.'/resources_' . $previewsCacheSuffix);
                
                //Copy files in new folder
                ShaTreatmentInfo::setInfo('TASK', 'PUSH_RESOURCE', 'Copying plugin ' . $plugin . ' pictures ...');
    			$pluginSrc = self::getPathToBase()."plugins/".$plugin."/resources";
    			$pluginDst = self::getPathToWeb()."plugins/".$plugin."/resources_".ShaPage::getCacheSuffix();
    			ShaUtilsFile::copyFiles($pluginSrc, $pluginDst);
    		}
    	}

        ShaTreatmentInfo::clearGroupInfo('PUSH_RESOURCE');
        ShaUtilsLog::info("Stoping update resources !");
    }

    /**
     * Encode msg using parameters security/encoded
     *
     * @param $msg
     * 
     * @return void
     */
    public static function securityEncode($msg){
        $encodedStep = self::$_configuration->get("security/encoded");
        foreach ($encodedStep as $step){
            switch ($step) {
                case 'md5':
                    $msg = md5($msg);
                    break;
                case 'sha1':
                    $msg = sha1($msg);
                    break;
            }
        }
        return $msg;
    }

    /**
     * Add flash message for user
     * 
     * @param string $msg
     * 
     * @return void
     */
    public static function addFlashMessage($msg) {
        ShaSession::add("cms_flash_message", $msg);
    }

    /**
     * Return the client IP
     * 
     * @return string
     */
	public static function getClientIp(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : "localhost";
		}
		return "NO_IP";
	}

	/**
	 * Return the client browser
	 * 
	 * @return string
	 */
	public static function getClientBrowser() {
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			return $_SERVER['HTTP_USER_AGENT'];
		} else {
			return "UNKNOWN";
		}
	}
	
	/**
	* TODO
	*/
	public static function declareRsaPublicKey(){
		if (ShaSession::has("rsaKeyId")){
			$publicKey = ShaSession::get("rsaKeyId")->getValue("public_key");
            $size = strlen($publicKey);
            if ($size == 0){
                return "";
            }
			if ($publicKey[$size - 1] == '\n')
				$publicKey = substr($publicKey, 0, $size - 1);
			$publicKey = ShaUtilsString::replace($publicKey, "\n", "\\\n");
			$html = "var publicKey = '".$publicKey."';";
			$html .= "Shaoline.setRsaPublicKey(publicKey);";
			return $html;
		} 
	}
	
	/**
	* Try to launch plugin commande
	*
	* @param $command:string plugin to looking for
	* @param $command:string command to looking for
	* @param $args:array full parameters array
	*
	* @return bool true if command found, false if not
	*/
	public static function checkCommandInPlugins($plugin, $command, $args){
		
		$pluginUpperName = strtoupper($plugin);
		if ($pluginUpperName != 'CORE' && class_exists($pluginUpperName) && method_exists ($pluginUpperName , "commande" )){
        	$pluginUpperName::commande($command, $args);
		}
	}
	
	/**
	* Looking for matching comand in all plugins
	*
	* @param $command:string command to looking for
	* @param $args:array full parameters array
	*
	* @return bool true if command found, false if not
	*/
	public static function listAllPluginsCommandes(){
		//LOAD ALL PLUGINS
        $plugins = self::$_configuration->get("plugins");
        if (is_array($plugins)) {
           foreach ($plugins as $plugin) {
        		if (method_exists ($plugin , "commande" )){
        			$plugin::commande("help", null);
        		}
        	};
        }
	}
		
}

?>