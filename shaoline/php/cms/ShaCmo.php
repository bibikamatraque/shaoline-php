<?php

/**
 * Class ShaCmo
 * ShaCmo extended objects are based on ShaDao class and contain 'Content Manager System' additional methodes
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
abstract class ShaCmo extends ShaDao
{

    /**
     * Return configuration for list display type
     *
     * @return array
     */
    public function defaultLineRender()
    {
        $form = new ShaForm();
        $form
            ->setSubmitable(false)
            ->setDaoClass(get_called_class())
        ;

        $fields = $this->getFields();
        /** @type ShaBddField $field */
        foreach ($fields as $field) {
            $form->addField($field->getName())->end();
        }
        return $form;
    }

    /**
     * Return configuration for formulaire display type
     *
     * @return array of field type descriptions
     */
    public function defaultEditRender()
    {
        $form = new ShaForm();
        $form
            ->setDaoClass(get_called_class());

        $fields = $this->getFields();
        /** @type ShaBddField $field */
        foreach ($fields as $field) {
            $form->addField($field->getName())->end();
        }
        return $form;
    }

    /**
     * Specific render functions
     *
     * @param array $config Configuration
     *
     * @return string
     */
    public function renderFunction($config = null)
    {
        return "";
    }


    /**
     * Return true if object must be displayed like tree
     *
     * @return bool
     */
    public static function isTreeType(){
        return false;
    }

    /**
     * Return true if class must be displayed like tree
     *
     * @return bool
     */
    public static function isCmoTreeType(){
        $className = get_called_class();
        /** @var ShaCmo $className */
        return $className::isTreeType();
    }

    /**
     * Define if 'add' button is present by default in list
     *
     * @return bool
     */
    public static function hasButtonAdd(){
        return true;
    }

    /**
	 * Define if 'add' button is present by default in list
	 *
	 * @return bool
	 */
    public static function hasButtonDelete(){
        return true;
    }

    /**
     * Return true if the class got field (language_id)
     *
     * @param string $className Name of class to test
     *
     * @return bool
     */
    public static function isTranslatingClass($className)
    {
    	$instance = new $className();
        return $instance->hasField('language_id');
    }

    /**
     * Return list of DAO using carrousel mode
     *
     * @param array $aConfig Configuration
     *        class            => class name
     *        condition        => sql condition
     *        sort            => fields sort
     *        qty                => qty to get
     *      height            => carrousel height
     *      width            => carrousel width
     *      renderfunction    => render function
     *      domId            => Dom id
     *        timeout            => Carrousel timeout
     *
     *  @return HTML
     */
    public static function displayDaoLikeCarrousel($aConfig)
    {

        $sSortCondition = (isset($aConfig['sort'])) ? " ORDER BY " . $aConfig['sort'] . " " : "";
        $sLimitCondition = (isset($aConfig['qty'])) ? " LIMIT " . $aConfig['qty'] . " " : "";
        $sWhereCondition = (isset($aConfig['condition'])) ? $aConfig['condition'] . " " : "";

        $oClass = new $aConfig['class']();
        $oDaoList = $oClass::loadByWhereClause($sWhereCondition . $sSortCondition . $sLimitCondition);
        $sResult = "<ul id='" . $aConfig['domId'] . "'>";
        foreach ($oDaoList as $oDao) {
            $sResult .= "<li>" . $aConfig["function"]($oDao) . "</li>";
        }
        $sResult .= "</ul>
			<script type='text/javascript'>
				jCarouselLite(" . $aConfig['domId'] . ",1000,speed,visible,vertical)
			</script>
		";

        return $sResult;
    }

    public function drawEditFormulaire()
    {
        return "";
    }


    /**
     * Return sql language condition
     *
     * @return string
     */
    protected function getLanguageSqlCondition()
    {
        return " AND language_id = " . self::getLanguageId();
    }

}

