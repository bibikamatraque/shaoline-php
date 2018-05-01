<?php

/**
 * Description of ShaParameter
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
class ShaParameter extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_parameter";
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
            ->addField("parameter_key")->setType("VARCHAR(100)")->setPrimary()->end()
            ->addField("parameter_value")->setType("TEXT")->end()
            ->addField("parameter_description")->setType("TEXT")->end()
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
            ->addField()->setDaoField("parameter_key")->setLibEnable(false)->setWidth(250)->end()
            ->addField()->setDaoField("parameter_value")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("parameter_description")->setLibEnable(false)->setWidth(250)->end()
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
            ->addField()->setDaoField("parameter_key")->setLibEnable(false)->end()
            ->addField()->setDaoField("parameter_value")->setLibEnable(false)->end()
            ->addField()->setDaoField("parameter_description")->setLibEnable(false)->end()
        ;
        return $form;

	}

	/**
	 * Get parameter value
	 * 
	 * @param string $key Parameter name
	 * 
	 * @throws Exception
	 * @return string
	 */
	public static function get($key){
		$myShaParameter = new ShaParameter();
		if (!$myShaParameter->load($key)) {
			ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Parameter not found  : $key");
			die("Missing parameter : ".$key );
		}
		return $myShaParameter->getValue('parameter_value');
	}


	/**
	 * Set parameter value
	 * 
	 * @param string $key   Parameter name
	 * @param string $value Parameter new value
	 * 
	 * @throws Exception
	 */
	public static function set($key,$value){
		$myShaParameter = new ShaParameter();
		if (!$myShaParameter->load($key)) {
			ShaUtilsLog::fatal(__CLASS__."::".__FUNCTION__." : Parameter not found  : $key");
			throw new Exception(ShaContext::t("Fatal error occured"));
		}
		$myShaParameter->setPersistentValue('parameter_value', $value);
	}

	/**
	 * Return true if parameter exist
	 * 
	 * @param string $key Parameter name
	 * 
	 * @return boolean
	 */
	public static function exist($key){
		$myShaParameter = new ShaParameter();
		if (!$myShaParameter->load($key)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Return true if parameter not exist or is egal to ''
	 *
	 * @param string $key Parameter name
	 *
	 * @return boolean
	 */
	public static function isEmpty($key){
		if (!self::exist($key)){
			return true;
		}
		return ('' == self::get($key));
	}

}

?>