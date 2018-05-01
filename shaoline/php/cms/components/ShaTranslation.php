<?php
/**
 * Tracution Cms object
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
class ShaTranslation extends ShaCmoTranslating
{


	private static $_keyValues;
	
	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_translation";
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
            ->addField("translation_key")->setType(" VARCHAR(100)")->setPrimary()->end()
            ->addField("language_id")->setType("MEDIUMINT UNSIGNED")->setPrimary()->setIndex()->end()
            ->addField("translation_value")->setType("TEXT")->end()
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
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("language_id")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(ShaLanguage::getValuesMapping("language_id", "language_flag"))->setWidth(20)->end()
            ->addField()->setDaoField("translation_key")->setLibEnable(false)->setWidth(250)->end()
            ->addField()->setDaoField("translation_value")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA)->setWidth(500)->end()
        ;
        return $form;

	}

	/**
	 * Return array of field type descriptions for formulaire
	 *
	 * @return array
	 */
	public function defaultEditRender(){

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->addField()->setLib('Language')->setDaoField("language_id")->setRenderer(ShaFormField::RENDER_TYPE_COMBOBOX)->setDatas(ShaLanguage::getValuesMapping("language_id", "language_lib"))->setWidth(150)->end()
            ->addField()->setLib('Key')->setDaoField("translation_key")->setWidth(250)->end()
            ->addField()->setLib('Value')->setDaoField("translation_value")->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA)->setWidth(500)->setHeight(300)->end()
        ;
        return $form;

	}

    /**
     * Define if 'add' button is present by default in list
     *
     * @return bool
     */
    public static function hasButtonAdd(){
        return false;
    }

	/**
	 * Draw the translation of a content (if not existe, creat an empty entry in the datase)
	 *
	 * @param string $contentKey Content key
	 * @param int    $type       Display type
	 * @param bool   $editMode   Is edit mode
	 *
	 * @return string
	 */
	public function draw($contentKey, $type = ShaFormField::RENDER_TYPE_TEXT,$editMode = false){
		$this->_checkingInsertion($contentKey);
		return $this->internalDrawValue('translation_value', $type, $editMode);
	}

	/**
	 * Return the translation of a content (if not existe, creat an empty entry in the database)
	 *
	 * @param string $contentKey Id of content to translate
	 *
	 * @return string
	 */
	public function translate($contentKey){
		$this->_checkingInsertion($contentKey);
		$value =  $this->getValue("translation_value");
		return ($value=='[no translation]')?"[".$contentKey."]":$value;
	}
	
	/**
	 * Return the translation of a content (if not existe, creat an empty entry in the database)
	 *
	 * @param string $contentKey Id of content to translate
	 *
	 * @return string
	 */
	public function translateBold($contentKey){
		return "</b>".$this->translate($contentKey)."</b>";
	}

	/**
	 * Return the translation of a content with quote protection (if not existe, creat an empty entry in the database)
	 *
	 * @param string $contentKey Id of content to translate
	 *
	 * @return string
	 */
	public function translateJs($contentKey){
		$this->_checkingInsertion($contentKey);
		$value =  $this->getValue("translation_value");
		return ($value=='[no translation]')?"[".$contentKey."]":ShaUtilsString::cleanForJs($value);
	}

	/**
	 * Check if the translation of a content exist or not (if not existe, create an empty entry in the database)
	 *
	 * @param string $contentKey Id of content to translate
     * @throws Exception
	 */
	private function _checkingInsertion($contentKey){
		if (!isset(self::$_keyValues)) {
			self::loadTranslations();
		}
		
		if (isset(self::$_keyValues[$contentKey])) {
			$this->setValue("translation_value", self::$_keyValues[$contentKey]);
			return;
		}
		
		if (!$this->load(array('translation_key'=>$contentKey))) {
			$this->bddInsert(
				"INSERT INTO shaoline_translation
				(translation_key, translation_value, language_id)
				VALUES
				('".ShaUtilsString::cleanForSQL($contentKey)."','[no translation]',".ShaContext::getLanguageId().")", false
			);
			if (!$this->load(array('translation_key' => $contentKey))) {
				ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Disable to create new entry in shaoline_translation table");
				throw new Exception(ShaContext::t("Fatal error occured"));
			}
			self::$_keyValues[$contentKey] = $this->getValue("translation_value");
			$this->setValue("translation_value", self::$_keyValues[$contentKey]);
		}
	}
	
	/**
	 * Load all word translations
	 */
	public static function loadTranslations(){
		self::$_keyValues = array();
		$recordset = self::bddSelect("SELECT translation_key as translationKey, translation_value as translationValue FROM shaoline_translation WHERE language_id = ".ShaContext::getLanguageId());
        while ($record = $recordset->fetchAssoc()) {
			self::$_keyValues[$record["translationKey"]] = $record["translationValue"];
		}
	}
}

?>