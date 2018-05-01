<?php
/**
 * Description of ShaUser
 *
 * PHP version 5.3
 *
 * @category   Cms
 * @package    Components
 * @subpackage Default
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaLanguage extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_language";
	}


	/**
	 * Return SQL crating request
	 *
	 * @return string
	 */
	public static function getTableDescription(){

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("language_id")->setType("MEDIUMINT UNSIGNED")->setAutoIncremental()->end()
            ->addField("language_lib")->setType("TEXT")->end()
            ->addField("language_flag")->setType("TEXT")->end()
            ->addField("language_url")->setType("TEXT")->end()
            ->addField("language_abr")->setType("TEXT")->end()
            ->addField("language_locale")->setType("TEXT")->end()
        ;

        return $table;

	}

    public static function init()
    {

        //ShaContext::bddInsert("
        //    INSERT INTO shaoline_language (language_id,language_lib,language_flag,language_abr,language_locale)
        //    VALUES
        //    (1,'Français','shaoline/resources/img/flags/France.png','FR','fr-fr');
        //");

    }

	/**
	 * Return array of field type descriptions
	 *
	 * @return array
	 */
	public function defaultLineRender(){

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("language_id")->setLibEnable(false)->setWidth(50)->end()
            ->addField()->setDaoField("language_lib")->setLibEnable(false)->setWidth(250)->end()
            ->addField()->setDaoField("language_flag")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setWidth(50)->end()
        ;
        return $form;

	}

	/**
	 * Return array of field type descriptions for formulaire
	 *
	 * @return array
	 */
	public function defaultEditRender(){
		return $this->defaultLineRender();
	}

    /**
     * Return HTML list of language availables on website
     *
     * @param bool $bAddLabel
     * @param string $classCss
     * @param bool $onlySession
     * @param int $width
     *
     * @return string
     */
    public static function drawLanguageList($bAddLabel = true, $classCss="", $onlySession=false, $width=15, $forcePage = ""){
		$onlySession = ($onlySession)?"Session":"";
		$currentLanguage = ShaContext::getLanguageId();
		$languages = ShaLanguage::loadByWhereClause("1=1");
		$html = "<ul id='shaoline_language_selector' class='$classCss'>";
		$items = "";

        $url = ($forcePage == "") ? ShaResponse::CONST_URL_CURRENT_PAGE : ShaPage::getUrlFromName($forcePage);

        /** @type ShaLanguage $language */
		foreach ($languages as $language) {
			
			$sLabel = ($bAddLabel) ? $language->getValue('language_lib') : "";

            $operation = new ShaOperationAction();
            $operation
                ->setDaoClass("ShaController")
                ->setDaoMethod("changeLanguage")
                ->setParameters(
                    array(
                        "language_id" => $language->getValue('language_id'),
                        "url" => $url
                    )
                )
                ->save()
             ;

			if ($language->getValue('language_id')!=$currentLanguage) {
				$items.="<li><img style='width:".$width."px' ".$operation->getDomEvent()." alt='".$language->getValue('language_lib')."' src='".$language->getValue('language_flag')."' title='".$language->getValue('language_lib')."' >".$sLabel."</li>";
			} else {
				$html.="<li><img style='width:".$width."px' ".$operation->getDomEvent()." alt='".$language->getValue('language_lib')."' src='".$language->getValue('language_flag')."' title='".$language->getValue('language_lib')."' >".$sLabel."</li>";
			}
		}
		$html .= $items."</ul>";
		return $html;
	}

    /**
     * Return HTML list of language availables on website
     *
     * @param bool $onlySession
     * @param string $classCss
     * @param int $width
     *
     * @todo: use ShaForm
     *
     * @return string
     */
    public static function drawLanguageSelect($onlySession=false, $classCss="", $width=0){
		$onlySession = ($onlySession)?"Session":"";
		$width = ($width>0)?"style='width:".$width."px'":"";
		$currentLanguage = ShaContext::getLanguageId();
		$languages = ShaLanguage::loadByWhereClause();
		$html = "<select ".$width." onchange='cms_changeLanguageSelect$onlySession()' class='$classCss' id='shaoline_language_selector'>";
		$items = "";
        /** @type ShaLanguage $language */
		foreach ($languages as $language) {
			if ($language->getValue('language_id')!=$currentLanguage) {
				$items.="<option value='".$language->getValue('language_id')."'>".$language->getValue('language_lib')."</option>";
			} else {
				$html.="<option value='".$language->getValue('language_id')."'>".$language->getValue('language_lib')."</option>";
			}
		}
		$html .= $items."</select>";
		return $html;
	}

	/**
	 * Return current language ABR
	 * 
	 * @throws Exception
	 * @return string
	 */
	public static function drawCurrentLanguageAbr(){
		$currentLanguage = ShaContext::getLanguageId();
		$language = new ShaLanguage();
		if ($language->load(array('language_id' => $currentLanguage))) {
			return $language->getValue('language_abr');
		} else {
			ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Language not found : $currentLanguage");
			throw new Exception(ShaContext::t("Fatal error occured"));
		}
	}

	/**
	 * Return sepcific language ABR
	 *
	 * @param int $languageId Language ID
	 *
	 * @throws Exception
	 * @return string
	 */
	public static function drawLanguageUrl($languageId){
		$language = new ShaLanguage();
		if ($language->load(array('language_id'=>$languageId))) {
			return $language->getValue('language_url');
		} else {
			ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Language not found : $languageId");
			throw new Exception(ShaContext::t("Fatal error occured"));
		}
	}
	
	
	/**
	 * Return sepcific language ABR
	 * 
	 * @param int $languageId Language ID
	 * 
	 * @throws Exception
	 * @return string
	 */
	public static function drawLanguageAbr($languageId){
		$language = new ShaLanguage();
		if ($language->load(array('language_id'=>$languageId))) {
			return $language->getValue('language_abr');
		} else {
			ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Language not found : $languageId");
			throw new Exception(ShaContext::t("Fatal error occured"));
		}
	}

	/**
	 * Return current locale
	 * 
	 * @throws Exception
	 * @return string
	 */
	public static function drawCurrentLocale(){
		$currentLanguage = ShaContext::getLanguageId();
		$language = new ShaLanguage();
		if ($language->load(array('language_id'=>$currentLanguage))) {
			return $language->getValue('language_locale');
		} else {
			ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Language not found : $currentLanguage");
			throw new Exception(ShaContext::t("Fatal error occured"));
		}
	}
}

?>