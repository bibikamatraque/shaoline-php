<?php



/**
 * Class ShaCountry
 * Cms country
 *
 * @category   Component
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaCountry extends ShaCmo
{


    /**
     * Return table name concerned by object
     *
     * @return string
     */
    public static function getTableName()
    {
        return "shaoline_country";
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
            ->addField("country_id")->setType("INT UNSIGNED")->setAutoIncremental()->end()
            ->addField("country_lib")->setType("VARCHAR(200)")->end()
            ->addField("iso_alpha2")->setType("VARCHAR(2)")->end()
            ->addField("iso_alpha3")->setType("VARCHAR(2)")->end()
            ->addField("iso_numeric")->setType("INT")->end()
            ->addField("currency_code")->setType("VARCHAR(3)")->end()
            ->addField("currency_name")->setType("VARCHAR(50)")->end()
            ->addField("currrency_symbol")->setType("VARCHAR(3)")->end()
            ->addField("country_flag")->setType("text")->end();

        return $table;

    }

    protected static function initData()
    {
        ShaContext::bddInsert("INSERT INTO shaoline_country ( country_lib ) VALUES ('Unknown');");
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
            ->addField()->setDaoField("country_flag")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setWidth(35)->end()
            ->addField()->setDaoField("country_lib")->setLibEnable(false)->setWidth(200)->end()
            ->addField()->setDaoField("country_code")->setLibEnable(false)->setWidth(75)->end()
            ->addField()->setDaoField("country_id")->setLibEnable(false)->setWidth(50)->end();

        return $form;

    }


    /**
     * Return array of field type descriptions for formulaire
     *
     * @return array
     */
    public function defaultEditRender()
    {
        return array(
            array('forcedValue' => '<div style="text-align:center">'),
            array(
                'key'   => 'country_flag', 'canEdit' => false, 'renderer' => ShaFormField::RENDER_TYPE_PICTURE,
                'width' => 75
            ),
            array('forcedValue' => '</div>'),
            array(
                'key'   => 'country_id', 'canEdit' => false, 'renderer' => ShaFormField::RENDER_TYPE_TEXT,
                'label' => ShaContext::tb('Id'), 'width' => 250
            ),
            array(
                'key'   => 'country_lib', 'canEdit' => true, 'renderer' => ShaFormField::RENDER_TYPE_TEXT,
                'label' => ShaContext::tb('Lib'), 'width' => 250
            ),
            array(
                'key'   => 'country_code', 'canEdit' => true, 'renderer' => ShaFormField::RENDER_TYPE_LINK,
                'label' => ShaContext::tb('Abr'), 'width' => 250
            )
        );
    }


}

?>