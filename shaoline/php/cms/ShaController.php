<?php

class ShaController {

    /**
     * Try to connect user
     *
     * @param ShaForm $form
     * @param ShaDao $instance
     *
     * @return json
     */
    public static function tryConnect($form, $instance = null){

        $data = $form->getDaoAdditionalDatas();

        $useMail = false;
        if (isset($data['user_mail'])) {
            $useMail = $data['user_mail'];
        }

        $shaResponse = new ShaResponse();
        $loginFld = $form->getField("login")->getSubmitValue();
        $passwordFld = $form->getField("password")->getSubmitValue();
        $err = ShaContext::getUser()->tryConnection($loginFld, $passwordFld, $useMail);
        if ($err == 0){
            $shaResponse
                ->setRenderer(ShaResponse::CONST_RENDERER_REDIRECT)
                ->setContent(ShaResponse::CONST_URL_CURRENT_PAGE)
            ;
        } elseif ($err == -2){
            $shaResponse
                ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
                ->setContent(ShaContext::t("mail not verified please click on mail link"))
                ->getPopin()
                    ->setTitle("Error")
                    ->setColor("red")
            ;
        } elseif ($err == -3){
        	
        	ShaUtilsLog::security("Security : Too much login attempts from IP : ". ShaContext::getClientIp());

            if ($form->getField("captcha") == NULL){

                $form
                    ->insertFieldBefore("forget", "captcha")
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
                    ->end()
                    ->save()
                ;
            }

            $shaResponse = new ShaResponse();
            $shaResponse
        		->setContent($form->render())
        		->setRenderer(ShaResponse::CONST_RENDERER_DIV)
        		->setRenderDomId($data['div_dom_id']);

        } else {
            $shaResponse
                ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
                ->setContent(ShaContext::t("Bad login or password"))
                ->getPopin()
                    ->setTitle("Error")
                    ->setColor("red")
            ;
        }

        return $shaResponse->render();

    }



    /**
     * Display CMS object default list
     *
     * @param array $parameters Paremeters
     *  - className
     *
     * @return string
     */
    public static function showCmoObjectList($parameters){

        /** @var ShaCmo $classType */
        $classType = $parameters['className'];

        /** @type ShaOperation $ShaOperation */
        $ShaOperation = null;

        if ($classType::isCmoTreeType()) {
            /** @type ShaOperation $ShaOperation */
            $ShaOperation = new ShaOperationListTree();
            $ShaOperation
                ->setDomId(ShaContext::getNextContentId())
                ->setDaoClass($parameters['className'])
                ->setCssClass("cms_default_admin_tree cms_admin_list")
                ->setParentKey(array("parent" => 0))
                ->save();

        } else {
        	
            /** @type ShaOperationList $ShaOperation */
            $ShaOperation = new ShaOperationList();
            $ShaOperation
                ->setDomId(ShaContext::getNextContentId())
                ->setDaoClass($parameters['className'])
                ->setAutoGenerateHeaderForPopin(true)
                ->setActiveAddButton(true)
                ->setActiveEditBtn(true)
                ->setActiveDeleteButton(true)
                ->save()
            ;

        }

        $ShaOperation
            ->setActiveAddButton(true)
            ->setActiveDeleteButton(true)
            ->setActiveEditBtn(true)
            ->setActiveRefreshButton(true)
        ;
        $shaResponse = new ShaResponse();
        $shaResponse
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($ShaOperation->run())
            ->setRenderDomId(ShaContext::getNextContentId())
            ->getPopin()
            	->setTitle(ShaContext::tt("Default view : %0%", $classType))
            	->setColor("blue")
            	->setStyle('padding:0px')
        ;
        return $shaResponse->render();
    }

    /**
     * Load user
     * Genre magic key
     * Send it to user
     *
     * @param $parameters
     *
     * @return string
     * @throws Exception
     */
    public static function initAndSendMagicKey($parameters){

        $userId = $parameters['userId'];
        if (!ShaUtilsString::isRegexPositiveInteger($userId)){
            throw new Exception(
                __CLASS__."::".__FUNCTION__." : Bad user id format !"
            );
        }

        $user = new ShaUser();
        if (!$user->load($userId)){
            throw new Exception(
                __CLASS__."::".__FUNCTION__." : Bad user id !"
            );
        }

        $user->initAndSendMagicKey();

        $shaResponse = new ShaResponse();
        $shaResponse
            ->setRenderDomId(ShaContext::getNextContentId())
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent(ShaContext::t("magicMailCorreclySentToUser"))
            ->getPopin()
                ->setColor("blue")
                ->setTitle(ShaContext::t("SendingNewMagicKey"))
        ;
        return $shaResponse->render();
    }

    /**
     * Change current language
     *
     * @param array $parameters
     *
     * @return string
     */
    public static function changeLanguage($parameters) {
        ShaContext::setLanguage($parameters['language_id']);

        $shaResponse = new ShaResponse();
        $shaResponse
            ->setRenderer(ShaResponse::CONST_RENDERER_REDIRECT)
            ->setContent($parameters['url'])
        ;
        return $shaResponse->render();
    }

    /**
     * Return action result into popin
     *
     * @param array $parameters
     *     - gcId => action id
     *     - title => popin title
     *
     * @return string
     */
    public static function popinAction($parameters) {

        if (!ShaUtilsString::isRegexPositiveInteger($parameters['gcId'])){
            return ShaContext::t("Bad operation Id");
        };

        $operation = ShaOperation::load($parameters['gcId']);
        if (!isset($operation)){
            return ShaContext::t("Bad operation Id");
        }

        $shaResponse = new ShaResponse();
        $shaResponse
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($operation->run())
            ->getPopin()
                ->setColor("blue")
                ->setTitle($parameters['title'])
        ;

        return $shaResponse->render();
    }

    
    /**
     * Return batches progress
     */
    public static function getProgress(){
    	
    	if (!ShaContext::getUser()->isAuthentified() || !ShaContext::getUser()->isAdmin()){
    		return "";
    	}
		
        //Load all others components
    	$content = "";

        ////////////////////////////////
        // Load Shaoline advancements //
        ////////////////////////////////

        //Resource copying advancements
        if (ShaTreatmentInfo::hasInfo('TASK', 'PUSH_RESOURCE')){
            $content .= '
                <br/>COPYING RESOURCES
                <br/>
                <br/>Task :'.ShaTreatmentInfo::getInfo("TASK", "PUSH_RESOURCE").'
                <br/>Current :'.ShaTreatmentInfo::getInfo("CURRENT", "PUSH_RESOURCE").' / '.ShaTreatmentInfo::getInfo("TOTAL", "PUSH_RESOURCE").'
                <br/>'.ShaUtilsGraphique::drawPurcentageBar(ShaTreatmentInfo::getInfo("CURRENT", "PUSH_RESOURCE"), ShaTreatmentInfo::getInfo("TOTAL", "PUSH_RESOURCE"), 250).'
                <br/>
            ';
			
        }

        //Load all plugins advancements
        $plugins = ShaContext::getConf()->get("plugins");
        if (is_array($plugins)) {
            foreach ($plugins as $plugin) {
                /** @var ShaPlugin $obj */
                $obj = new $plugin();
                $content .= $obj->getProgress();
                unset($obj);
            }
        } 
        return $content;
    }
    
}



?>