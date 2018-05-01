<?php



/**
 * Class ShaGroup
 * Manage group
 *
 * @category   Component
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaGroup extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_group";
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

            ->addField("group_key")->setType("VARCHAR(50)")->setPrimary()->end()
            ->addField("group_description")->setType("TEXT")->end()

            ->addReference("permissions")
                ->setType("n:n")
                ->setThrough("ShaGroupPermission")->using('group_key')
                ->setTo("ShaPermission")->using('permission_key')
                ->end()

            ->addReference("applications")
                ->setType("n:n")
                ->setThrough("ShaGroupApplication")->using("group_key")
                ->setTo("ShaApplication")->using("application_key")
                ->end()
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
            ->addField()->setDaoField("group_key")->setLibEnable(false)->setWidth(100)->end()
            ->addField()->setDaoField("group_name")->setLibEnable(false)->setWidth(150)->end()
            ->addField()->setDaoField("group_description")->setLibEnable(false)->setWidth(250)->end()
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
            ->addField()->setInputEnable(false)->setLib(ShaContext::t("Global informations"))->setCssClass("cms_title")->end()
            ->addField()->setDaoField("group_key")->setLib(ShaContext::t("Id"))->end()
            ->addField()->setDaoField("group_name")->setLib(ShaContext::t("Name"))->end()
            ->addField()->setDaoField("group_description")->setLib(ShaContext::t("Description"))->end()
            ->addField()->setInputEnable(false)->setLib(ShaContext::t("Other informations"))->setCssClass("cms_title")->end()
            ->addField()->setRelation("permissions")->setLib(ShaContext::t("Show permissions"))->end()
            ->addField()->setRelation("applications")->setLib(ShaContext::t("Show application"))->end()
        ;
        return $form;

	}


}
?>