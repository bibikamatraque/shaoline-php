<?php


/**
 * Description of ShaPage
 *
 * PHP version 5.3
 *
 * @category   Cms
 * @package    Core
 * @subpackage Component
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaPage extends ShaCmo
{

    /** @var array $_shaPageObjects */
    private $_shaPageObjects;
    /** @var array $_shaPageObjects */
	private $_shaPageObjectsInfo;
    /** @var string $_shaPageObjects */
	public static $preBodyCode="";
    /** @var array $_endOfPageJs */
    public static $_endOfPageJs = array();
    /** @var array $_endOfPageJsScript */
    public static $_endOfPageJsScript = array();
    /** @var string additional article */
    public static $additionalHtaccess;

    public static $_title;
    public static $_description;
    public static $_pageHtml = "";

    /** @var string $_shaPageObjects */
	const CONST_ID_PAGE_404 = "notFound";


    public static function forceTitle($title){
        self::$_title = $title;
    }

    public static function forceDescription($description){
        self::$_description = $description;
    }


    /**
     * Return load object
     *
     * @param string $objectName
     *
     * @return Object
     */
    public function getObject($objectName)
    {
        return $this->_shaPageObjects[$objectName];
    }

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName()
    {
		return "shaoline_page";
	}

	/**
	 * Return SQL creating request
	 *
	 * @return string
	 */
	public static function getTableDescription()
    {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())

            ->addField("page_international_name")->setType("VARCHAR(20)")->setPrimary()->end()
            ->addField("language_id")->setType("MEDIUMINT UNSIGNED")->setPrimary()->setIndex()->end()
            ->addField("page_variables")->setType("TEXT")->end()
            ->addField("page_add_language_prefix")->setType("TINYINT UNSIGNED")->setDefault(1)->end()

            ->addField("page_title")->setType("TEXT")->end()
            ->addField("page_description")->setType("TEXT")->end()
            ->addField("page_keyword")->setType("TEXT")->end()
            ->addField("page_comment")->setType("TEXT")->end()

            ->addField("page_qty_visite")->setType("BIGINT UNSIGNED")->setDefault(0)->end()
            
            ->addField("page_structure")->setType("TINYINT")->setDefault(1)->end()
            ->addField("page_add_header")->setType("TINYINT")->setDefault(1)->end()
            ->addField("page_add_footer")->setType("TINYINT")->setDefault(1)->end()
            ->addField("page_add_body")->setType("TINYINT")->end()
            ->addField("page_active")->setType("TINYINT")->setDefault(1)->end()
            ->addField("page_add_analytics")->setType("TINYINT")->setDefault(1)->end()

            ->addField("page_template")->setType("TEXT")->end()
            ->addField("page_url")->setType("TEXT")->end()
            ->addField("page_url_additional")->setType("TEXT")->end()
            ->addField("page_need_redirect")->setType("TINYINT")->setDefault(1)->end()
            ->addField("page_get_parameters")->setType("TEXT")->setDefault("")->end()
            ->addField("page_importance")->setType("FLOAT UNSIGNED")->end()

            ->addField("additional_js")->setType("TEXT")->end()
            ->addField("additional_css")->setType("TEXT")->end()
            ->addField("page_css_class")->setType("TEXT")->end()

            ->addField("page_active_facebook")->setType("TINYINT")->end()
            ->addField("page_active_twitter")->setType("TINYINT")->end()

            ->addField("page_client_cache_duration")->setType("INT")->end()
            ->addField("page_server_cache_duration")->setType("INT")->end()
            
            ->addField("page_robots")->setType("VARCHAR(30)")->setDefault("index, follow")->end()
        ;

        return $table;

	}

	/**
	 * Return array of field type descriptions
	 *
	 * @return array
	 */
	public function defaultLineRender() {

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("page_active")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(array("0"=>'shaoline/resources/img/cms_led_red.png',"1"=>'shaoline/resources/img/cms_led_green.png'))->end()
            ->addField()->setDaoField("language_id")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(array_merge(array("0"=>'shaoline/resources/img/no_flag.png'), ShaLanguage::getValuesMapping("language_id", "language_flag")))->setWidth(20)->end()
            ->addField()->setDaoField("page_international_name")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("page_qty_visite")->setLibEnable(false)->setWidth(100)->end()
        ;
        return $form;

	}

	/**
	 * Return array of field type descriptions for formulaire
	 *
	 * @return array
	 */
	public function defaultEditRender() {

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->setInputWidth(300)
            ->addField()->setInputEnable(false)->setLib('<div class="cms_link"><a href="'.$this->getUrlBase().'">'.ShaContext::t("Go to page").'</a></div>')->end()
            ->addField()->setInputEnable(false)->setLib('<div class="cms_title">'.ShaContext::t("Global").'</div>')->end()
            ->addField()->setDaoField("page_active")->setLib(ShaContext::t("is_page_active"))->setWidth(15)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(array("0"=>'shaoline/resources/img/cms_led_red.png',"1"=>'shaoline/resources/img/cms_led_green.png'))->end()
            ->addField()->setLib("Qty visits")->setDaoField("page_qty_visite")->setEditable(false)->end()
            ->addField()->setDaoField("language_id")->setLib(ShaContext::t("Language"))->setWidth(15)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(array_merge(array("0"=>'shaoline/resources/img/no_flag.png'), ShaLanguage::getValuesMapping("language_id", "language_flag")))->setWidth(20)->end()
            ->addField()->setLib("Name")->setDaoField("page_international_name")->end()
            ->addField()->setLib("Variables")->setDaoField("page_variables")->end()
            ->addField()->setLib("Title")->setDaoField("page_title")->end()
            ->addField()->setLib("Description")->setDaoField("page_description")->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA)->setWidth(450)->setHeight(50)->end()
            ->addField()->setLib("Keywords")->setDaoField("page_keyword")->end()
            ->addField()->setInputEnable(false)->setLib('<div class="cms_title">'.ShaContext::t('Technics').'</div>')->end()
            ->addField()->setLib("Template")->setDaoField("page_template")->end()
            ->addField()->setLib("URL")->setDaoField("page_url")->end()
            ->addField()->setLib("Additional URL")->setDaoField("page_url_additional")->end()
            ->addField()->setLib("Need redirect")->setDaoField("page_need_redirect")->end()
            ->addField()->setLib("Parameters")->setDaoField("page_get_parameters")->end()
            ->addField()->setLib("Params")->setDaoField("page_get_param")->end()
            ->addField()->setLib("Canonical")->setDaoField("page_canonical")->end()
            ->addField()->setLib("Add header")->setDaoField("page_add_header")->end()
            ->addField()->setLib("Add footer")->setDaoField("page_add_footer")->end()
            ->addField()->setLib("Add body")->setDaoField("page_add_body")->end()
            ->addField()->setLib("Add analytic")->setDaoField("page_add_analytics")->end()
            ->addField()->setLib("Importance")->setDaoField("page_importance")->end()
            ->addField()->setInputEnable(false)->setLib('<div class="cms_title">'.ShaContext::t('Additionals').'</div>')->end()
            ->addField()->setLib("Body CSS class")->setDaoField("page_css_class")->end()
            ->addField()->setLib("JS file")->setDaoField("additional_js")->end()
            ->addField()->setLib("CSS file")->setDaoField("additional_css")->end()
            ->addField()->setInputEnable(false)->setLib('<div class="cms_title">'.ShaContext::t('Social').'</div>')->end()
            ->addField()->setLib("Active Facebook")->setDaoField("page_active_facebook")->end()
            ->addField()->setLib("Active Twitter")->setDaoField("page_active_twitter")->end()
            ->addField()->setLib("Comment")->setDaoField("page_comment")->end()
            ->addField()->setLib("International name")->setDaoField("page_international_name")->end()
            ->addField()->setLib("Robots")->setDaoField("page_robots")->end()
        ;
        return $form;
	}

	/**
	 * Do HTTP redirection
	 *
	 * @param string $sUrl New URL
	 *
	 * @return void
	 */
	public static function redirect($sUrl) {

		if (!headers_sent()) {

			header('Location: '.$sUrl);
		} else {

            echo  "
            <script type='text/javascript'>
			    window.location.href = '".$sUrl."'
			</script>
			<noscript>
			    <meta http-equiv='refresh' content='0' url='".$sUrl."' >
			</noscript>
            ";
		}
        exit;

	}

	/**
	 * Return base URL of page, using international name and current id
	 *
	 * @param string  $internationalName International page name
	 * @param string  $object            Instance of cmsObject
	 * @param boolean $allURL            Write complete URL or not
	 *
	 * @return string
	 */
	public static function getUrlFromName($internationalName, $object = null,  $allURL=false, $params = array()) {
		$pages = ShaPage::loadByWhereClause("page_international_name = '".$internationalName."' AND language_id = ".ShaContext::getLanguageId());
		
		if (count($pages)>0) {
            /** @type ShaPage $firstPage */
            $firstPage = $pages[0];
			$url = $firstPage->writeUrl($object, $allURL);
			if (count($params) > 0) {
				$url .= "?";
			}
			foreach ($params as $key => $value) {
				$url .= "&$key=$value";
			}
			$url = ShaUtilsString::replace($url, "?&", "?");
			$url = ShaUtilsString::replace($url, "&", "&amp;");
			$url = ShaUtilsString::clearDbl($url, "/");
			
			return $url;
		} else {
			return "";
		}

	}

	/**
	 * Return current URL
	*/
	public static function getCurrentUrl() {
		return $_SERVER["REQUEST_URI"];
	}

	/**
     * Convert GET params to hidden input field
     *
     * @param array $ignored Param to ignor
	*/
	public static function getToHidden($ignored){
		$result = "";
		foreach ($_GET as $key => $value) {
			if (!in_array($key, $ignored) && $key != "p" && $key != "l"){
				$result .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
			}
		}
		return $result;
	}

    /**
     * Write URL base
     *
     * @param bool $allURL Write complete URL or not
     *
     * @return string
     */
    public function getUrlBase($allURL = true) {
		$base = ($allURL) ? ShaContext::getSiteFullUrl() : "";
		$language = $this->getValue('language_id');
		$languageAbr = ($language != 0 && $language != "") ? ShaLanguage::drawLanguageUrl($language)."/" : "";
		$url = $this->getValue('page_url');
		$elements = explode("@", $url);
		$leftPart = "/".$languageAbr.$elements[0];
		$leftPart = str_replace("//", "/", $leftPart);
		return $base.$leftPart;
	}

	/**
	 * Return XML for site map
	 *
	 * @param int $languageId Language ID
	 *
	 * @return string
	 */
	public static function createSitemapXML($languageId) {
		
		$html = "<?xml version='1.0' encoding='UTF-8' ?>".PHP_EOL;
		$html .= "
<urlset
xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'
xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9'
>
".PHP_EOL;

		$pages = ShaPage::loadByWhereClause("language_id=".$languageId." AND page_importance > 0 ORDER BY page_importance DESC");
        /** @type ShaPage $page */
		foreach ($pages as $page) {

            $languageClause = ($page->getValue('language_id') != 0) ? " language_id = ".$page->getValue('language_id') : "";
			$page->_getVariableInfo();

			if (count($page->_shaPageObjectsInfo) > 0) {

				foreach ($page->_shaPageObjectsInfo as $key => $value) {
					$lot = 0;
					do {
						$languageClause = ShaCmo::isTranslatingClass($key) ? $languageClause : " 1=1 ";

                        /** @type ShaCmo $className */
                        $className = $key;
						$items = $className::getList($languageClause,"", $lot, 100);
						foreach ($items as $item) {
							$html.= "
									<url>
										<loc>".$page->writeUrl(array($key => $item), true)."</loc>
										<priority>".$page->getValue('page_importance')."</priority>
									</url>
								";
							$lot += 100;
						}
					} while (count($items) > 0);
				}
			} else {
				$html.= "
                    <url>
                        <loc>".$page->writeUrl(array(), true)."</loc>
                        <priority>".$page->getValue('page_importance')."</priority>
                    </url>
                ";
			}

		}
		$html .= "</urlset>".PHP_EOL;
		ShaUtilsArray::clearArray($pages);
		$html = ShaUtilsString::clearDbl($html, " ");
		return $html;
	}

    public static function getAdditionalHtaccess(){
        return self::$additionalHtaccess;
    }

    public static function setAdditionalHtaccess($additional){
        self::$additionalHtaccess = $additional;
    }

	/**
	 * Write .htaccess base
	 *
	 * @return void
	 */
	public static function rebuildHtaccess() {

	    //TODO : use config file
	    $xdebugConf = ShaContext::getConf()->get("env/xdebug");
	    if ($xdebugConf != ""){
	        $xdebug = "XDEBUG_SESSION_START=".$xdebugConf."&";
	    }
	    
		$contentBasic = "
			#Override PHP parameters
			SetEnv REGISTER_GLOBALS 1
			SetEnv PHP_VER 5

			".self::getAdditionalHtaccess()."

			[HTACCESS]
					
			#default image
            #<FilesMatch \".(jpg|png|gif)$\">
            #    ErrorDocument 404 \"/resources/img/cms_no_picture.png\"
            #</FilesMatch>

			#Error pages
			ErrorDocument 404 ".ShaContext::getSiteFullUrl()."/index.php?p=notFound

			#Protect htaccess file
			<Files .htaccess>
			Deny from all
			</Files>

			#Protect password file
			<Files .htpasswd>
			Deny from all
			</Files>

			#Rewriting On
			Options -Indexes +FollowSymLinks -MultiViews
			RewriteEngine On
			#RewriteBase ".ShaContext::getSiteFullUrl()."/

			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{REQUEST_FILENAME} !-d

			RewriteRule ^shaoline/resources/img/$ ".ShaContext::getConf()->get("site/folder")."index.php?".$xdebug."p=1 [L]
			RewriteRule ^shaoline/resources/css/$ ".ShaContext::getConf()->get("site/folder")."index.php?".$xdebug."p=1 [L]
			RewriteRule ^shaoline/resources/js/$ ".ShaContext::getConf()->get("site/folder")."index.php?".$xdebug."p=1 [L]
		";


		$siteMaps = "#Sitemaps".PHP_EOL;
		$languages =  ShaLanguage::getList( "", -1, -1);
		
        /** @type ShaLanguage $language */
		foreach ($languages as $language) {
			$siteMaps .= "RewriteRule ^sitemap-".$language->getValue('language_abr').".xml$ /".ShaContext::getConf()->get("site/folder")."sitemap.php?".$xdebug."l=".$language->getValue('language_id')." [L,R=301] #sitemap.xml ".$language->getValue('language_lib');
			$siteMaps .= "\n";
		}

		$pageRedirection = "#Globales rules".PHP_EOL;
		$pages =  ShaPage::getList("page_active = 1");

        /** @type ShaPage $page */
		foreach ($pages as $page) {
			$rules = $page->getHtaccessUrl(false);
			$pageRedirection .= "RewriteRule ^".$rules[0]." ".$rules[1]." #".$page->getValue('page_comment');
			$pageRedirection = ShaUtilsString::replace($pageRedirection, "?", "?".$xdebug);
			$pageRedirection .= "\n";
		}

		$content = "
			$contentBasic
			$siteMaps
			$pageRedirection
		";

		$htaccess = "
			#############
			# HTPASSWDS #
			#############
		";
		$htpasswdLines = "";
		$htpasswds = ShaContext::getConf()->listParams("security/htpasswds");
		foreach ($htpasswds as $key => $value){
			
			$configPath = "security/htpasswds/".$key."/";
			if (ShaContext::getConf()->get($configPath."active") == "1") {
					
				$type = ShaContext::getConf()->get($configPath."type");
				$path = ShaContext::getConf()->get($configPath."path");
				$sentence = ShaContext::getConf()->get($configPath."sentence");
				$login = ShaContext::getConf()->get($configPath."login");
				$password = ShaContext::getConf()->get($configPath."password");

				if ($type == "file") { $htaccess .= "<Files '$path' >"; }
				if ($type == "folder") { $htaccess .= "<Directory '$path' >"; }
					$htaccess .= '
						AuthUserFile '.ShaContext::getPathToWeb().'.htpasswd
						AuthName "'.$sentence.'"
						AuthType Basic
						Require user '.$login.'
					';
				if ($type == "file") { $htaccess .= "</Files>"; }
				if ($type == "folder") { $htaccess .= "</Directory>"; }
						
				$htpasswdLines .= $login.":".ShaUtilsString::cryptApr1Md5($password).PHP_EOL;
				
			}
			
			ShaUtilsFile::createFile(ShaContext::getPathToWeb().'.htpasswd');
			ShaUtilsFile::writeContent(ShaContext::getPathToWeb().'.htpasswd', $htpasswdLines);
		}

		$content = ShaUtilsString::replace($content, "[HTACCESS]", $htaccess);
		
		if (count($htpasswds) > 0 ){
			$content = ShaUtilsString::replace($content, "\t", "");
			$buffer = ShaUtilsString::clearDbl($content, " ");
			$fp = fopen(ShaContext::getPathToWeb().".htaccess", "w");
			fwrite($fp, $buffer);
			fclose($fp);
		}

	}

	/**
	 * Draw basic meta tags
	 *
	 * @return void
	 */
	public function drawBasicMetatags() {

		//Meta
        echo  '
<!-- Site basic URL -->
<base href="'.ShaContext::getSiteFullUrl().'/" >

<!-- Meta tags -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" >
<meta http-equiv="content-language" content="'.ShaContext::getLanguage()->getValue("language_abr").'" >
<meta name="category" content="'.ShaParameter::get("SITE_CATEGORY").'" >
<meta name="Revisit-After" content="'.$this->getValue('page_client_cache_duration').' days" >
<meta name="robots" content="'.$this->getValue('page_robots').'" >

<!-- favicon -->
<link rel="icon" type="image/png" href="favicon.png" >

<!-- Page info -->
<title>[SHA_TITLE]</title>
<meta name="description" content="[SHA_DESCRIPTION]" >
<meta name="keywords" content="'.$this->writeKeyWord($this->_shaPageObjects).'" >
';
	}

    /**
     * Construct string using specific ShaPage
     *
     * @param $cmoObjects
     * @param $value
     * @param string $glue
     *
     * @return string
     * @throws Exception
     */
    private function _interpretSequence($cmoObjects, $value, $glue = "/", $format = true) {
    	
		$elements = explode(";", $value);
		$sResult = array();
		foreach ($elements as $element) {
			$objectDescription = explode("@", $element);

			if (count($objectDescription)==2) {
                if (is_array($cmoObjects)){
                    if (!isset($cmoObjects[$objectDescription[0]])) {
                    	ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." :  Class not found : ".$objectDescription[0]);
                    	throw new Exception(ShaContext::t("Fatal error occured"));
                    }
                    /** @type ShaCmo $cmoInstance */
                    $cmoInstance = $cmoObjects[$objectDescription[0]];
                } else {
                    /** @type ShaCmo $cmoInstance */
                    $cmoInstance = $cmoObjects;
                }
				$sSequence =  $cmoInstance->getValue($objectDescription[1]);
				$sResult[] = ($format) ? ShaUtilsString::formatForURL($sSequence) : $sSequence;
			} else {
				$sResult[] = ($format) ? ShaUtilsString::formatForURL($objectDescription[0]) : $objectDescription[0];
			}
		}

		return implode($glue, $sResult);
	}

	/**
	 * Write Title
	 *
	 * @param array $cmoObject Objects
	 *
	 * @return string
	 */
	public function writeTitle($cmoObject) {
		return $this->_interpretSequence($cmoObject, $this->getValue('page_title'), "", false);
	}

	/**
	 * Write keywords from object
	 *
	 * @param array $cmoObject Objects
	 *
	 * @return string
	 */
	public function writeKeyWord($cmoObject) {
		return ShaUtilsNetwork::formatForKeyword($this->_interpretSequence($cmoObject, $this->getValue('page_keyword'), "/", false), "");
	}

	/**
	 * Write description
	 *
	 * @param array $cmoObject Objects
	 *
	 * @return string
	 */
	public function writeDescription($cmoObject) {
		return ShaUtilsNetwork::formatForDescription($this->_interpretSequence($cmoObject, $this->getValue('page_description'), "/", false), "");
	}

	/**
	 * Write url from object
	 *
	 * @param array   $cmoObject Objects
	 * @param boolean $allURL   Write all URL or not
	 *
	 * @return string
	 */
	public function writeUrl($cmoObject, $allURL = false) {

        //BASE
		$base = ($allURL) ? ShaContext::getSiteFullUrl() : ShaContext::getConf()->get("site/folder");

        //LANGUAGE
		$language = $this->getValue('language_id');
		$languageAbr = "";
		if ($this->getValue("page_add_language_prefix") == 1){
		    $languageAbr = ($language!=0 && $language!="")?ShaLanguage::drawLanguageUrl($language)."/":"";
		}

        //VARIABLES
		$sUrl = $this->_interpretSequence($cmoObject, $this->getValue('page_url'), "/");
		$sUrlAdditional = $this->_interpretSequence($cmoObject, $this->getValue('page_url_additional'), "/");

        //CLEAN
		$url = $base.$languageAbr.$sUrl.$sUrlAdditional."/";
		$url = ShaUtilsString::replace($url, "//", "/");
        $url = ShaUtilsString::replace($url, "http:/", "http://");
		return $url;
	}

	/**
	 * Write URL with .htaccess format
	 *
	 * @param bool $allURL All URL or not
	 *
	 * @return array
	 */
	public function getHtaccessUrl($allURL = false) {

        //Base
		$base = ($allURL) ? ShaContext::getSiteFullUrl() : ShaContext::getConf()->get("site/folder");

        //Languages
		$language = $languageTxt = $languageAbr = "";
		$language = $this->getValue('language_id');
        if ($this->getValue("page_add_language_prefix") == 1){
            $languageAbr = ($language!=0 && $language!="") ? ShaLanguage::drawLanguageUrl($this->getValue('language_id')) : "";
        }
        $languageTxt = ($language!=0 && $language!="") ? "&l=".$this->getValue('language_id') : "";
		
        $urlComponents = $this->_getUrlComponents();

        //Construct condition
        $condition = implode("/", $urlComponents['condition']);
        if ($languageAbr != "") $languageAbr .= "/";
        //$condition = $languageAbr.$condition.'/';
		$condition = $languageAbr.$condition;
        if ($this->getValue("page_need_redirect") == 1) {
        	$condition .= "$";
        } else {
        	$condition .= "?$";
        }
        if ($condition == "/$"){
            $condition = "$";
        }
        
        //Construct action
        $action = implode("", $urlComponents['action']);
        $action = "/index.php?p=".$this->getValue('page_international_name').$languageTxt.$action.$this->getValue("page_get_parameters")." [L]";
        $action = ShaUtilsString::replace($action, "//", "/");
        $action = $base.$action;

		$condition = ShaUtilsString::replace($condition, "//", "/");

		return array($condition, $action);
	}





	/**
	 * Return the value of reference variable
	 *
	 * @param array  $cmoObject Object
	 * @param string $rules    Rules
	 *
	 * @return string
     * @throws Exception
	 */
	private function _getVariableValue($cmoObject, $rules) {

		$result = "";
		$parts = explode(";", $rules);
		foreach ($parts as $part) {
			$elements = explode("@", $part);
			if (count($elements)==2) {
				if (!isset($cmoObject[$elements[0]])) {
					ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." :  Class not found : ".$elements[0]);
					throw new Exception(ShaContext::t("Fatal error occurred"));
				}
				/** @type ShaCmo $instance */
				$instance = $cmoObject[$elements[0]];
				$result .= ShaUtilsNetwork::formatForDescription($instance->getValue($elements[1]));
			} else {
				$result .= ShaUtilsNetwork::formatForDescription($part);
			}
		}
		return $result;
	

	}

	/**
	 * Return variable info
	 *
	 * @return void
	 */
	private function _getVariableInfo() {
		$this->_shaPageObjectsInfo = array();
		$index = 0;

		$vars = $this->getValue('page_variables');
        if ($vars == null){
            return;
        }
		$vars = ShaUtilsString::replace($vars, "\r\n", "");
		$vars = ShaUtilsString::replace($vars, "\n", "");
		$vars = ShaUtilsString::replace($vars, "\r", "");

		//Get all object descriptions
		$objectDescriptions = explode(";", $vars);

        if (count($objectDescriptions) > 0){
            //Instanciate each needed object for this page
            foreach ($objectDescriptions as $objectDescription) {
                if ($objectDescription != "") {
                    $objectDescription = explode("@", $vars);
                    $this->_shaPageObjectsInfo[$objectDescription[0]] = $objectDescription[1];
                    $index++;
                }
            }
        }

	}


    /**
     * Return recap of all variables used in url
     */
    private function _getUrlComponents(){
        $result = array(
            'condition' => array(),
            'action' => array(),
            'variables' => array()
        );

        //Variables
        $url = $this->getValue('page_url');
        $elements = explode(";", $url);

        $index = 0;
        foreach ($elements as $element) {

            $objectDescription = explode("@", $element);
            if (count($objectDescription) == 2) {

                /** @var ShaCmo $class */
                $class = $objectDescription[0];
                /** @var ShaCmo $instance */
                $instance = new $class();
                $field = $instance->getField($objectDescription[1]);

                if (in_array($field->getNormalizedType(), array("UNSIGNEDINT", "INT"))){
                    $pattern = "([0-9]+)";
                    $result['variables'][] = array('class' => $class, 'field' => $objectDescription[1]);
                } else {
                    $pattern = "([a-zA-Z0-9_-]+)";
                }

                $result['condition'][] = $pattern;
                $result['action'][] =  "&".$class."_".$objectDescription[1]."=$".($index + 1);
                $index++;
            } else {
                $result['condition'][] = $element;
            }
        }
        return $result;
    }

	/**
	 * Load variable informations
	 */
	private function _getVariables() {

		$this->_shaPageObjects = array();
        $urlComponents = $this->_getUrlComponents();
        foreach ($urlComponents['variables'] as $variable){
            $getParamName =  $variable['class']."_".$variable['field'];
            if (!isset($_GET[$getParamName])){
                ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Missing param '".$getParamName."' for loading page");
                throw new Exception(ShaContext::t("Fatal error occured"));
            }
            $class = $variable['class'];
            /** @var ShaCmo $instance */
            $instance = new $class();
            if (!$instance->load($_GET[$getParamName])) {
                $this->_shaPageObjects[$variable['class']] = null;
            } else {
                $this->_shaPageObjects[$variable['class']] = $instance;
            }

        }

	}

	/**
	 * Draw analytics script
	 *
	 * @return string
	 */
	public function drawAnalytics() {

		if ($this->getValue('page_add_analytics')!="") {
            return "
<script type='text/javascript'>
 (function(i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function() {
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

 ga('create', '".ShaParameter::get('GOOGLE_ANALYTIC_ID')."', 'auto');
 ga('send', 'pageview');
</script>
";
		}
        return "";

	}


	/**
	 * Draw facebook balises
	 *
	 * @param array $cmoObject objects
	 *
	 * @return string
	 */
	public function drawFacebook($cmoObject) {
		if ($this->getValue('page_active_facebook') == "1") {
			return '
<!-- Facebook link -->
<meta property="og:title" content="[SHA_JS_TITLE]" >
<meta property="og:type" content="article" >
<meta property="og:url" content="'.$this->writeUrl($this->_shaPageObjects, true).'" >
<meta property="og:site_name" content="'.ShaContext::getSiteFullUrl().'" >
<meta property="og:description" content="[SHA_JS_DESCRIPTION]" >
			';
		}
        return "";
	}

	/**
	 * Add content for facebook description
	 *
	 * @param array $cmoObject objects
	 *
	 * @return string
	 */
	public function drawFacebookDescription($cmoObject) {
		if ($this->getValue('page_active_facebook') != "1") {
            return '<div style="width:0px;height:0px;overflow: hidden;">'.$this->_getVariableValue($cmoObject, $this->getValue('page_description')).'</div>';
		}
        return "";
	}

	/**
	 * Draw twitter balises
	 *
	 * @param array $cmoObject objects
	 *
	 * @return string
	 */
	public function drawTwitter($cmoObject) {
		if ($this->getValue('page_active_twitter') == "1") {
			
			self::addJsScriptForEndOfPage("
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');	
			");
			
            return '
<!-- Twitter link -->
<meta name="twitter:card" content="summary" >
<meta name="twitter:url" content="'.$this->writeUrl($this->_shaPageObjects, true).'" >
<meta name="twitter:title" content="[SHA_JS_TITLE]" >
<meta name="twitter:description" content="[SHA_JS_DESCRIPTION]" >
			';
		}
        return "";
	}

	public static function regenerateGets(){
		//Additional GET parameters
		if (isset($_SERVER["REQUEST_URI"])){
			$requestQuery = $_SERVER["REQUEST_URI"];
			$strParams = explode("?", $requestQuery);
			if (count($strParams) > 1) {
				array_shift($strParams);
				$strParams = implode("?", $strParams);
				$strParams = explode("&", $strParams);
				foreach ($strParams as $strParam) {
					$param = explode("=", $strParam);
					if (count($param) == 2) {
						$_GET[$param[0]] = $param[1];
					}
				}
			}
		}
		
	}
	
	/**
	 * Draw page
	 */
	public static function draw() {

		$includeFilePath = ShaContext::getPathToBase().ShaParameter::get("INCLUDE_TPL");
	
        ob_start();

		$requestQuery = $_SERVER["REQUEST_URI"];
		
		//Additional GET parameters
		//self::regenerateGet();
		/*
		$requestQuery = $_SERVER["REQUEST_URI"];
		$strParams = explode("?", $requestQuery);
		if (count($strParams) > 1) {
			array_shift($strParams);
			$strParams = implode("?", $strParams);
			$strParams = explode("&", $strParams);
			foreach ($strParams as $strParam) {
				$param = explode("=", $strParam);
				if (count($param) == 2) {
					$_GET[$param[0]] = $param[1];
				}
			}
		}*/
		
		//Get page ID
		$pageId = ShaUtilsArray::getPOSTGET("p");
		$originalPage = $pageId;
		if (!ShaUtilsString::isRegexVariable($pageId)) {
			//404 page
			ShaUtilsLog::security(__CLASS__."::".__FUNCTION__." : Security : Incorrect 'p' fromat : '$pageId' ($requestQuery)");
			$pageId = self::CONST_ID_PAGE_404;
		}

		
		//Get language ID
		$languageId = ShaUtilsArray::getPOSTGET("l");
		$originalLanguage = $languageId;
		if (!ShaUtilsString::isRegexPositiveInteger($languageId)) {
			$languageId = 1;
		}

		
        if (!ShaContext::setLanguage($languageId) && $languageId > 0) {
			ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : language unknown ".$languageId." ($requestQuery)");
			$languageId = 1;
		}
	
		//Get page
		$page = new ShaPage();

        //Check if it is maintenance
        if (ShaParameter::get('MAINTENANCE') == '1') {
		
			header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 3600');

            //if (!ShaContext::getUser()->isAuthentified() || !ShaContext::getUser()->isAdmin()){
                $pageId = 'maintenance';
                $languageId = 1;
            //}
        }


		$pageFound = $page->load(array('page_international_name' => $pageId, 'language_id' => $languageId));
		if (!$pageFound) {
			
			header("HTTP/1.0 404 Not Found"); 
			header('Status: 404 Page Not Found');
			
			ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Page not found : p = '$originalPage', l = '$originalLanguage' ($requestQuery)");
			
			if (!$page->load(array('page_international_name' => 'notFound', 'language_id' => 0))) {
				
				//ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : 404 page not found in database ! ($requestQuery)");
                //throw new Exception(ShaContext::t("Fatal error occured : no 404 page found !"));
				echo "404 page not found !";
				return;
			}
                
		}

		//Write variable
		$page->_getVariables();

        self::$_title = $page->writeTitle($page->_shaPageObjects);
        self::$_description =  $page->writeDescription($page->_shaPageObjects);

        //Increment qty of see
		$page->setPersistentValue("page_qty_visite", ( (int)$page->getValue("page_qty_visite") + 1));

		if ($page->getValue("page_structure") == "0"){
			$tpl = ShaContext::getPathToBase().$page->getValue('page_template').".tpl.php";
			if (is_file($tpl)){
                echo  include_once $tpl;
                echo "[SHA_INCLUDE_ONE]";
			} else {
				ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Template not found : '$tpl' ($requestQuery)");
			}
			return;
		}

		//start of page
        //echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.PHP_EOL;
        //echo  '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'.PHP_EOL;
        //echo  "<html ".ShaContext::writeXHTMLHead()." ".ShaContext::writeFacebookHead().">".PHP_EOL;
        echo '<!DOCTYPE html>'.PHP_EOL;
        echo '<html lang="'.ShaContext::getLanguage()->getValue("language_abr").'" prefix="fb: http://www.facebook.com/2008/fbml">'.PHP_EOL;
        
		//start of head
        echo  "<head>".PHP_EOL;

		//Draw basic metatags
		$page->drawBasicMetatags();


		//Adding Cms inclusion
        echo '<!-- Shaoline CSS -->'.PHP_EOL;
        echo ShaContext::insertCss();
        echo '<!-- Shaoline JS -->'.PHP_EOL;
        echo ShaContext::insertJs();

		//Reccurent js and css includes
        
		if (is_file($includeFilePath)){
			echo '<!-- Custom includes -->'.PHP_EOL;
			echo include_once ShaContext::getPathToBase().ShaParameter::get("INCLUDE_TPL");
			echo "[SHA_INCLUDE_ONE]";
		}		

		//Specific JS
		$specificJsFiles = explode(";", $page->getValue('additional_js'));
        echo '<!-- Custom JS -->'.PHP_EOL;
		foreach ($specificJsFiles as $specificJsFile) {
			if (trim($specificJsFile)!="") {
                echo '<script type="text/javascript" src="'.$specificJsFile.'"></script>'.PHP_EOL;
			}

		}

        //Specific CSS
        $specificCssFiles = explode(";", $page->getValue('additional_css'));
        echo '<!-- Custom CSS -->'.PHP_EOL;
        foreach ($specificCssFiles as $specificCssFile) {
            if (trim($specificCssFile)!="") {
                echo '<link type="text/css" rel="stylesheet" href="'.$specificCssFile.'" />'.PHP_EOL;
            }
        }

		//Draw Facebook code
        echo $page->drawFacebook($page->_shaPageObjects);
		//Draw Twitter code
        echo $page->drawTwitter($page->_shaPageObjects);

		//end of head
        echo "</head>".PHP_EOL;

		//start of body
        echo "<body>".PHP_EOL;

		//Precode
		if (self::$preBodyCode != "") {
            echo self::$preBodyCode;
		}

        echo "<div class='shaoline_page_body'>".PHP_EOL;

		//Face book aditional content for description
        echo $page->drawFacebookDescription($page->_shaPageObjects);

		//Adding Cms needed HTML DIV
        echo ShaContext::insertHtmlDiv();

		//Header
		if ($page->getValue('page_add_header')=="1") {
            echo  include_once ShaContext::getPathToBase().ShaParameter::get("HEADER_TPL");
            echo "[SHA_INCLUDE_ONE]";
		}

		//Content of page
		if ($page->getValue("page_add_body")=="1") {
            echo "<div id='cms_body' class='".$page->getValue('page_css_class')."'>".PHP_EOL;
		}

		$tpl = ShaContext::getPathToBase().$page->getValue('page_template').".tpl.php";
		if (is_file($tpl)){
            echo  include_once $tpl;
            echo "[SHA_INCLUDE_ONE]";
		} else {
			ShaUtilsLog::error(__CLASS__."::".__FUNCTION__." : Template not found : '$tpl'");
			echo "Page template not found !";
		}

        echo "<div style='clear:both'></div>".PHP_EOL;

		if ($page->getValue("page_add_body") == "1") {
            echo "</div>".PHP_EOL;
		}

		//Footer
		if ($page->getValue('page_add_footer')=="1") {
            echo  include_once ShaContext::getPathToBase().ShaParameter::get("FOOTER_TPL");
            echo "[SHA_INCLUDE_ONE]";
		}

        echo self::_addAdminBlock();


		//end of body
        echo "</div>".PHP_EOL;

        if (count(self::$_endOfPageJsScript) > 0){
            foreach (self::$_endOfPageJsScript as $scriptUrl){
                echo "<script type='text/javascript' src='".$scriptUrl."' ></script>".PHP_EOL;
            }
        }

        //Add jJS at end of file
        if (count(self::$_endOfPageJs) > 0) {
            echo "<script type='text/javascript'>";
            foreach (self::$_endOfPageJs as $script){
                echo $script.PHP_EOL;
            }
            echo "</script>";
        }

        //Draw google analytics code
        echo $page->drawAnalytics();

        echo "
            <script type='text/javascript'>
                ".ShaGarbageCollector::getGcJsCode()."
                <!-- Capture mouse motions -->
                UtilsWindow.launchWindowsMoveSystem();
                UtilsWindow.launchWindowsUpSystem();
            </script>
        ";
       
        $flashMessages = ShaSession::get("cms_flash_message");
        if (is_array($flashMessages)){
            $content = ShaUtilsGraphique::drawListLike(
                array(
                    'domId' => 'cms_flash_message',
                    'btn' => (count($flashMessages) > 0)
                ),
                $flashMessages
            );
            echo ShaContext::cmsPopinWithJsBalise(
                ShaContext::t("Messages"),
                $content
            );

            echo 
                "<script type='text/javascript'>".
            	ShaUtilsGraphique::launchCycle(
            		array(
            			'domId' 				=> '#cms_flash_message',
            			'fx' 					=> 'scrollLeft',
            			//'pager' 				=> '#cms_flash_message_pager',
            			//'pagerAnchorBuilder' 	=> '<div></div>',
            			'timeout' 				=> 3000
            		)
            	).
            	"</script>"
            ;
            

        }
        
        ShaSession::clear("cms_flash_message");

        echo "
        	<script type='text/javascript'>
        		".ShaContext::declareRsaPublicKey()."
        	</script>
        ";

        echo "</body>".PHP_EOL;

        echo "</html>".PHP_EOL;

        //self::$_pageHtml = ob_get_contents();
        self::$_pageHtml = ob_get_clean();
        //ob_end_clean();
        self::$_pageHtml = ShaUtilsString::replace(self::$_pageHtml, "[SHA_TITLE]", self::$_title);
        self::$_pageHtml = ShaUtilsString::replace(self::$_pageHtml, "[SHA_DESCRIPTION]", ShaUtilsString::replace(self::$_description, '"', "`"));

        self::$_pageHtml = ShaUtilsString::replace(self::$_pageHtml, "[SHA_JS_TITLE]", ShaUtilsString::replace(self::$_title, '"', "`"));
        self::$_pageHtml = ShaUtilsString::replace(self::$_pageHtml, "[SHA_JS_DESCRIPTION]", ShaUtilsString::replace(self::$_description, '"', "`"));

        self::$_pageHtml = ShaUtilsString::replace(self::$_pageHtml, "1[SHA_INCLUDE_ONE]", "");
        self::$_pageHtml = ShaUtilsString::replace(self::$_pageHtml, "[SHA_INCLUDE_ONE]", "");
        echo self::$_pageHtml;
	}

	/**
	 * Add admin block
	 *
	 * @return string
	 */
	private static function _addAdminBlock() {

		if (ShaContext::getUser()->isAuthentified() && ShaContext::getUser()->gotPermission("ACCESS_DESKTOP")) {

			$backgroundColor = "background-color:".ShaParameter::get('ADMIN_BLOCK_BACKGROUND_COLOR').";";
			$fontColor = "color:".ShaParameter::get('ADMIN_BLOCK_FONT_COLOR').";";
			$position = ShaParameter::get('ADMIN_BLOCK_POSITION');

			if ($position=="bottom") {
				$position = "bottom:0px;width:100%;height:30px;";
				$gotoAdminPageCssSyle = "left:10px;top:8px;";
				$gotoDeconnectionPageCssSyle = "right:10px;top:5px;";
			} else if ($position=="top") {
				$position = "top:0px;width:100%;height:30px;";
				$gotoAdminPageCssSyle = "left:10px;top:8px;";
				$gotoDeconnectionPageCssSyle = "left:10px;top:8px;";
			} else if ($position=="right") {
				$position = "right:0px;width:150px;height:300px;";
				$gotoAdminPageCssSyle = "left:10px;top:10px;";
				$gotoDeconnectionPageCssSyle = "left:10px;bottom:10px;";
			} else if ($position=="left") {
				$position = "left:0px;width:150px;height:300px;";
				$gotoAdminPageCssSyle = "left:10px;top:10px;";
				$gotoDeconnectionPageCssSyle = "left:10px;bottom:10px;";
			} else {
				$position = "bottom:15px;width:100%;height:15px;";
				$gotoAdminPageCssSyle = "left:10px;top:8px;";
				$gotoDeconnectionPageCssSyle = "right:10px;top:8px;";
			}

			$cssStyle = " style='".$backgroundColor.$fontColor.$position."' ";
			$gotoAdminPageCssSyle = " style='".$gotoAdminPageCssSyle."' ";
			$gotoDeconnectionPageCssSyle = " style='".$gotoDeconnectionPageCssSyle."' ";

            $disconnectResponse = new ShaResponse();
            $disconnectResponse
                ->setRenderer(ShaResponse::CONST_RENDERER_REDIRECT)
                ->setContent(ShaResponse::CONST_URL_CURRENT_PAGE)
            ;

            $operationDisconnect = new ShaOperationAction();
            $operationDisconnect
                ->setDaoClass("ShaUser")
                ->setDaoMethod("disconnect")
                ->setShaResponse($disconnectResponse)
                ->save()
            ;

            return "
                <div id='cms_admin_block' $cssStyle>
                    <div id='cms_admin_block_internal'>
                        <a class='goto_admin_page' $gotoAdminPageCssSyle href='".ShaContext::getAdminPath().".php' >".ShaContext::t("gotoAdminPage")."</a>
                        <div class='deconnection' $gotoDeconnectionPageCssSyle ".$operationDisconnect->getDomEvent()." >".ShaContext::t("deconnection")."</div>
                    </div>
                </div>
			";
		}
        return "";
	}

    /**
     * Add JS for end of page
     *
     * @param $script
     */
    public static function addJsScriptForEndOfPage($script){
        self::$_endOfPageJs[] = $script;
    }

    /**
     * Add JS importation
     *
     * @param $scriptUrl
     */
    public static function addJsScriptUrlForEndOfPage($scriptUrl){
        self::$_endOfPageJsScript[] = $scriptUrl;
    }

}

?>