<?php

/**
 * Class ShaCmo
 * pecific ShaCmo (translation management)
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
abstract class ShaCmoTranslating extends ShaCmo
{

	/**
	 * Adding constructor
	 * Check if 'language_id' field exist (throw exeption if not)
	 */
	function __construct(){
		parent::__construct();
        /** @type ShaCmo $class */
        $class = get_called_class();
		if (!$this->isColumnExist($class::getTableName(), 'language_id')) {
			
			throw new Exception("The table ".$class::getTableName()." must contain a field named 'language_id'");
		}
		$this->setValue('language_id', ShaContext::getLanguageId());
	}

	/**
	 * specific save for cmotranslating object
	 * try to find language_id and other int primary key
	 *
	 * @param bool $check True to check compatibility of all values
	 *
	 * @return void
	 */
	public function save($check = true) {

        /** @type ShaCmo $class */
        $class = get_called_class();

        $languageId = $this->getValue("language_id");

		//Force language id
		if ($languageId == 0 ) {
            $this->setValue("language_id", ShaContext::getLanguageId());
            $languageId = $this->getValue("language_id");
		}

		if ($this->getQtyPrimaryKeys() == 2 && $languageId > 0) {

            $primaryKeys = $this->getPrimaryKeysAsArray();
			foreach ($primaryKeys as $key => $value) {
				if (
                    $key != 'language_id' &&
                    (
                        (
                            $this->getValue($key) == null || ($this->getValue($key) == 0 || $this->getValue($key) == '') &&
                            $this->getField($key)->canBeIncremented()
                        )
                    )
                ){
                    $value = $this->bddSelectValue("SELECT MAX(".$key.") as id FROM " . $class::getTableName() ." WHERE language_id = ".$languageId);
				    $this->setValue($key, isset($value) ? ((int)$value + 1) : 1);
				}
			}

		}

		parent::save($check);

	}

	/**
	 * Load database row using primary key (and adding shaoline_language if not present)
	 * OVERWRITE : AbstractDao::load($primaryKeys)
	 *
	 * @param array $aPrimaryKeys Array of mapping primaryKey/value
	 *
	 * @return IRecorset
	 * @throws Exception Error description
	 */
	public function load($aPrimaryKeys) {
		if (!is_array($aPrimaryKeys) || !isset($aPrimaryKeys['language_id']) || $this->getValue('language_id') == 0) {
			if (!is_array($aPrimaryKeys)) {
				if ($this->getQtyPrimaryKeys() > 2) {
					die("Bad primary key number");
				}
				$sValue = $aPrimaryKeys;
				$aPrimaryKeys = $this->getOtherPrimarykey();
				$aPrimaryKeys[key($aPrimaryKeys)] = $sValue;
			}
			$aPrimaryKeys['language_id'] = ShaContext::getLanguageId();

		}
		return parent::load($aPrimaryKeys);
	}

	/**
	 * Load database row using primary key (and adding shaoline_language if not present)
	 * OVERWRITE : AbstractDao::loadRecordsetByPrimaryKey($primaryKeys)
	 *
	 * @param array $primaryKeys Array of mapping primaryKey/value
	 *
	 * @return boolean False if error (true if not)
	 * @throws Exception Error description
	 */
	protected function loadRecordsetByPrimaryKey($primaryKeys) {
		if (!isset($primaryKeys['language_id'])  || $this->getValue('language_id')==0) {
			$primaryKeys['language_id'] = ShaContext::getLanguageId();
		}
		return parent::loadByWhereClause($primaryKeys);
	}

	/**
	 * Return array with all primary key but not language_id
	 *
	 * @return array
	 */
	protected function getOtherPrimarykey(){
		$aResult = array();
        $primaryKeys = $this->getPrimaryKeysAsArray();
		foreach ($primaryKeys as $sKey => $sValue) {
			if ($sKey != "language_id") {
				$aResult[$sKey] = $sValue;
			}
		}
		return $aResult;
	}

}
?>