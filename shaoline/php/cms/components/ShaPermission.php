<?php

/**
 * Description of ShaUser
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
class ShaPermission extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_permission";
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
            ->addField("permission_key")->setType("VARCHAR(100)")->setPrimary()->end()
            ->addField("permission_description")->setType("TEXT")->end()
        ;
        return $table;

	}

	/**
	 * Return array of field type descriptions for formulaire
	 *
	 * @return array
	 */
	public function defaultLineRender(){

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("permission_key")->setLibEnable(false)->setWidth(300)->end()
            ->addField()->setDaoField("permission_description")->setLibEnable(false)->setWidth(300)->end()
        ;
        return $form;
	}


	/**
	 * Return array of field type descriptions
	 *
	 * @return array
	 */
	public function defaultEditRender(){

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->addField()->setDaoField("permission_key")->setLib(ShaContext::t("Key"))->end()
            ->addField()->setDaoField("permission_description")->setLib(ShaContext::t("Description"))->end()
        ;
        return $form;
	}

	/**
	 * Test if CMO object permission already existe
	 *
	 * @param string $className CMO class name
	 *
	 * @return boolean
	 */
	//public static function permissionAlreadyExist($className){
	//	return self::bddExist("SELECT 1 FROM shaoline_permission WHERE permission_key = '".AbstractCmo::ShaOperation_LIST . "_" . $className ."' LIMIT 1");
	//}


	/**
	 * Add permission
	 *
	 * @param string $name        Permission name
	 * @param string $description Description  of permission
	 *
	 * @return void
	 */
	//public static function createPermission($name, $description){
	//	if (!self::permissionAlreadyExist($name)) {
	//		Dao::bddUpdateOrInsert("INSERT INTO shaoline_permission (permission_key,permission_description) VALUES ('".ShaUtilsString::cleanForSQL($name)."','".ShaUtilsString::cleanForSQL($description)."');", false);
	//		ShaGroupPermission::addPermissions(1, $name);
	//	}
	//}

	/**
	 * Add permission for CMO object
	 *
	 * @param string $className CMO class name
	 *
	 * @return void
	 */
	//public static function createClassPermission($className){
	//	if (!self::permissionAlreadyExist(AbstractCmo::ShaOperation_LIST . "_" . $className)) {
	//		Dao::bddUpdateOrInsert("INSERT INTO shaoline_permission (permission_key,permission_description) VALUES ".self::createBasicPermissions($className).";", false);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_LIST. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_DELETE. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_SHOW_ADD_FORMULAIRE. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_SHOW_EDIT_FORMULAIRE. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_SHOW_EDIT_FIELD. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_SHOW_ADD_MAPPING. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_EDIT_FIELD. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_TREE. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_SHOW_EDIT_TREE_FORMULAIRE. "_" . $className);
	//		ShaGroupPermission::addPermissions(1, AbstractCmo::ShaOperation_LAUNCH_FUNCTION. "_" . $className);
	//	}
	//}


	/**
	 * Create Cms  basic permission for class name
	 *
	 * @param int $className CMO class name
	 *
	 * @return string
	 */
	//public static function createBasicPermissions($className) {
//
	//	return
	//	"
	//		('" . AbstractCmo::ShaOperation_LIST . "_" . $className . "','List " . $className . " type contents'),
	//		('" . AbstractCmo::ShaOperation_DELETE . "_" . $className . "','Delete " . $className . " type contents'),
	//		('" . AbstractCmo::ShaOperation_SHOW_ADD_FORMULAIRE . "_" . $className . "','Show adding form for " . $className . " type contents'),
	//		('" . AbstractCmo::ShaOperation_SHOW_EDIT_FORMULAIRE . "_" . $className . "','Show editing form for " . $className . " type contents'),
	//		('" . AbstractCmo::ShaOperation_SHOW_EDIT_FIELD . "_" . $className . "','Show editing form for " . $className . " fileds'),
	//		('" . AbstractCmo::ShaOperation_SHOW_ADD_MAPPING . "_" . $className . "','Show add mapping " . $className . " type contents'),
	//		('" . AbstractCmo::ShaOperation_EDIT_FIELD . "_" . $className . "','Editing " . $className . " fields'),
	//		('" . AbstractCmo::ShaOperation_TREE . "_" . $className . "','Editing tree " . $className . " fields'),
	//		('" . AbstractCmo::ShaOperation_SHOW_EDIT_TREE_FORMULAIRE . "_" . $className . "','Editing tree " . $className . " fields'),
	//		('" . AbstractCmo::ShaOperation_LAUNCH_FUNCTION . "_" . $className . "','Launch class methode')
	//	";
	//}
	
}

?>