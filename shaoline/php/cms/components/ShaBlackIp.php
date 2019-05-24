<?php
/**
 * Description of ShaBlackIp
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
class ShaBlackIp extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_black_ip";
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
            ->addField("id")->setType("INT)")->setPrimary()->setAutoIncremental()->end()
            ->addField("ip")->setType("VARCHAR(100)")->end()
        ;

        return $table;

	}

	/**
	 * Return true if object must be displayed like tree
	 *
	 * @return bool
	 */
	public static function isTreeType(){
		return false;
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
            ->addField()->setDaoField("ip")->setLib("Enter an IP to blacklist : ")->setWidth(250)->end()
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
            ->addField()->setDaoField("ip")->setLib(ShaContext::t("Ip"))->setWidth(300)->end()
        ;
        return $form;

	}
	
	/**
	 * Check if IP is black listed
	 * 
	 * @param string ip
	 * 
	 * @return boolean
	 */
	public static function isBadIp($ip){
	    $result = self::loadByWhereClause(" ip = '" . ShaUtilsString::cleanForSQL($ip) . "' ");
	    return $result != null;
	}

}

?>