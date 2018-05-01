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
class ShaGroupApplication extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName() {
		return "shaoline_group_application";
	}

	/**
	 * Return SQL crating request
	 *
	 * @return string
	 */
	public static function getTableDescription() {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("group_key")->setType("VARCHAR(50)")->setPrimary()->end()
            ->addField("application_key")->setType("VARCHAR(50)")->setPrimary()->setIndex()->end()
        ;

        return $table;

	}

	/**
	 * Add application permission for specified group
	 *  
	 * @param string $group       Groupe name
	 * @param int    $application Application ID
	 * 
	 * @return void
	 */
	public static function addApplication($group, $application){
		self::bddInsert("INSERT INTO shaoline_group_application (group_key, application_key) VALUES ('".$group."', '".$application."')", false);
	}


	/**
	 * Add all application permission for specified group
	 * 
	 * @param string $group Groupe name
	 * 
	 * @return void
	 */
	public static function addAllApplications($group){
        self::bddInsert("INSERT INTO shaoline_group_application (group_key, application_key) (SELECT '".$group."', application_key  FROM shaoline_application)", false);
	}

}
?>