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
class ShaGroupPermission extends ShaCmo
{


	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName() {
		return "shaoline_group_permission";
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
            ->addField("permission_key")->setType("VARCHAR(100)")->setPrimary()->setIndex()->end()
        ;

        return $table;

    }

	/**
	 * Add permission for specified group
	 * 
	 * @param string $group      Id group
	 * @param string $permission Permisison name
	 * 
	 * @return void
	 */
	public static function addPermissions($group,$permission){
		self::bddInsert("INSERT INTO shaoline_group_permission (group_key, permission_key) (SELECT '".$group."', '".$permission."')", false);
	}

	/**
	 * Add all permission for specified group
	 *
	 * @param string $group Group name
	 *
	 * @return void
	 */
	public static function addAllPermissions($group){
        self::bddInsert("INSERT INTO shaoline_group_permission (group_key, permission_key) (SELECT '".$group."', permission_key FROM shaoline_permission)", false);
	}

}

?>