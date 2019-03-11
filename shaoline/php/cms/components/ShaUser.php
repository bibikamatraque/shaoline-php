<?php
/**
 * Description of ShaUser
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
class ShaUser extends ShaCmo
{
	//Internal boolean to check if user is authentified
	private $_isAuthentified = false;
	//Internal permissions array
	private $_permissions = array();


	/**
	 * Return table name concerned by object
	 *
	 * @return string
	*/
	public static function getTableName(){
		return "shaoline_user";
	}


	/**
	 * Return SQL crating request
	 *
	 * @return ShaBddTable
	 */
	public static function getTableDescription() {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("user_id")->setType("BIGINT UNSIGNED")->setAutoIncremental()->end()
            ->addField("user_login")->setType("VARCHAR(50)")->setIndex("UNIQUE")->end()
            ->addField("user_pwd")->setType("VARCHAR(256)")->end()
            ->addField("user_salt")->setType("TEXT")->end()
            ->addField("user_mail")->setType("VARCHAR(100)")->setIndex()->end()
            ->addField("user_avatar")->setType("TEXT")->end()
            ->addField("user_language")->setType("MEDIUMINT UNSIGNED")->setDefault(1)->end()
            ->addField("user_country")->setType("MEDIUMINT UNSIGNED")->end()
            ->addField("user_admin")->setType("TINYINT")->setDefault(0)->end()
            ->addField("user_magic")->setType("TEXT")->end()
            ->addField("user_magic_datetime")->setType("VARCHAR(19)")->setDefault('0000-00-00 00:00:00')->end()
            ->addField("user_active")->setType("TINYINT")->setDefault(1)->end()
            ->addField("user_creation_date")->setType("TIMESTAMP")->setDefault("CURRENT_TIMESTAMP")->end()
            ->addField("user_date_last_activity")->setType("VARCHAR(19)")->setDefault('0000-00-00 00:00:00')->end()
            ->addField("user_last_ip")->setType("TEXT")->end()
            ->addField("user_validated")->setType("TINYINT")->setDefault(0)->end()
            ->addIndex("user_login, user_pwd")

            ->addReference("groups")
                ->setType("n:n")
                ->setThrough("ShaUserGroup")->using("user_id")
                ->setTo("ShaGroup")->using("group_key")
                ->end()
        ;
        return $table;

	}

	/**
	 * Return array of field type descriptions
	 *
	 * @return array
	 */
	public function defaultLineRender(){

        $form = new ShaForm();
        $form
            ->setLineHeight(45)
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("user_avatar")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setWidth(40)->end()
            ->addField()->setDaoField("user_id")->setLibEnable(false)->setWidth(75)->end()
            ->addField()->setDaoField("user_login")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("user_mail")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("user_creation_date")->setLibEnable(false)->setWidth(250)->end()
        ;
        return $form;

	}


	/**
	 * Return array of field type descriptions for formulaire
	 *
	 * @return array
	 */
	public function defaultEditRender(){

        $reinitPassword = new ShaOperationAction();
        $reinitPassword
            ->setDaoClass("ShaController")
            ->setDaoMethod("initAndSendMagicKey")
            ->setParameters(array('userId' => $this->getValue('user_id')))
            ->setActiveConfirmation(true)
            ->setConfirmationMsg(ShaContext::t("AreYouSure"))
            ->save()
        ;
        $buttonReinitPassword = "<div class='cms_button' style='width:200px;' ".$reinitPassword->getDomEvent().">".ShaContext::t("SendMagicKey")."</div>";

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->addField()->setDaoField("user_avatar")->setLib(ShaContext::t("Avatar"))->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setWidth(350)->end()
            ->addField()->setDaoField("user_login")->setLib(ShaContext::t("Login"))->setWidth(350)->end()
            ->addField()->setDaoField("user_mail")->setLib(ShaContext::t("Mail"))->setWidth(350)->end()
            ->addField()->setDaoField("user_creation_date")->setLib(ShaContext::t("Creation"))->setEditable(false)->setWidth(350)->end()
            ->addField()->setRelation("groups")->setLib("Show groups")->end()
            ->addField()->setInputEnable(false)->setLib($buttonReinitPassword)->end()
        ;
        return $form;

	}


	/**
	 * Get the user connection state
	 *
	 * @return boolean
	 */
	public function isAuthentified(){
		return $this->_isAuthentified;
	}

	/**
	 * Set the user connection state
	 *
	 * @param bool $value Is authentified
	 *
	 * @return void
	 */
	public function setAuthentified($value){
		$this->_isAuthentified = $value;
	}

    /**
     * Return true if user is admin
     *
     * @return bool
     */
    public function isAdmin(){
        return ($this->getValue("user_admin") == 1);
    }

    /**
     * Try login using magic key
     *
     * @return int
     */
    public function tryMagic(){

        if(isset($_GET['magicKey'])) {

        	sleep(1);
        	
            if (!ShaUtilsString::isRegexVariable($_GET['magicKey'])) {
            	ShaUtilsLog::security("Security : Bad magicKey : ".$_GET['magicKey']);
                return -1;
            }
            /** @var ShaUser $user */
            $user = ShaUser::loadByWhereClause(" BINARY user_magic = '".$_GET['magicKey']."' ", true);

            if ($user) {

            	if (ShaUtilsDate::getAge($user->getValue('user_magic_datetime')) > ShaParameter::get('USER_MAGIC_DURATION') ) {
            		ShaUtilsLog::warn("Too old magicKey used for user ".$user->getValue("user_id"));
            		return -3;
            	}
            	
                $user
                	->setValue('user_validated', 1)
                	->setValue('user_magic', '')
                	->setValue('user_last_ip', ShaContext::getClientIp())
                	->setValue('user_date_last_activity', date('Y-m-d h:i:s'))
                	->save()
                ;
                
                $userId = $user->getValue('user_id');
                $this->load(array('user_id' => $userId));
                $this->_isAuthentified = true;
                ShaContext::updateUserId();
                return 1;
            } else {
                return -2;
            }

        } else {
            return 0;
        }
    }

    /**
     * Try to find connexion attempts in $_POST or $_GET parameters
     *
     * @param array $post Need : login and password
     * @param array $get Need : login and password
     *
     * @return int
     */
    public function tryConnection($login, $password, $useMail = false){

        //Time security
    	sleep(1);

        /** @var ShaUser $user */
		$user = null;
        $login = ShaUtilsString::cleanForBalise($login);
        $login = ShaUtilsString::cleanForSQL($login);
        $password = ShaContext::securityEncode($password);
        
        if ($useMail) {
			
            $user = ShaUser::loadByWhereClause(
                " BINARY user_mail = '$login' AND
                  BINARY user_pwd = '$password'
				"
                , true
            );
        } else {

            $user = ShaUser::loadByWhereClause(
                " BINARY user_login = '$login' AND
                  BINARY user_pwd = '$password'
				"
                , true
            );
        }

        $shaIpSecurityChecker = ShaIpSecurityChecker::get(ShaContext::getClientIp());
        if (isset($user)) {

            if ($user->getValue('user_validated') == 0) {
                return -2;
            }

            $shaIpSecurityChecker->setValue('user_id', $user->getValue('user_id'));
            $shaIpSecurityChecker->save();

            $user
            	->setValue('user_last_ip', ShaContext::getClientIp())
            	->setValue('user_date_last_activity', date('Y-m-d h:i:s'))
            	->save()
            ;

            $userId = $user->getValue('user_id');
            $this->load(array('user_id' => $userId));
            $this->_isAuthentified = true;
            ShaContext::updateUserId();
            return 0;

        } else {
        	
        	//Check if qty of connexion by time
        	if (!$shaIpSecurityChecker->isValid()){
        		$shaIpSecurityChecker->addAttempt();
        		return -3;
        	}
        	
        	$shaIpSecurityChecker->addAttempt();
            return -1;
        }
			

	}

	/**
	 * Load permission in internal array
	 *
	 * @param boolean $forceReload Force the reload of permission from BDD (default = false)
	 *
	 * @return boolean Return true if user is loggued
	 */
	private function _loadPermission($forceReload=false) {
		if ($this->getValue("user_id")>0 && ($forceReload || count($this->_permissions)==0)) {
			ShaUtilsArray::clearArray($this->_permissions);
			$sRequest = "
				SELECT permission_key FROM 
				shaoline_user_group join shaoline_group using (group_key)
				JOIN shaoline_group_permission using (group_key)
				JOIN shaoline_permission using (permission_key)
				WHERE user_id = ".$this->getValue("user_id")."
				GROUP BY permission_key;
			";
			$oRecordset = ShaContext::bddSelect($sRequest);
			while ($oRecord = $oRecordset->fetchAssoc()) {
				$this->_permissions[] = $oRecord["permission_key"];
			}

		}
	}

	/**
	 * Return true if the user have got the permission
	 * 
	 * @deprecated : use hasPermission($parameterKey)
	 * 
	 * @param array $parameterKey Permission ID
	 *
	 * @return boolean
	 */
	public function gotPermission($parameterKey){
		$this->_loadPermission();
		return in_array($parameterKey, $this->_permissions);
	}
	
	
	/**
	 * Return true if the user have got the permission
	 *
	 * @param array $parameterKey Permission ID
	 *
	 * @return boolean
	 */
	public function hasPermission($parameterKey){
		$this->_loadPermission();
		return in_array($parameterKey, $this->_permissions);
	}


	/**
	 * Return HTML code with Cms login formulaire
	 *
	 * @return string
	 */
	public static function drawPopinFormulaireLogin(){
		return ShaContext::cmsPopinWithJsBalise(
            ShaContext::tj("authentification"),
            "<div id='login_form_popin'>".self::getLoginForm('login_form_popin', true)->render()."</div>",
            "cms_default_popin"
        );
    }

	/**
	 * Return HTML code with Cms login formulaire
	 * 
	 * @param boolean $bActiveButton Add valid button or not
     * @param boolean $userEmail Add valid button or not
	 * 
	 * @return ShaForm
	 */
	public static function getLoginForm($id, $bActiveButton = false, $userEmail = false){

        $ShaOperation = new ShaOperationAction();
        $ShaOperation
            ->setDaoClass("ShaUser")
            ->setDaoMethod("getForgetPasswordForm")
            ->setNoMask(false)
            ->save()
        ;

        $form = new ShaForm();
        $form
            ->setDomId("login_form")
            ->setSubmitFunction("ShaController::tryConnect")
            ->setDaoAdditionalDatas(
                array(
                    'user_mail'     => $userEmail,
                    'div_dom_id'    => $id."_div"
                )
            )
            ->addField("login")->setLibEnable(false)/*->setRsaProtected()*/->setFormat(ShaUtilsString::PATTERN_ALL)->setPlaceholder(ShaContext::t("Login"))->setSubmitOnEnter(true)->end()
            ->addField("password")->setLibEnable(false)/*->setRsaProtected()*/->setRenderer(ShaFormField::RENDER_TYPE_PASSWORD)->setFormat(ShaUtilsString::PATTERN_ALL)->setPlaceholder(ShaContext::t("Password"))->setSubmitOnEnter(true)->end()
        ;

        $shaIpSecurityChecker = ShaIpSecurityChecker::get(ShaContext::getClientIp());
        if (!$shaIpSecurityChecker->isValid()){
            $form->addField("captcha")
                ->setRenderer(ShaFormField::RENDER_TYPE_CAPCHA)
                ->setFormat(ShaUtilsString::PATTERN_ALPHANUM)
                ->setPlaceholder(ShaContext::t("Please enter previous code"))
                ->setCapcha(
                    array(
                        "capchaWidth"=>200,
                        "capchaHeight"=>50,
                        "capchaNoiseDensity"=>250,
                        "capchaColorBackR"=>255,
                        "capchaColorBackG"=>255,
                        "capchaColorBackB"=>255,
                        "capchaColorFontR"=>42,
                        "capchaColorFontG"=>49,
                        "capchaColorFontB"=>76,
                        "capchaColorNoiseR"=>100,
                        "capchaColorNoiseG"=>120,
                        "capchaColorNoiseB"=>180,
                        "capchaLength"=>6
                    )
                )
                ->end();
            }

            $form->addField("forget")->setInputEnable(false)->setLib("<a ".$ShaOperation->getDomEvent().">".ShaContext::t("forget password ?")."</a>")->end()
         ;

        if ($bActiveButton){
            $form->addField("submit")->setRenderer(ShaFormField::RENDER_TYPE_SUBMIT)->setValue(ShaContext::t("Connect"));
        }

        $form->save();

        return $form;
	}
	
	/**
	 * Return form for password reinit
	 *
     * @param array $parameters Parameters
     *
	 * @return string
	 */
	public static function getForgetPasswordForm($parameters){

        $form = new ShaForm();
        $form
            ->setDomId("forget_password_form")
            ->setSubmitFunction("ShaUser::tryInitPassword")
            ->addField("msg")->setInputEnable(false)->setLib(ShaContext::t("forget_password_instruction_?"))->end()
            ->addField("email")->setLibEnable(false)->setFormat(ShaUtilsString::PATTERN_MAIL)->setPlaceholder(ShaContext::t("enter_your_email"))->setWidth(300)->end()
            ->addField("submit")->setRenderer(ShaFormField::RENDER_TYPE_SUBMIT)->setValue(ShaContext::t("validate"))->end()
            ->save()
        ;

        $shaResponse = new ShaResponse();
        $shaResponse
        	->setRenderDomId("password_forgotten")
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($form->render())
            ->getPopin()
            ->setColor("blue")
            ->setTitle(ShaContext::t("password_forgotten"))
            
        ;

        $shaResponse->render();
	}

	/**
	 * Init user magic key
	 * 
	 * @return string: the code generated
	 */
	public function initMagicKey(){
	    $sCode = $this->getMagicCode();
	    $this->setValue("user_magic", $sCode);
	    $this->setValue("user_magic_datetime", date('Y-m-d H:i:s'));
	    $this->save();
	    return $sCode;
	}
	
    /**
     * Init user magic key and send by mail
     * 
     * @return void
     */
    public function initAndSendMagicKey(){
        $sCode = $this->initMagicKey();
        
        //Send confirmation mail
        $sMailBody = (ShaContext::t("initPasswordMailBody"));
        $sMailBody = ShaUtilsString::replace($sMailBody, "[PAGE]", ShaContext::getSiteFullUrl() . "?magicKey=".$sCode);
        $sMailBody = ShaUtilsString::replace($sMailBody, "[LOGIN]",  $this->getValue("user_login"));

        ShaUtilsMail::sendMail($sMailBody, ShaContext::t("confirmationNeededMailTitle"),  $this->getValue("user_mail"));

    }


    /**
	 * Try init password
	 * $_POST['mail']
	 */
	public static function tryInitPassword(ShaForm $form){

        $email = $form->getField("email")->getSubmitValue();
        $email = ShaUtilsString::cleanForSQL($email);

        $shaResponse = new ShaResponse();
        $shaUser = ShaUser::loadByWhereClause(" BINARY user_mail = '".$email."'", true);
        if ($shaUser != null && $shaUser->getValue('user_validated') == 1){
            $shaUser->initAndSendMagicKey();
        } 
        $shaResponse
        	->addJsActions("UtilsWindow.closeWindow('password_forgotten');")
        	->setRenderDomId("mail_sent")
        	->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
        	->setContent(ShaContext::t("A mail has just been sending you."))
        	->getPopin()
        	->setTitle(ShaContext::t("password_forgotten"))
        	->setColor("blue")
		;
        	
        $shaResponse->render();
	}

	
	/**
	 * Load shortcut
	 *
	 * @return array
	 */
	private function _getShortcut(){
        $result = array();
		$groups = $this->loadByReference('groups');
        /** @type ShaGroup $group */
        foreach ($groups as $group){
            $result = array_merge($result, $group->loadByReference('applications'));
        }
		return $result;
	}

	/**
	 * Draw all the desktop icons of the user
	 *
	 * @return string code
	 */
	public function drawDesktopShortcuts(){
		$applications = $this->_getShortcut();
		$html = "";
		$index = 0;
        /** @type ShaApplication $application */
		foreach ($applications as $application) {
            if ($application->getValue('application_on_desktop')=='1') {
                $x = ($index%7)*100+10;
                $y = (($index-($index%7))/8)*100+10;
                $html .= $application->drawDesktopIcon($x, $y);
                $index++;
            }
		}
		return $html;
	}


	/**
	 * Dir folder id by name
	 *
	 * @param array  &$array Folders
	 * @param string $name   Name
	 *
	 * @return number
	 */
	private static function _dirFolderId(&$array, $name){
		$index=0;
		foreach ($array as $item) {
			if ($item['name']==$name && $item['type']=='folder') {
				return $index;
			}
			$index++;
		}
		return -1;
	}

	/**
	 * Return HTML for shortcut menu
	 *
	 * @return string
	 */
	private function _getMenuShortcuts() {
		$groups = $this->loadByReference('groups');
		$result = array();
        /** @type ShaDao $group */
		foreach ($groups as $group) {
			$apps = $group->loadByReference('applications');
            /** @type ShaDao $app */
			foreach ($apps as $app) {
				if (!isset($result[$app->getValue('application_key')])) {
					$result[] = $app;
				}
			}
		}

		ShaUtilsArray::clearArray($groups);
		ShaUtilsArray::sortObjectArray($result, "getValue('application_menu_order')");
		return $result;
	}

	/**
	 * Draw all the desktop icons of the user
	 *
	 * @return string code
	 */
	public function drawMenuShortcuts() {

		$applications = $this->_getMenuShortcuts();
		$menu = array();
		$tmpMenu = array();
        /** @type ShaApplication $application */
		foreach ($applications  as $application) {
			if ($application->getValue('application_on_menu')=='1') {
				$shortcutPath = $application->getValue('application_menu_position');
				$elementPath = explode("/", $shortcutPath);
				$tmpMenu = &$menu;
				if (count($elementPath)>1 || $elementPath[0]!="") {
					foreach ($elementPath as $element) {
						$folderId = self::_dirFolderId($tmpMenu, $element);
						if ($folderId==-1) {
							$folderId = count($tmpMenu);
							$tmpMenu[]=array('type'=> 'folder', 'name'=>$element, 'children'=> array());
						}
						$tmpMenu = &$tmpMenu[$folderId]['children'];
					}
				}
				$tmpMenu[] = array('type'=> 'item','name'=> $application->drawMenuIcon());
			}
		}

		$output = array();

		ShaUtilsJs::constructMenuType1($output, $menu, "shaoline_menu", 0, 0, "<img alt='folder' src='shaoline/resources_" . ShaPage::getCacheSuffix() . "/img/cms_menu_folder.png' />", true);

		$html = "";
		foreach ($output as $htmlDiv) {
			$html .= $htmlDiv;
		}

		
		$result = "
			<div id='shaoline_menu'>
				".$html."
				<script type='text/javascript'>
					createMenuType1('shaoline_menu','left','none',true,true,false, 0, 0);
					cms_hideMenuType1('shaoline_menu');
				</script>
    		</div>
		";

		return $result;
	}


	/**
	 * Change current language of user
	 *
	 * @param int $languageId Language id
	 *
	 * @return void
	 */
	public function changeLanguage($languageId){
		ShaContext::setLanguage($languageId);
		$this->setPersistentValue('user_language', $languageId);
	}

	/**
	 * Verif before create new user
	 * 
	 * @param array  $configuration Configuration
	 * @param Object $cmsObject     ShaDao object
	 * 
	 * @return string
	 */
	public static function beforeSave($configuration, $cmsObject) {
	
		//check mail
		$iQty = ShaContext::bddSelectValue("SELECT COUNT(1) FROM shaoline_user WHERE user_mail = '".ShaUtilsString::cleanForSQL($cmsObject->getValue("user_mail"))."'");
		if ($iQty>0) {
			return ShaContext::t("This mail already exist !");
		}
	
		//check login
		$iQty = ShaContext::bddSelectValue("SELECT COUNT(1) FROM shaoline_user WHERE user_login = '".ShaUtilsString::cleanForSQL($cmsObject->getValue("user_login"))."'");
		if ($iQty>0) {
			return ShaContext::t("This login already exist !");
		}
	
		return "";
	
	}
	
	/**
	 * Generate magic code
     *
	 * @return string
	 */
	public function getMagicCode(){
		return md5($this->getValue("user_id") ."_". (rand(100, 1000) * $this->getValue("user_id")) . "_" . microtime() . rand(100, 1000));
	}
	
	/**
	 * CReate confirmation mail
	 * 
	 * @param array  $configuration Configuration
	 * @param Object $cmsObject     ShaDao object
	 * 
	 * @return boolean
	 */
	public static function createConfirmationMail($configuration, $cmsObject){
		
		if (!isset($configuration['confirmationPage'])) {
			return false;
		}
		
		//ADD MAGIC KEY
		if (!$cmsObject->load(array('user_id'=>$cmsObject->getValue('user_id')))) {
			return false;
		}
		$sCode = $cmsObject->getMagicCode();
		$cmsObject->setValue("user_magic", $sCode);
        $cmsObject->setValue("user_magic_datetime", date('Y-m-d H:i:s'));
		$cmsObject->save();
		
		//ADD USER TO GROUP
		$ShaUserGroup = new ShaUserGroup();
		$ShaUserGroup->setValue("user_id", $cmsObject->getValue('user_id'));
		$ShaUserGroup->setValue("group_key", "user");
		$ShaUserGroup->save();
		
		//Send confirmation mail 
		$sMailBody = (ShaContext::t("confirmationNeededMailBody"));
		$sMailBody = ShaUtilsString::replace($sMailBody, "[PAGE]", $configuration['confirmationPage']);
		$sMailBody = ShaUtilsString::replace($sMailBody, "[CODE]", $sCode);
		$sMailBody = ShaUtilsString::replace($sMailBody, "[LOGIN]",  $cmsObject->getValue("user_login"));
		
		ShaUtilsMail::sendMail($sMailBody, ShaContext::t("confirmationNeededMailTitle"),  $cmsObject->getValue("user_mail"));
				
		$sMailBody = ShaContext::t("newUserMailBody");
		$sMailBody = ShaUtilsString::replace($sMailBody, "[LOGIN]", $cmsObject->getValue("user_login"));
		$sMailBody = ShaUtilsString::replace($sMailBody, "[MAIL]", $cmsObject->getValue("user_mail"));
		
		ShaUtilsMail::sendMailToAdmin($sMailBody, ShaContext::t("newUserMailTitle"));

		header("Location : /");
		
	}

    /**
     * Return 'disconnect' operation action
     *
     * @param string $redirect
     *
     * @return string
     */
    public static function getDisconnectGc($redirect = "/"){

		if ($redirect == ""){
			$redirect = "/".ShaContext::getAdminPath().".php";
		}
	
    	$response = new ShaResponse();
    	$response
    		->setRenderer(ShaResponse::CONST_RENDERER_REDIRECT)
    		->setContent($redirect)
    	;
    	
        $ShaOperation = new ShaOperationAction();
        $ShaOperation
            ->setDaoClass("ShaUser")
            ->setDaoMethod("disconnect")
            ->setShaResponse($response)
            ->save()
        ;

		return $ShaOperation->getDomEvent();
	}
	
	/**
	 * Disconnect cms user
	 * 
	 * @param array $aParam Unuser user
	 * 
	 * @return void
	 */
	public static function disconnect($aParam = null){
        ShaSession::set("cms_current_user_id", null);
		return;
	}

	/**
	 * Return qty of valid user
	 * 
	 * @return void
	 */
	public static function getQtyValidUser() {
		 return self::bddSelectValue("
		 	SELECT count(1) as qty FROM shaoline_user 
		 	WHERE 
		 		user_active = 1
		  ");

	}
	
	/**
	 * Return true if user got an avatar
	 * 
	 * @return void
     */
	public function hasAvatar(){
		 
		$avatar = $this->getValue("user_avatar");
		$size = strlen($avatar) ;
		 
		if ( empty($avatar) )
			return false;;
		 
		if ( ($size > 11 ) && (substr($avatar, $size - 11, 11) == "/avatar.png" ) )
			return false;
		 
		return true;
		 
	}
	
}

?>