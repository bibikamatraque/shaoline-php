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
class ShaUserFlashMessage  extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName() {
		return "shaoline_user_flash_message";
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
            ->addField("message_id")->setType("BIGINT UNSIGNED")->setPrimary()->setIndex()->end()
        ;

        return $table;

	}

}

?>