<?php
/**
 * Tracution Cms object
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
class ShaFlashMessage extends ShaCmo
{


	private static $_keyValues;
	
	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_flash_message";
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
            ->addField("message_id")->setType("INT")->setAutoIncremental()->end()
            ->addField("message_text")->setType("TEXT")->end()
            ->addField("message_start_date")->setType("VARCHAR(10)")->setDefault("0000-00-00")->end()
            ->addField("message_stop_date")->setType("VARCHAR(10)")->setDefault("0000-00-00")->end()
            ->addField("message_only_for_logged")->setType("TINYINT")->end()
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
            ->addField()->setDaoField("message_id")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setWidth(50)->end()
            ->addField()->setDaoField("message_text")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setWidth(250)->end()
            ->addField()->setDaoField("message_start_date")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setWidth(125)->end()
            ->addField()->setDaoField("message_stop_date")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setWidth(125)->end()
            ->addField()->setDaoField("message_only_for_logged")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setWidth(75)->end()
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
            ->addField()->setDaoField("message_id")->setLib(ShaContext::t("Id")." : ")->setRenderer(ShaFormField::RENDER_TYPE_TEXT)->setWidth(50)->end()
            ->addField()->setDaoField("message_text")->setLib(ShaContext::t("Text")." : ")->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA)->setWidth(450)->setHeight(100)->end()
            ->addField()->setDaoField("message_start_date")->setLib(ShaContext::t("Start")." : ")->setRenderer(ShaFormField::RENDER_TYPE_DATE)->setWidth(125)->end()
            ->addField()->setDaoField("message_stop_date")->setLib(ShaContext::t("Stop")." : ")->setRenderer(ShaFormField::RENDER_TYPE_DATE)->setWidth(125)->end()
            ->addField()->setDaoField("message_only_for_logged")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_RADIOBOX)->setDatas(array('0'=>'all users', '1' => 'only logged users'))->end()
        ;
        return $form;

	}

	public static function addFlashMessages(){
		
		// Getting message for all user
		$messages = self::bddSelect("SELECT message_text FROM shaoline_flash_message WHERE message_only_for_logged = 0 AND current_date() >= message_start_date AND current_date() <= message_stop_date");
		while ($row = $messages->fetchAssoc()) {
			ShaContext::addFlashMessage($row["message_text"]);
		}
		
		if (ShaContext::getUser()->isAuthentified()){
			// Getting message for logged user
			$messages = self::bddSelect("
			SELECT message_id, message_text
			FROM shaoline_flash_message
			WHERE
				message_only_for_logged = 1 AND
				current_date() >= message_start_date AND
				current_date() <= message_stop_date AND
				message_id NOT IN (SELECT message_id FROM shaoline_user_flash_message WHERE user_id = ".ShaContext::getUser()->getValue("user_id").")
			");
			while ($row = $messages->fetchAssoc()) {
				ShaContext::addFlashMessage($row["message_text"]);
				$shaUserFlashMessage = new ShaUserFlashMessage();
				$shaUserFlashMessage
					->setValue("user_id", ShaContext::getUser().getValue("user_id"))
					->setValue("message_id", $row["message_id"])
					->save()
				;
			}
			
		}
	
	} 
	
}

?>