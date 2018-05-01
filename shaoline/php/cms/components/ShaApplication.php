<?php
/**
 * This class is a Content Managed Object (Cmo) used to store your website applications
 *
 * ShaBddTable : shaoline_application
 *
 * Ex :
 *
 * $application = new ShaApplication();
 * $application->setValue('application_name', 'my_first_application');
 * $application->save();
 * ...
 *
 * @package     Shaoline/Cms/Components
 * @category    ShaCmo class
 *
 * @license     Contact bastien.duho@free.fr
 * @author      Bastien DUHOT <bastien.duho@free.fr>
 *
 * @version     1.0
 */
class ShaApplication extends ShaCmo
{

    /**
     * Return table name concerned by object
     *
     * @return string
     */
    public static function getTableName()
    {
        return "shaoline_application";
    }

    /**
     * Return SQL crating request
     *
     * @return string
     */
    public static function getTableDescription()
    {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("application_key")->setType("VARCHAR(50)")->setPrimary()->end()
            ->addField("application_name")->setType("TEXT")->end()
            ->addField("application_icon")->setType("TEXT")->end()
            ->addField("application_function")->setType("TEXT")->end()
            ->addField("application_menu_position")->setType("TEXT")->end()
            ->addField("application_description")->setType("TEXT")->end()
            ->addField("application_on_desktop")->setType("TINYINT")->end()
            ->addField("application_on_menu")->setType("TINYINT")->end()
            ->addField("application_desktop_order")->setType("MEDIUMINT")->end()
            ->addField("application_menu_order")->setType("MEDIUMINT")->end();

        return $table;
    }

    /**
     * Return array of field type descriptions
     *
     * @return array
     */
    public function defaultLineRender()
    {

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->setSubmitable(false)
            ->addField()->setDaoField("application_icon")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setWidth(35)->end()
            ->addField()->setDaoField("application_name")->setLibEnable(false)->setWidth(150)->end()
            ->addField()->setDaoField("application_name")->setLibEnable(false)->setWidth(300)->end();
        return $form;
    }

    /**
     * Return array of field type descriptions for formulaire
     *
     * @return array
     */
    public function defaultEditRender()
    {

        $form = new ShaForm();
        $form
            ->setDaoClass(__CLASS__)
            ->addField()->setDaoField("application_icon")->setLib(ShaContext::t("Icon"))->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->end()
            ->addField()->setDaoField("application_name")->setLib(ShaContext::t("Name"))->end()
            ->addField()->setDaoField("application_description")->setLib(ShaContext::t("Description"))->setRenderer(ShaFormField::RENDER_TYPE_TEXTAREA )->end();
        return $form;

    }

    /**
     * Return HTML for menu icon of application
     *
     * @return string
     */
    public function drawMenuIcon()
    {
        $function = $this->getValue('application_function');
        $fFunctionResult = null;
        eval("\$fFunctionResult = $function;");

        return "
			<div $fFunctionResult class='shaoline_menu_icon' title='" . ShaUtilsString::quoteProtection(
            $this->getValue('application_description')
        ) . "'>
				<img title='" . ShaUtilsString::quoteProtection($this->getValue('application_description')) . "'
					 alt='" . ShaUtilsString::quoteProtection($this->getValue('application_name')) . "'
					 src='" . ShaUtilsString::quoteProtection($this->getValue('application_icon')) . "'
				/>
				<div class='shaoline_menu_icon_label'>" . $this->getValue('application_name') . "</div>
			</div>
		";
    }

    /**
     * Return HTML for desktop icon of application
     *
     * @param int $x Horisontal poisiton
     * @param int $y Vertical position
     *
     * @return string
     */
    public function drawDesktopIcon($x, $y)
    {

        $function = $this->getValue('application_function');
        $result = null;
        eval("\$result = $function;");

        return "
			<div $result class='cms_desktop_icon' title='" . ShaContext::tj(
            ShaUtilsString::quoteProtection($this->getValue('application_description'))
        ) . "' style='position:absolute;top:" . $y . "px;left:" . $x . "px'>
				<img title='" . ShaContext::tj(ShaUtilsString::quoteProtection($this->getValue('application_description'))) . "'
					 alt='" . ShaContext::tj(ShaUtilsString::quoteProtection($this->getValue('application_name'))) . "'
					 src='" . ShaUtilsString::quoteProtection($this->getValue('application_icon')) . "'
				/>
				<div class='cms_desktop_icon_label'>" . ShaContext::t(strtoupper($this->getValue('application_name'))) . "</div>
			</div>
		";

    }

    /**
     * Test if CMO object application already existe
     *
     * @param string $applicationName Application name to test
     *
     * @return boolean
     */
    public static function applicationAlreadyExist($applicationName)
    {
        $qty = ShaContext::bddSelect(
            "SELECT COUNT(1) FROM shaoline_application WHERE application_name = '" . ShaUtilsString::cleanForSQL(
                $applicationName
            ) . "'"
        );
        return $qty >= 1;
    }


    /**
     * Add application in database
     *
     * @param string $name Name
     * @param string $icon Icon path
     * @param string $funct Function when click
     * @param string $menu Position in menu
     * @param string $description Description
     * @param bool $onDesktop Is on desktop
     * @param bool $onMenu Is in menu
     * @param int $desktopOrder Order on desktop
     * @param int $menuOrder Order in menu
     *
     * @return int
     */
    public static function addApplication(
        $name,
        $icon,
        $funct,
        $menu,
        $description,
        $onDesktop,
        $onMenu,
        $desktopOrder = 0,
        $menuOrder = 0
    ) {
        $id = ShaContext::bddInsert(
            "
            INSERT INTO shaoline_application
            (application_name,application_icon,application_function,application_menu_position,application_description,application_on_desktop,application_on_menu,application_desktop_order,application_menu_order)
            VALUES
            ('" . ShaUtilsString::cleanForSQL($name) . "','" . ShaUtilsString::cleanForSQL(
                $icon
            ) . "','" . ShaUtilsString::cleanForSQL($funct) . "','" . ShaUtilsString::cleanForSQL(
                $menu
            ) . "','" . ShaUtilsString::cleanForSQL(
                $description
            ) . "'," . $onDesktop . "," . $onMenu . "," . $desktopOrder . "," . $menuOrder . "),
			"
        );
        return $id;
    }

    /**
     * Add application for specified group
     *
     * @param string $group Group name
     * @param string $name Name
     * @param string $icon Icon path
     * @param string $funct Function when click
     * @param string $menu Position in menu
     * @param string $description Description
     * @param bool $onDesktop Is on desktop
     * @param bool $onMenu Is in menu
     * @param int $desktopOrder Order on desktop
     * @param int $menuOrder Order in menu
     *
     * @return void
     */
    public static function addApplicationForGroup(
        $group,
        $name,
        $icon,
        $funct,
        $menu,
        $description,
        $onDesktop,
        $onMenu,
        $desktopOrder = 0,
        $menuOrder = 0
    ) {
        if (!self::applicationAlreadyExist($name)) {
            $id = self::addApplication(
                $name,
                $icon,
                $funct,
                $menu,
                $description,
                $onDesktop,
                $onMenu,
                $desktopOrder,
                $menuOrder
            );
            ShaGroupApplication::addApplication($group, $id);
        }
    }
}

?>