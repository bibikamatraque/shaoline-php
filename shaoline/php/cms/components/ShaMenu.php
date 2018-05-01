<?php
/**
 * Description of HEADER TEMPLATE
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
class ShaMenu extends ShaCmoTreeTranslating
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_menu";
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
            ->addField("menu_id")->setType("MEDIUMINT UNSIGNED")->setPrimary()->end()
            ->addField("language_id")->setType("MEDIUMINT UNSIGNED")->setPrimary()->setIndex()->refereTo("ShaLanguage")->end()
            ->addField("parent")->setType("INT UNSIGNED")->setIndex()->end()
            ->addField("menu_group")->setType("VARCHAR(50)")->end()
            ->addField("menu_short_lib")->setType("VARCHAR(50)")->end()
            ->addField("menu_long_lib")->setType("TEXT")->setNullable(true)->end()
            ->addField("menu_link")->setType("TEXT")->setNullable(true)->end()
            ->addField("menu_function")->setType("TEXT")->setNullable(true)->end()
            ->addField("menu_picture")->setType("TEXT")->setNullable(true)->end()
            ->addField("menu_order")->setType("SMALLINT UNSIGNED")->end()
        ;

        //$queries = $table->getCreateQuery();
        return $table;

	}

	/**
	 * Return true if object must be displayed like tree
	 *
	 * @return bool
	 */
	public static function isTreeType(){
		return true;
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
            ->addField()->setDaoField("language_id")->setLibEnable(false)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(ShaLanguage::getValuesMapping("language_id", "language_flag"))->setWidth(20)->end()
            ->addField()->setDaoField("menu_short_lib")->setLibEnable(false)->setWidth(250)->end()
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
            ->setDaoClass(self::getTableName())
            ->addField()->setDaoField("language_id")->setEditable(false)->setRenderer(ShaFormField::RENDER_TYPE_SWITCHPICTURE)->setDatas(ShaLanguage::getValuesMapping("language_id", "language_flag"))->setWidth(20)->end()
            ->addField()->setDaoField("menu_group")->setLib(ShaContext::t("Groupe"))->setWidth(300)->end()
            ->addField()->setDaoField("menu_short_lib")->setLib(ShaContext::t("Short lib"))->setWidth(300)->end()
            ->addField()->setDaoField("menu_long_lib")->setLib(ShaContext::t("Long lib"))->setWidth(300)->end()
            ->addField()->setDaoField("menu_link")->setLib(ShaContext::t("Link"))->setWidth(300)->end()
            ->addField()->setDaoField("menu_function")->setLib(ShaContext::t("Function"))->setWidth(300)->end()
            ->addField()->setDaoField("menu_order")->setLib(ShaContext::t("Order"))->setWidth(300)->end()
            ->addField()->setDaoField("menu_picture")->setLib(ShaContext::t("Picture"))->setRenderer(ShaFormField::RENDER_TYPE_PICTURE)->setWidth(50)->end()
        ;
        return $form;

	}

	
	public function getSimpleValue($dDistingFirstChar = false){
		$picture = ($this->getValue('menu_picture')!="") ? $this->drawPicture('menu_picture', false):"";
		$onclick = ($this->getValue('menu_function')!="")?' onclick="'.$this->getValue('menu_function').'" ':"";
		$sText = $this->getValue('menu_short_lib');
		if ($dDistingFirstChar){
			$sText = ShaUtilsGraphique::distinctFirstChar($sText);
		}
		return($this->getValue('menu_link')!="")?
			'<a href="'.$this->getValue('menu_link').'" '.$onclick.'>'.$picture.$sText.'</a>':
			"<div ".$onclick.">".$picture.$sText."</div>";
	}
		
	/**
	 * draw item
	 *
	 * @return string
	 */
	public function drawItem(){
		$picture = ($this->getValue('menu_picture')!="")?$this->drawPicture('menu_picture', false):"";
		$onclick = ($this->getValue('menu_function')!="")?' onclick="'.$this->getValue('menu_function').'" ':"";
		return($this->getValue('menu_link')!="")?
		'<a href="'.$this->getValue('menu_link').'" '.$onclick.'>'.$picture.$this->drawText('menu_short_lib', false).'</a>':
		"<div ".$onclick.">".$picture.$this->drawText('menu_short_lib', false)."</div>";
	}

	/**
	 * Returne children nodes
	 *
	 * @param string $menuGroup  Groupe name
	 * @param int    $languageId Language id
	 *
	 * @return ShaDao array
	 */
	/*public static function getChildrenList($menuGroup,$languageId=null){
			
		$languageId = (!isset($languageId))?self::getLanguageId():$languageId;
		return self::getList("ShaMenu", " menu_group = '".$menuGroup."' AND language_id = ".$languageId." ORDER BY menu_order, menu_id ", -1, -1);
	}*/

	//    /**
	//     * Return HTML code for menu
	//     */
	//    public static function drawMenu($menuGroup, $name,$puce,$decalType,$decalIndex,$decalQty,$languageId=NULL){
	//        $items = self::getChildrenList($menuGroup,$languageId);
	//        $menu = array();$tmpMenu = array();
	//        foreach($items  as $item){
	//            $shortcutPath = $item->getValue('menu_path');
	//            $elementPath = explode("/",$shortcutPath);
	//            $tmpMenu = &$menu;
	//            $index = 0;
	//            if (count($elementPath)>1 || $elementPath[0]!=""){
	//                foreach ($elementPath as $element){
	//                    if (!isset($tmpMenu[$element])){
	//                        $tmpMenu[$element]=array();
	//                    }
	//                    $tmpMenu = &$tmpMenu[$element];
	//                }
	//            }
	//            if (!isset($tmpMenu['Cms_SHORTCUTS']))
	//                $tmpMenu['Cms_SHORTCUTS'] = array();
	//            $tmpMenu['Cms_SHORTCUTS'][] = $item->drawItem();
	//        }
	//        ShaUtilsArray::clearArray($items);
	//
	//        $output = array();
	//        ShaUtilsJs::constructDivsMenu($output,$menu,$name,0,$decalType,$decalIndex,$decalQty,$puce,true);
	//
	//        $html = "";
	//        foreach($output as $htmlDiv){
	//            $html.=$htmlDiv;
	//        }
	//        ShaUtilsArray::clearArray($output);
	//        return $html;
	//    }


	/**
	 * Dir folder id by name
	 *
	 * @param array  &$array Datas
	 * @param string $name   Name
	 *
	 * @return number
	 */
	/*private static function _dirFolderId(&$array, $name){
		$index=0;
		foreach ($array as $item) {
			if ($item['name']==$name && $item['type']=='folder') {
				return $index;
			}
			$index++;
		}
		return -1;
	}*/

	/*public static function fillArrayWithMenuData($menuGroup,  $languageId=null){
		$items = self::getChildrenList($menuGroup, $languageId);
		$menu = array();
		$tmpMenu = array();
		foreach ($items  as $item) {
			$shortcutPath = $item->getValue('menu_path');
			$elementPath = explode("/", $shortcutPath);
			$tmpMenu = &$menu;
			if (count($elementPath)>1 || $elementPath[0]!="") {
				foreach ($elementPath as $element) {
					$folderId = self::_dirFolderId($tmpMenu, $element);
					if ($folderId==-1) {
						$folderId = count($tmpMenu);
						$tmpMenu[]=array('type'=> 'folder','name'=>$element,'children'=> array());
					}
					$tmpMenu = &$tmpMenu[$folderId]['children'];
				}
			}
			$tmpMenu[] = array('type'=> 'item','name'=> $item->drawItem());
		}
		ShaUtilsArray::clearArray($items);
		return $menu;
	}*/

	/**
	 * Return HTML code for menu of type 1
	 *
	 * @param string $menuGroup  Groupe name
	 * @param string $name       Menu DOM name
	 * @param string $puce       HTML puce
	 * @param int    $languageId Force language ID
	 *
	 * @return HTML
	 */
	/*public static function drawMenuType1($menuGroup, $name, $puce, $languageId=null){
		$menu = self::fillArrayWithMenuData($menuGroup, $languageId);
		$output = array();
		ShaUtilsJs::constructMenuType1($output, $menu, $name, 0, 0, $puce, true);

		$html = "";

		foreach ($output as $htmlDiv) {
			$html.=$htmlDiv;
		}
		ShaUtilsArray::clearArray($output);
		return $html;
	}*/

	
	/**
	 * Return HTML code for menu of type 2
	 *
	 * @param string $menuGroup  Groupe name
	 * @param string $name       Menu DOM name
	 * @param string $puce       HTML puce
	 * @param int    $languageId Force language ID
	 *
	 * @return HTML
	 */
	/*public static function drawMenuType2($menuGroup, $name, $languageId=null){
		$menu = self::fillArrayWithMenuData($menuGroup, $languageId);
		$output = array();
		return ShaUtilsJs::constructMenuType2($menu, $name);
	}*/
	
	
	/**
	 * Return HTML code for menu
	 * 
	 * @param string $menuGroup  Groupe name
	 * @param string $name       Menu DOM name
	 * @param string $puce       HTML puce
	 * @param int    $hDirection Horisontal way 
	 * @param int    $vDirection Vertical way 
	 * @param bool   $hUsePanel  Use panel for horisontal positionning
	 * @param bool   $vUsePanel  Use panel for vertical positionning
	 * @param int    $hDecalage  Horisontal decalage
	 * @param int    $vDecalage  Vertical decalage
	 * @param int    $languageId Language id
	 * 
	 * @return string
	 */
	/*public static function advancedDrawMenuType1($menuGroup, $name, $puce, $hDirection, $vDirection, $hUsePanel, $vUsePanel, $hDecalage=0, $vDecalage=0, $languageId=null) {
		$html = "
				<div id='".$name."_menu'>
					".ShaMenu::drawMenuType1($menuGroup, $name, $puce, $languageId)."
					<script type='text/javascript'>
						cms_repositionMenuPanel('$name', '$hDirection', '$vDirection', true, $hUsePanel, $vUsePanel, $hDecalage, $vDecalage);
					</script>
	    		</div>
	    	";
		return $html;
	}*/
	
	/**
	 * Return HTML code for menu
	 *
	 * @param string $menuGroup  Groupe name
	 * @param string $name       Menu DOM name
	 * @param string $puce       HTML puce
	 * @param int    $hDirection Horisontal way
	 * @param int    $vDirection Vertical way
	 * @param bool   $hUsePanel  Use panel for horisontal positionning
	 * @param bool   $vUsePanel  Use panel for vertical positionning
	 * @param int    $hDecalage  Horisontal decalage
	 * @param int    $vDecalage  Vertical decalage
	 * @param int    $languageId Language id
	 *
	 * @return string
	 */
	/*public static function advancedDrawMenuType2($menuGroup, $name, $puce, $hDirection, $vDirection, $hUsePanel, $vUsePanel, $hDecalage=0, $vDecalage=0, $languageId=null) {
		$html = "
		<div id='".$name."_menu'>
			".ShaMenu::drawMenuType2($menuGroup, $name, $languageId)."
			<script type='text/javascript'>
				cms_repositionMenuPanel('$name', '$hDirection', '$vDirection', true, $hUsePanel, $vUsePanel, $hDecalage, $vDecalage);
			</script>
		</div>
		";
		return $html;
	}*/

}

?>