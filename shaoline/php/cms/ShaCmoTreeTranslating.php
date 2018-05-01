<?php


/**
 * Description of AbstractCmoTranslating
 *
 * @category   ShaDao
 * @package    Core
 * @subpackage Core
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    mon-referendum.com copyright
 * @link       No link
 * @deprecated
 */
//TODO: repair class

abstract class ShaCmoTreeTranslating extends ShaCmoTranslating
{


    public function delete(){
        parent::delete();

        //Delete all child if tree
        if ($this->isTreeType()) {
            $child = $this->getTreeChild();
            foreach ($child as $children) {
                $children->delete();
            }
        }
    }

	/**
	 * Convert parent type list to menu type list
	 * 
	 * @param string $sClass           Class name
	 * @param string $sGroupFieldName  ShaBddField name for group
	 * @param string $sGroupFieldValue ShaBddField name for Value
	 * @param string $sAction          JS Action
	 * 
	 * @return array
	 */
	public static function getMenuList($sClass, $sGroupFieldName, $sGroupFieldValue, $sAction){
		//Get root
		$oClass = new $sClass();
		$aTreeObject = $oClass::loadByWhereClause($sGroupFieldName." = '".$sGroupFieldValue."' AND parent = 0");
		if (count($aTreeObject)==0) {
			return null;
		}
		$oTreeObjectRoot = $aTreeObject[0];
	
		$aOutput = array();
		$oTreeObjectRoot->getRecurciveMenuList($aOutput, $sAction);
		return $aOutput;
	}
	

	/**
	 * Convert parent type list to menu type list
	 * 
	 * @param array  &$aOutput Output array
	 * @param string $sAction  Js action
	 * 
	 * @return void
	 */
	public function getRecurciveMenuList(&$aOutput, $sAction){
		$aChild = $this->getTreeChild();
		$index = count($aOutput);
		eval("\$content =  \$this->$sAction;");
		if (count($aChild)>0) {
			$aOutput[] = array('type'=> 'folder','name'=>$content,'children'=> array());
		} else {
			$aOutput[] = array('type'=> 'item','name'=>$content);
		}
		foreach ($aChild as $oChildren) {
			$oChildren->getRecurciveMenuList($aOutput[$index]['children'], $sAction);
		}
	
	}
	

	/**
	 * Return html code for menu of type 1
	 * 
	 * @param string $sClass           Classe name
	 * @param string $sGroupFieldName  ShaBddField for lib
	 * @param string $sGroupFieldValue ShaBddField for value
	 * @param string $sAction          Js action
	 * @param string $name             Dom id
	 * @param string $puce             Puce code
	 * 
	 * @return HTML
	 */
	public static function getTreeMenuType1($sClass, $sGroupFieldName, $sGroupFieldValue, $sAction, $name, $puce){
	
		$menu = self::getMenuList($sClass, $sGroupFieldName, $sGroupFieldValue, $sAction);
		$output = array();
		ShaUtilsJs::constructMenuType1($output, $menu, $name, 0, 0, $puce, true);
	
		$html = "";
		foreach ($output as $htmlDiv) {
			$html.=$htmlDiv;
		}
		ShaUtilsArray::clearArray($output);
		return $html;
	
	}
	
	/**
	 * Return html code for menu of type 1
	 * 
	 * @param string $sClass           Classe name
	 * @param string $sGroupFieldName  ShaBddField for lib
	 * @param string $sGroupFieldValue ShaBddField for value
	 * @param string $sAction          Js action
	 * @param string $name             Dom id
	 * 
	 * @return HTML
	 */
	public static function getTreeMenuType2($sClass,  $sGroupFieldName, $sGroupFieldValue, $sAction, $name, $iStartSecondItem=true, $bIsStaticMenu=false){
	
		$menu = self::getMenuList($sClass,  $sGroupFieldName, $sGroupFieldValue, $sAction);
		return ShaUtilsJs::constructMenuType2($menu, $name, $iStartSecondItem, $bIsStaticMenu);
	
	}
	
	/**
	 * Return html code for menu of type 1
	 * 
	 * @param string $sClass           Classe name
	 * @param string $sGroupFieldName  ShaBddField for lib
	 * @param string $sGroupFieldValue ShaBddField for value
	 * @param string $sAction          Js action
	 * @param string $name             Dom id
	 * @param string $puce             Puce code
	 * @param int    $hDirection       Horizontal direction
	 * @param int    $vDirection       Vertical direction
	 * @param bool   $hUsePanel        Use panel for horizontal posisionning
	 * @param bool   $vUsePanel        Use panel for vertical posisionning
	 * @param int    $hDecalage        Horizontal decalage (px)
	 * @param int    $vDecalage        Vertical decalage (px)
	 * 
	 * @return HTML
	 */
	public static function drawMenuType1($sClass,  $sGroupFieldName, $sGroupFieldValue, $sAction, $name, $puce, $hDirection, $vDirection, $hUsePanel, $vUsePanel, $hDecalage=0, $vDecalage=0){
	
		$html = "
		<div id='".$name."_menu'>
			".self::getTreeMenuType1($sClass,  $sGroupFieldName, $sGroupFieldValue, $sAction, $name, $puce)."
			<script type='text/javascript'>
				cms_repositionMenuPanel('$name', '$hDirection', '$vDirection', true, $hUsePanel, $vUsePanel, $hDecalage, $vDecalage);
			</script>
		</div>
		";
		
		return $html;
	}
	
	/**
	 * Return html code for menu of type 1
	 *
	 * @param string $sClass           Classe name
	 * @param string $sGroupFieldName  ShaBddField for lib
	 * @param string $sGroupFieldValue ShaBddField for value
	 * @param string $sAction          Js action
	 * @param string $name             Dom id
	 *
	 * @return HTML
	 */
	public static function drawMenuType2($sClass, $sGroupFieldName, $sGroupFieldValue, $sAction, $name, $iStartSecondItem=true, $bIsStaticMenu=false) {
		$html = "
		<div id='".$name."_menu'>
			".self::getTreeMenuType2($sClass,  $sGroupFieldName, $sGroupFieldValue, $sAction, $name, $iStartSecondItem, $bIsStaticMenu)."
		</div>
		";
		return $html;
	}
	
	/**
	 * Construct editing formulaire for treetype  object
	 * 
	 * @param string &$html  Output html
	 * @param array  $config Config
	 * 
	 * @return void
	 */
	public function drawEditingRecurciveTreeFormulaire(&$html, $config){
	
		$child = $this->getTreeChild();
	
		//Btn d'édition
		$btn = "";
		

		if ($config['editBtnAllowed']) {
		    //TODO : repear it
			//$btnEdit=AbstractCmo::drawDoActionBtn($this->createEditShowFormulaireBtnDatas($config['class'], $config));
		    $btnEdit = "";
		}
		if ($config['deleteBtnAllowed']) {
		    //TODO : repear it
		    //$btnDelete=AbstractCmo::drawDoActionBtn($this->createDeleteBtnDatas($config['class'], $config), $config,  "window-content-".$config['div']);
		    $btnDelete = "";
		}
		if ($config['addBtnAllowed']) {
		    //TODO : repear it
		    //$btnAdd=AbstractCmo::drawDoActionBtn($this->_createAddTreeBtn($config['class'], $config), $config, "window-content-".$config['div']);
		    $btnAdd = "";
		}
	
		$type = (count($child)>0)?"folder":"file";
		$html .= "<li><div class='$type'><div class='cms_subdiv_float_left'>".$this->drawLineMode($this->fieldTypesList()).$btnEdit.$btnAdd.$btnDelete."</div></div><div class='clear'></div>";
	
		if (count($child)>0) {
			$html .= "<ul>";
			foreach ($child as $children) {
				$children->drawEditingRecurciveTreeFormulaire($html, $config);
			}
			$html .= "</ul>";
		}
	
		$html .= "</li>";
	}
	
	/**
	 * Return array with keys for child (parent, language ...)
	 * 
	 * @return array
	 */
	public function getKeyForChild(){
		if (count($this->primaryKey)==1) {
			return array('parent'=>$this->primaryKey[0][1]);
		} elseif (count($this->primaryKey)==2) {
			$aResult = array();
			foreach ($this->primaryKey as $key => $value) {
				if ($key=='language_id') {
					$aResult['language_id'] = $value;
				} else {
					$aResult['parent'] = $value;
				}
			}
			return $aResult;
			if ($language=="") {
				throw new Exception(ShaContext::t("TooManyPrimaryKeys"));
			}
		} else {
		    throw new Exception(ShaContext::t("TooManyPrimaryKeys"));
		}
	}
	
	/**
	 * Return CmsObject tree child
	 *
	 * @throws Exception
	 * @return Array CmsObject
	 */
	public function getTreeChild(){
	
		$primaryKey = "";
		$language = "";
		if (count($this->primaryKey)==1) {
			$primaryKey = $this->primaryKey[0][1];
		} elseif (count($this->primaryKey)==2) {
			foreach ($this->primaryKey as $key => $value) {
				if ($key=='language_id') {
					$language = " AND language_id = ".$value;
				} else {
					$primaryKey = "parent = ".$value;
				}
			}
			if ($language=="") {
			    throw new Exception(ShaContext::t("TooManyPrimaryKeys"));
			}
		} else {
		    throw new Exception(ShaContext::t("TooManyPrimaryKeys"));
		}
		return $this::loadByWhereClause($primaryKey.$language);
	}
	
	/**
	 * Return config for tree creation btn
	 *
	 * @return array
	 */
	private function _createAddTreeBtn(){
		return array(
			'class' => get_class($this),
		    //TODO : repeari it
		    'ShaOperation' => '',
			//'ShaOperation' => AbstractCmo::ShaOperation_SHOW_ADD_FORMULAIRE,
			'js' => 'cms_doAction',
			'addBtn' => true,
			'editBtn' => true,
			'deleteBtn' => true,
			'cssClass' => 'cms_cmo_list_btn_add',
			'title' => self::getTranslatorInstance()->t("addContentInDatabase"),
			'tree'=> $this->getKeyForChild()
		);
	
	}

}

?>
