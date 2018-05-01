<?php


/**
 * Description of ShaUserGroup
 *
 * @category   Cms
 * @package    Core
 * @subpackage Component
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaUserGroup  extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName() {
		return "shaoline_user_group";
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
            ->addField("user_id")->setType("BIGINT UNSIGNED")->setPrimary()->end()
            ->addField("group_key")->setType("VARCHAR(50)")->setPrimary()->setIndex()->end()
        ;

        return $table;

	}

}

?>