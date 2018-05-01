<?php
/**
 * This class is a Content Managed Object (Cmo) used to store various contacts
 * ShaBddTable : shaoline_contact
 *
 * Ex :
 *
 * $contact = new ShaContact();
 * $contact->setValue('contact_surname', 'my_first_conatct');
 * $contact->setValue('contact_mobile', '001122334455');
 * $contact->save();
 * ...
 *
 * @package    Shaoline/Cms/Components
 * @category   ShaCmo class
 *
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <bastien.duho@free.fr>
 *
 * @version    1.0
 */
class ShaContact extends ShaCmo
{


	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_contact";
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
            ->addField("contact_id")->setType("INT UNSIGNED")->setAutoIncremental()->end()
            ->addField("contact_surname")->setType("TEXT")->end()
            ->addField("contact_firstname")->setType("TEXT")->end()
            ->addField("contact_fix")->setType("TEXT")->end()
            ->addField("contact_fax")->setType("TEXT")->end()
            ->addField("contact_mobile")->setType("TEXT")->end()
            ->addField("country_id")->setType("MEDIUMINT")->end()
            ->addField("contact_city")->setType("TINYINT")->end()
            ->addField("contact_zipcode")->setType("TINYINT")->end()
            ->addField("contact_additional_datas")->setType("MEDIUMINT")->end()
            ->addField("contact_latitude")->setType("FLOAT")->end()
            ->addField("contact_longitude")->setType("FLOAT")->end();

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
            ->addField()->setDaoField("contact_surname")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("contact_firstname")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("contact_fix")->setLibEnable(false)->setWidth(100)->end()
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
            ->addField()->setDaoField("contact_id")->setLib(ShaContext::t("Id"))->setEditable(false)->end()
            ->addField()->setDaoField("contact_surname")->setLib(ShaContext::t("Surname"))->end()
            ->addField()->setDaoField("contact_firstname")->setLib(ShaContext::t("Firstname"))->end()
            ->addField()->setDaoField("contact_fix")->setLib(ShaContext::t("Fix"))->end()
            ->addField()->setDaoField("contact_fax")->setLib(ShaContext::t("Fax"))->end()
            ->addField()->setDaoField("country_id")->setLib(ShaContext::t("Country"))->end()
            ->addField()->setDaoField("contact_city")->setLib(ShaContext::t("City"))->end()
            ->addField()->setDaoField("contact_zipcode")->setLib(ShaContext::t("Zipcode"))->end()
            ->addField()->setDaoField("contact_additional_datas")->setLib(ShaContext::t("Additional"))->end()
            ->addField()->setDaoField("contact_latitude")->setLib(ShaContext::t("Latitude"))->end()
            ->addField()->setDaoField("contact_longitude")->setLib(ShaContext::t("Longitude"))->end()
        ;
        return $form;

	}

	
}

?>