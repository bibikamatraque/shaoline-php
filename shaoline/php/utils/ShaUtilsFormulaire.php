<?php



/**
 * ShaFormulaire lib
 *
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  mon-referendum.com copyright
 * @link     No link
 * @deprecated
 */
// class ShaUtilsFormulaire
// {

// 	//Formulaire ShaOperation
// 	const ShaOperation_ADD 	= 0;
// 	const ShaOperation_UPDATE = 1;

// 	/**
// 	 * Construct a multiselector field type
// 	 *
// 	 * @param int    $id               HTML field ID
// 	 * @param string $titre            Title of field
// 	 * @param array  $mappingKeyValues Mapping Id/Key/Value
// 	 * @param string $selectedValues   Selected values
// 	 * @param string $cssClass         Additional CSS class
// 	 * @param int    $width            ShaBddField width
// 	 * @param string $type             Use checkbox or radiobox
// 	 * @param string $onchange         JS onchange action
// 	 *
// 	 * @return HTML
// 	 */
// 	public static function createMultipleSelectionList($id,$titre,$mappingKeyValues,$selectedValues, $cssClass,$width,$type,$onchange){
// 		$onchange = (isset($onchange) && $onchange!="")?"onchange='".ShaUtilsString::getASCII($onchange)."'":"";
// 		$html = "<div class='multipleSelection".$cssClass."' style='width:".$width."px'  >";
// 		$html .= "<input type='hidden' id='is-collapse-".$id."' value='0'>";
// 		$html .= "<h2 onclick=\"collapseMultipleList('".$id."')\">".$titre."</h2><ul id='ul-".$id."' style='width:".$width."px;display:none'>";
// 		$cpt=0;

// 		foreach ($mappingKeyValues as $mappingKeyValue) {
// 			$checked = (in_array($mappingKeyValue[0], $selectedValues))?"checked":"";
// 			$html.= "<li>";
// 			if ($type=="checkbox") {
// 				$html .= "<input name='".$id."' value='".$mappingKeyValue[0]."' type='checkbox' ".$onchange." $checked >";
// 			} else if ($type=="radiobox") {
// 				$checked = ($cpt==0)? "checked=checked":"";
// 				$html .= "<input name='".$id."' value='".$mappingKeyValue[0]."' type='radio' ".$onchange." $checked >";
// 			}
// 			$html.= "<span>".$mappingKeyValue[1]."</span>
// 					</li>";
// 			$cpt++;
// 		}

// 		$html .= "</ul></div>";
// 		return $html;
// 	}


// 	/**
// 	 * Construct formulaire field
// 	 *  
// 	 * @param array  &$item       Items datas
// 	 * @param string $identifiant Identifiant base
// 	 * @param int    $index       Identifiant index
// 	 * @param array  $config      All config (needed by submit btn)
// 	 * @param string $dao         ShaDao object
// 	 * 
// 	 * @throws Exception
// 	 * @return HTML
// 	 */
// 	public static function constructField(&$item, $identifiant, $index, $config=null, $dao=null){

// 		$canEdit = isset($item['canEdit']) ? $item['canEdit'] : false;
// 		$idValue = (isset($item['id'])) ? $item['id'] : $identifiant."_".$index;
// 		$id = (self::_mustSubmit($item)) ? " name='".$idValue."' " : "";
// 		$onchange = (isset($item['onchange']))?"onchange='".ShaUtilsString::getASCII($item['onchange'])."'":"";
// 		$domWidth = (isset($item['width']))?"width:".$item['width']."px;":"";

// 		if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_TEXT) {

//             $placeholder = "";
//             if (isset($item['placeholder'])) {
//                 $placeholder = "placeholder = '".$item['placeholder']."'";
//             }

// 			//TEXT BOX
// 			if ($canEdit) {
// 				return "<input type='text' id='$idValue' class='cms_input' $placeholder style='".$domWidth."' ".$id." ".$onchange." alt='".ShaUtilsString::quoteProtection($item['values'])."' value='".ShaUtilsString::quoteProtection($item['values'])."'>";
// 			} else {
// 				return $item['values'];
// 			}
			
// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_PASSWORD) {

// 			//PASSWORD BOX
// 			if ($canEdit) {
// 				return "<input type='password' $idValue class='cms_input' style='".$domWidth."' ".$id." ".$onchange." value='".ShaUtilsString::quoteProtection($item['values'])."'>";
// 			} else {
// 				return $item['values'];
// 			}
// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_TEXTAREA) {

// 			//TEXTAREA
// 			if ($canEdit) {
// 				return "<textarea class='cms_input' $idValue style='".$domWidth."' ".$id." ".$onchange." >".$item['values']."</textarea>";
// 			} else {
// 				return $item['values'];
// 			}
// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_RADIOBOX) {

// 			//RADIOBOX
// 			if (!is_array($item['datas'])) {
// 				throw new Exception(" items['datas'] must be an array for radiobox field type !");
// 			}
// 			if (!isset($item['selectedValues'])) {
// 				$item['selectedValues'] = $item['datas'];
// 			}
// 			$html = "";
// 			foreach ($item['datas'] as $key => $value) {
// 				if ($key == $item['selectedValues']) {
// 					$html .= "<input type='radio' checked  ".$id." ".$onchange." value='".$key."' class='cms_espacement_right_5'>".$value;
// 				} else {
// 					$html .= "<input type='radio' ".$id." ".$onchange." value='".$key."' class='cms_espacement_right_5'>".$value;
// 				}
// 			}
// 			return $html;

// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_CHECKBOX) {

// 			//CHECKBOX
// 			if (!is_array($item['datas'])) {
// 				throw new Exception(" items['datas'] must be an array for checkbox field type !");
// 			}
// 			if (!isset($item['selectedValues'])) {
// 				$item['selectedValues'] = $item['values'];
// 			}
// 			$html = "";
// 			foreach ($item['datas'] as $key => $value) {
// 				if ($key == $item['selectedValues']) {
// 					$html .= "<input type='checkbox' ".$id." ".$onchange." value='".$key."' class='cms_espacement_right_5'>".$value;
// 				} else {
// 					$html .= "<input type='checkbox' checked ".$id." ".$onchange." value='".$key."' class='cms_espacement_right_5'>".$value;
// 				}
// 			}
// 			return $html;

// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_COMBOBOX) {

// 			//COMBOBOX
// 			if (!is_array($item['datas'])) {
// 				throw new Exception(" items['datas'] must be an array for combobox field type !");
// 			}
// 			if (!isset($item['selectedValues'])) {
// 				$item['selectedValues'] = $item['datas'];
// 			}
// 			$html = "<select style='".$domWidth."' ".$id." ".$onchange.">";
// 			$firstOption = "";
// 			$options = "";
// 			foreach ($item['datas'] as $key => $value) {
// 				if (isset($item['selectedValues']) && $key == $item['selectedValues']) {
// 					$options .= "<option selected value='".$key."' >".$value."</option>";
// 				} else {
// 					$options .= "<option value='".$key."' >".$value."</option>";
// 				}
// 			}
// 			$html .=  $firstOption.$options;
// 			$html .= "</select>";
// 			return $html;

// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_CHECKBOX_LIST) {

// 			//CHECKBOXLIST
// 			return self::createMultipleSelectionList($idValue, $item['label'], $item['values'], $item['selectedValues'], "", $domWidth, "checkbox", (isset($item['onchange']))?$item['onchange']:"");

// 		} else if ($item['renderer']==AbstractCmo::DISPLAY_TYPE_RADIOBOX_LIST) {

// 			//RadioBoxList
// 			return self::createMultipleSelectionList($idValue, $item['label'], $item['values'], $item['selectedValues'], "", $domWidth, "radiobox", (isset($item['onchange']))?$item['onchange']:"");

// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_SUBMIT) {
// 			//submit
// 			if (isset($config['form'])) { 
// 				return "<input type='submit' class='cms_button' />";
// 			} else {
// 				$gcId = Cms::serializeListingParam($config);
// 				return '<span onclick="cms_submitFormulaire(\''.$config['id'].'\',\''.$gcId.'\')"  gcid="'.$gcId.'" class="cms_button cms_center">'.$item['label'].'</span>';
// 			}
		
// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_PICTURE) {
			
// 			if ($item['values']=="" || $item['values']=="[no translation]") {
// 				$item['values'] = "shaoline/resources/img/cms_no_picture.png";
// 			}
// 			$control = AbstractCmo::constructAdminPictoDomFieldConfig($dao, $item['key'], AbstractCmo::DISPLAY_TYPE_PICTURE, $item['canEdit'], $item['width']);
// 			return "<div " . $control . " ><img alt='" . $item['key'] . "' src='" . $item['values'] . "' style='" . $domWidth . "' /></div>";
			
			
// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_LINKEDPICTURE) {
			
// 			if ($item['values']=="" || $item['values']=="[no translation]") {
// 				$item['values'] = "[set your value here]|shaoline/resources/img/cms_no_picture.png";
// 			}
// 			$splitedValues = explode("|", $item['values']);
// 			if (count($splitedValues) != 2) {
// 				throw new Exception("Bad value for field " . $item['key'] . " !");
// 			}
// 			if ($splitedValues[0]=="") {
// 				$splitedValues[0] = "shaoline/resources/img/cms_no_picture.png";
// 			}
// 			$control = AbstractCmo::constructAdminPictoDomFieldConfig($dao, $item['key'], AbstractCmo::DISPLAY_TYPE_PICTURE, $item['canEdit'], $item['width']);
// 			return "<div " . $control . "><a alt='" . $item['key'] . "' href='" . $splitedValues[0] . "'><img alt='" . $item['key'] . "' src='" . $splitedValues[1] . "' " . $pictureWidth . " /></a></div>";
			
// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_LINK) {
			
// 			//TODO
			
// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_SWITCHVALUE) { 
// 			$value = $item['values'];
// 			$value = (isset($item['rendererMapping'][$value])) ? $item['rendererMapping'][$value] : "";
// 			if ($value=="" && isset($item["cms_render_mapping_other"])) {
// 				$value = $item["cms_render_mapping_other"];
// 			}
// 			return $value;
			
// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_SWITCHPICTURE) {
			
// 			$value = $item['values'];
// 			$pictureWidth = (isset($item['width'])) ? " style='width:".$item['width']."px' " : "";
// 			$value = (isset($item['rendererMapping'][$value])) ? $item['rendererMapping'][$value] : "";
// 			if ($value=="" && isset($item["cms_render_mapping_other"])) {
// 				$value = $item["cms_render_mapping_other"];
// 			}
// 			return "<img alt='' ".$pictureWidth." src='".$value."' />";
		
// 		} else if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_CAPCHA) {
// 			$item['capcha']["capchaValue"] = ShaUtilsCapcha::getRandomCode($item['capcha']['capchaLength']);
// 			$sCapcha = ShaUtilsCapcha::getCapchaPicture(
// 				$item['capcha']["capchaValue"],
// 				$item['capcha']["capchaWidth"],
// 				$item['capcha']["capchaHeight"],
// 				$item['capcha']["capchaNoiseDensity"],
// 				$item['capcha']["capchaColorBackR"],
// 				$item['capcha']["capchaColorBackG"],
// 				$item['capcha']["capchaColorBackB"],
// 				$item['capcha']["capchaColorFontR"],
// 				$item['capcha']["capchaColorFontG"],
// 				$item['capcha']["capchaColorFontB"],
// 				$item['capcha']["capchaColorNoiseR"],
// 				$item['capcha']["capchaColorNoiseG"],
// 				$item['capcha']["capchaColorNoiseB"]
// 			);
// 			return "
// 				$sCapcha
// 				<br/>
// 				<input type='text' class='cms_input' style='".$domWidth."' ".$id." ".$onchange." value=''>
// 				";
				
// 		} 

// 	}

	
	
// 	/**
// 	 * Draw formulaire
// 	 *
// 	 * @param array  $items       Items configuration
// 	 * @param string $identifiant Identifiant
// 	 *
// 	 * @return HTML
// 	 */
// 	public static function drawFormulaire($items, $identifiant=""){
// 		$html = "<div>
// 				<table class='cms_formulaire'>
// 				[DATAS]
// 				</table>
// 				</div>
// 				";

// 		$data = "";
// 		$index = 0;
// 		foreach ($items as $item) {

// 			$data.= "<tr>";
// 			if (isset($item['renderer']) && $item['renderer']!=AbstractCmo::DISPLAY_TYPE_SUBMIT) {
// 				$data.= "<td style='padding-right: 10px;'>".$item['label']."</td>";
// 			}
// 			//Is forced value
// 			if (isset($item['forcedValue'])) {
// 				$data.= "<td>".$item['forcedValue']."</td>";
// 			} else {
// 				$data.= "<td>".self::constructField($item, $identifiant, $index)."</td>";
// 			}
// 			$data.= "</tr>";
// 			$index++;
// 		}

// 		return ShaUtilsString::replace($html, "[DATAS]", $data);
// 	}

	
				
				
// 	/**
// 	 * Draw formulaire
// 	 * 
// 	 * @param array  &$config     ShaFormulaire config
// 	 * $config = array(
// 	 *  	id					=> ShaForm ID (en element id prefix),
// 	 * 		width				=> ShaForm width,
// 	 *		class				=> ShaDao class concerned by formulaire
// 	 *		additional			=> Additional datas to set to the object created or updated ( array(key=>value) )
// 	 *		beforeSave			=> Checking action before save ShaDao object
// 	 *		afterSave			=> Specific action to do after save ShaDao object
// 	 *		autoClearFromulaire	=> Delete formulaire in garbadge when succed
// 	 *		autoRedirect		=> Do redirection if success
// 	 *		successMsg			=> Success msg 
// 	 *		fields=> array(
// 	 *				label               => ShaBddField label,
// 	 *				trAdd               => Additional on first <tr> balise
// 	 *				td1Add              => Additional on first <td> balise
// 	 * 				td2Add 	            => Additional on second <td> balise
// 	 *				maxLength           => Max datas length,
// 	 *				minLength           => Min datas length,
// 	 *				maxValue            => Max value,
// 	 *				minValue            => Min value,
// 	 *				inputType           => Input type (TEXT, TEXTAREA, UNSIGNEDINT, INT, UNSIGNEDDECIMAL, DECIMAL, DATETIME, DATE),
// 	 *				key                 => ShaDao object field name,
// 	 *				renderer            => ShaBddField type (text, password, textarea, radiobox, combobox, checkboxList, radioboxList)
// 	 *				values              => Value for list type field  ( array(key=>value) )
// 	 *				onchange            => 'onchange' js function
// 	 *				selectedValues      => Values selected
// 	 *				valueKeyFieldName   => ShaBddField used for key in relation
// 	 *				valueValueFieldName => ShaBddField used for value in relation
// 	 *				confirm             => True to create confirmation field
// 	 *				width				=> ShaBddField width,
// 	 *				forcedValue			=> Valeur forcée
// 	 *				saveOnlyIfNotEmpty	=> Save only if field is felt
// 	 *				cryptage			=> md5, sha1
// 	 *				necessaryValue		=> Value necessary
// 	 *			)
// 	 *	);
// 	 * @param string $identifiant Dom identifiant
// 	 * @param string $cmsObject   Instance og CmsObject
// 	 * 
// 	 * @return HTML
// 	 */
// 	public static function drawExtendedFormulaire(&$config, $identifiant="", $cmsObject=null){

// 		$html = "<div>";

// 		if (isset($config['form'])) {
// 			$html = "
// 					<div>
// 						<div id='$identifiant'>
// 							<form method='".$config['form']['method']."' action='".$config['form']['action']."'>
// 								<table class='cms_formulaire'>
// 									[DATAS]
// 								</table>
// 							</form>
// 						</div>
// 					</div>
// 							";
// 		} else {
// 			$html = "<div>
// 						<div id='$identifiant'>
// 							<table class='cms_formulaire'>
// 							[DATAS]
// 							</table>
// 						</div>
// 					</div>
// 					";
// 		}

// 		$data="";
// 		$index = 0;
// 		$iCpt = 0;
		
// 		foreach ($config['fields'] as &$item) {

// 			//Prefix and suffix
// 			$prefix = (isset($item['prefix'])) ? $item['prefix'] : "";
// 			$suffix = (isset($item['suffix'])) ? $item['suffix'] : "";
			
// 			$sAddToTr = (isset($item['trAdd'])) ? " ".$item['trAdd']." " : "";
// 			$data.= "<tr class='".$config['id']."_".$index."_line' $sAddToTr>";
			
// 			if (!isset($item['renderer'])) {
// 				$item['renderer'] = "text";
// 			}

// 			$sAddToFirstTd = (isset($item['td1Add'])) ? " ".$item['td1Add']." " : "";
// 			$sAddToSecondTd = (isset($item['td2Add'])) ? " ".$item['td2Add']." " : "";

// 			//Draw label if not submit, not forced, not reference and label defined
// 			if (self::_mustDisplayLibelle($item)) {
// 				$data.= "<td class='".$config['id']."_".$iCpt."_key' $sAddToFirstTd>".$item['label']."</td>";
// 			}
			
// 			//Draw value
// 			$data.= "<td class='".$config['id']."_".$iCpt."_value' $sAddToSecondTd>".$prefix;
			
// 				if (isset($item['forcedValue'])) {
// 					//Forced value
// 					$data.= $item['forcedValue'];
// 				} else if (isset($item['reference'])) {
// 					//Reference
// 					$data.= $cmsObject->drawReference($item);
// 				} else {			
// 					//All other

// 					//Set default render = text
// 					if (!isset($item['renderer'])) {
// 						$item['renderer'] = "text";
// 					}
					
// 					//Dynamic renderer
// 					$dynamicRenderer = explode("[FIELD]", $item['renderer']);
// 					if (count($dynamicRenderer)==2) {
// 						$item['renderer'] = $cmsObject->getValue($dynamicRenderer[1]);
// 					}
					
// 					//Draw field
// 					$data.= self::constructField($item, $identifiant, $index, $config, $cmsObject, true);
					
// 					//Increment id conwt down if editable field
// 					if (self::_mustSubmit($item)) {
// 						$index++;
// 					} 
// 				}
// 			$data.= $suffix."</td></tr>";
// 			$iCpt++;
// 		}

// 		return ShaUtilsString::replace($html, "[DATAS]", $data);
// 	}

// 	/**
// 	 * Return formualire for ShaDao object
// 	 * 
// 	 * @param array     $config      ShaForm configuration
// 	 * @param ShaDaoObject $cmsObject   ShaDao object used to fill form
// 	 * @param string    $identifiant Dom identifiant
// 	 * 
// 	 * @return HTML
// 	 */
// 	public static function drawDaoFormulaire($config, $cmsObject, $identifiant) {
// 		$directRelations = $cmsObject->relationship();
	
// 		$config['class'] = get_class($cmsObject);
// 		$config['primaryKeys'] = $cmsObject->getPrimaryArray();
	
// 		//Scann all key
// 		foreach ($config['fields'] as &$item) {

// 			if (isset($item['key'])) {
	
// 				//search in field and in relation
// 				if ($cmsObject->isFieldExist($item['key'])) {
// 					$item['values'] = (isset($item['values'])) ?  $item['values'] : $cmsObject->getValue($item['key']);
// 					$item['inputType'] = (isset($item['inputType'])) ? $item['inputType'] : ShaUtilsString::PATTERN_ALL/*$cmsObject->fields[$item['key']]->type*/;
// 				}
	
// 				//search in relation
// 				if (array_key_exists($item['key'], $directRelations)) {
// 					$item['selectedValues'] = array();
// 					$groupDao = null;
// 					$values = $cmsObject->getRelation($item['key']);
// 					foreach ($values as $value) {
// 						$groupDao = get_class($value);
// 						$item['selectedValues'][] = implode("#", $value->getPrimaryArray());
// 					}
// 					$item['values'] = AbstractCmo::getPrimaryValuesForFormulaire($groupDao, $item['valueKeyFieldName']);
// 				}
// 			}
	
// 		}
	
// 		return self::drawExtendedFormulaire($config, $identifiant, $cmsObject);
	
// 	}
	
// 	/**
// 	 * Return true if label must be display
// 	 * 
// 	 * @param array $item ShaBddField configuration
// 	 * 
// 	 * @return boolean
// 	 */
// 	private static function _mustDisplayLibelle($item){
// 		return (
// 			isset($item['label']) && 
// 			$item['renderer']!=AbstractCmo::DISPLAY_TYPE_SUBMIT && 
// 			!isset($item['reference']) && 
// 			!isset($item['forcedValue']) && 
// 			!isset($item['tree'])	
// 		);
// 	}
	
// 	/**
// 	 * Return true if field must be submit
// 	 *
// 	 * @param array $item ShaBddField configuration
// 	 *
// 	 * @return boolean
// 	 */
// 	private static function _mustSubmit($item){
// 		return (
// 			!isset($item['reference']) && 
// 			!isset($item['forcedValue']) && 
// 			!isset($item['tree']) &&
// 			isset($item['renderer']) && 
// 			$item['renderer']!=AbstractCmo::DISPLAY_TYPE_SUBMIT &&
// 			$item['renderer']!=AbstractCmo::DISPLAY_TYPE_PICTURE &&
// 			(!isset($item['canEdit']) || $item['canEdit'])
// 		);
// 	}
	
	

// 	/**
// 	 * Threat formulaire submit
// 	 *
// 	 * @param array $config ShaFormulaire config
// 	 * @param array $values ShaFormulaire POST values
// 	 *
// 	 * @return string
// 	 */
// 	public static function submitFormulaire($config, $values){


// 		$configuration = Cms::unserializeListingParam($config);

// 		$values = json_decode($values);
// 		$cmsObject=null;
// 		if (isset($configuration['class']) && class_exists($configuration['class'])) {
			
// 			$cmsObject = new $configuration['class']();
// 			//eval("\$cmsObject = new ".$configuration['class']."();");
// 			$directRelations = $cmsObject->relationship();
	
// 			//Load ShaDao if update mode
// 			if (isset($configuration['primaryKeys'])) {
// 				if (!$cmsObject->load($configuration['primaryKeys'])) {
// 					return self::_errorFormatter(Dao::t("unknowObjectReference"));
// 				}
// 			}
	
// 			//Set additional informations if necessary
// 			if (isset($configuration['additional'])) {
// 				foreach ($configuration['additional'] as $key => $value) {
// 					if (!$cmsObject->isFieldExist($key)) {
// 						return self::_errorFormatter(Dao::t("missingParameters").$key."' for class ".$configuration['class']);
// 					}
// 					$cmsObject->setValue($key, $value);
// 				}
// 			}

// 		}
		
// 		//Read all form input
// 		$index = 0;
// 		$vLastValue = "";
// 		$vLastField = "";
// 		foreach ($configuration['fields'] as &$item) {
			
// 			if (self::_mustSubmit($item)) {

// 				//Check if data exist
// 				if (!isset($values[$index])) {
// 					return self::_errorFormatter(Dao::t("missingParameters")." : '".$index."'");
// 				}
				
// 				//If it is ShaDao field, check format
// 				if (isset($item['key']) && (!isset($cmsObject) || (isset($cmsObject) && $cmsObject->isFieldExist($item['key'])))) {
// 					$value = $values[$index][0];
// 					$item['client_value'] = $value;
					
// 					//Force security
// 					if (!isset($item['maxLength'])) {
// 						$item['maxLength'] = 1000;
// 					}
					
// 					//Check max value
// 					if (isset($item['maxLength']) && strlen($value)>$item['maxLength']) {
// 						return self::_errorFormatter(Dao::t("maximumLength")." : ".$item['label']);
// 					}
// 					//Check min length
// 					if (isset($item['minLength']) && strlen($value)<$item['minLength']) {
// 						return self::_errorFormatter(Dao::t("minimumLength")." : ".$item['label']);
// 					}
// 					//Check max value
// 					if (isset($item['maxValue']) && ($value>$item['maxValue'])) {
// 						return self::_errorFormatter(Dao::t("maximumValue")." : ".$item['label']);
// 					}
// 					//Check min value
// 					if (isset($item['minValue']) && ($value<$item['minValue'])) {
// 						return self::_errorFormatter(Dao::t("minimumValue")." : ".$item['label']);
// 					}
// 					//Check input pattern
// 					if (isset($item['inputType']) && !ShaUtilsString::isRegex($value, $item['inputType'])) {
// 						return self::_errorFormatter(Dao::t("badFormat")." : '".$item['label']."'");
// 					}
// 					//Bis mode
// 					if (isset($item['bisMode']) && $item['bisMode'] && $value!=$vLastValue) {
// 						return self::_errorFormatter(Dao::tt("Fields '%f1%' and '%f2%' must have the same value !", array("f1"=>$vLastField, "f2"=>$item['label'])));
// 					}
// 					//Check if value is necvessary
// 					if (isset($item['necessaryValue']) && $item['necessaryValue'] && "" == $value) {
// 						return self::_errorFormatter(Dao::t("Field '%f1%' is necessary"), array("f1" => $item['label']));
// 					}
// 					//Check if value allowed for list component
// 					if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_COMBOBOX
// 						|| $item['renderer'] == AbstractCmo::DISPLAY_TYPE_CHECKBOX
// 						|| $item['renderer'] == AbstractCmo::DISPLAY_TYPE_CHECKBOX_LIST
// 						|| $item['renderer'] == AbstractCmo::DISPLAY_TYPE_RADIOBOX_LIST
// 						|| $item['renderer'] == AbstractCmo::DISPLAY_TYPE_RADIOBOX
// 					) {
					
// 						if (!isset($item['datas']) || (!is_array($item['datas']))) {
// 							return self::_errorFormatter(Dao::t(" 'datas' must be an array '"));
// 						}
						
// 						if (!array_key_exists($value, $item['datas'])) {
// 							return self::_errorFormatter(Dao::t("Bad value for field '%f1%'"), array("f1" => $item['label']));
// 						}
						
// 					}

// 					//Set value to ShaDao
// 					if (isset($cmsObject)) {
						
// 						//Cryptage
// 						if (isset($item["cryptage"])) {
// 							eval("\$sValueToSave = ".$item["cryptage"]."('\$value');");
// 						} else {
// 							$sValueToSave = $value;
// 						}
						
// 						//Only if felt
// 						$bSaveDatas = true;
// 						if (isset($item['saveOnlyIfNotEmpty'])) {
							
// 							if ($item['saveOnlyIfNotEmpty']) {
// 								if ($value == "") {
// 									$bSaveDatas = false;
// 								}
// 							} 
							
// 						}
						
// 						if ($bSaveDatas) {
// 							//echo "Save ".$item['key']."=".$sValueToSave."<br/>";
// 							$cmsObject->setValue($item['key'], $sValueToSave);
// 						}
// 					}
// 					$vLastValue = $value;
// 					$vLastField = $item['label'];
// 					$index++;
// 				} else {
//                     $value = $values[$index][0];
//                     $item['client_value'] = $value;
//                 }
				
// 				//Specific capcha
// 				if ($item['renderer'] == AbstractCmo::DISPLAY_TYPE_CAPCHA) {
// 					$value = $values[$index][0];
// 					if ( (!isset($item['capcha'])) || (!isset($item['capcha']['capchaValue'])) || ($item['capcha']['capchaValue']!=$value)) {
// 						return self::_errorFormatter(Dao::t("Bad security code"));
// 					}
// 					$vLastValue = $value;
// 					$vLastField = $item['label'];
// 					$index++;
// 				}
				
// 				//Specify dao relation
// 				if (isset($cmsObject)) {
// 					//If relation datazs
// 					/*if (isset($item['key']) && array_key_exists($item['key'], $directRelations)) {
	
// 						$currentDirectRelations = $directRelations[$item['key']];
	
// 						echo "Adding relation ".$item['key'];
	
// 						//Delete all preview informations
// 						$deleteRequest = "";
// 						foreach ($currentDirectRelations['keys'] as $directRelationKey) {
// 							$deleteRequest.=$directRelationKey."='".$cmsObject->getValue($directRelationKey)."' AND ";
// 						}
// 						$deleteRequest = substr($deleteRequest, 0, strlen($deleteRequest)-4);
// 						echo "DELETE SQL : '".$deleteRequest."'";
// 						$relationsToDelete = ::loadByWhereClause($currentDirectRelations['dao'], $deleteRequest);
// 						foreach ($relationsToDelete as $relationToDelete) {
// 							echo "delete...<br/>";
// 							$relationToDelete->delete();
// 						}
	
// 						//For each values checked
// 						foreach ($values[$index] as $key=>$newRelationEntry) {
	
// 							echo "newRelationEntry = $newRelationEntry";
	
// 							//Instanciate new linked ShaDao object
// 							$cmsRelationObject=null;
// 							$cmsRelationObject = new $currentDirectRelations['dao']();
// 							//eval("\$cmsRelationObject = new ".$currentDirectRelations['dao']."();");
	
// 							//Set first relation keys
// 							$objectRelationKeys = $cmsObject->fillKeyArrayWithValues($currentDirectRelations['keys']);
// 							foreach ($objectRelationKeys as $key => $objectRelationKey) {
// 								$cmsRelationObject->setValue($key, $objectRelationKey);
// 							}
	
// 							//If it is double relation set seconde reference keys
// 							$inputValue = explode("_", $newRelationEntry);
// 							if (isset($currentDirectRelations['reference'])) {
// 								$cmsRelationObjectReference = $cmsRelationObject->relationship();
// 								$secondReference = $cmsRelationObjectReference[$currentDirectRelations['reference']];
// 								$secondReferenceKeys = $secondReference['keys'];
// 								if (count($secondReferenceKeys)!=count($inputValue)) {
// 									die("Binary relation bad primary key number !");
// 								}
// 								$i = 0;
// 								foreach ($secondReferenceKeys as $secondReferenceKey) {
// 									$cmsRelationObject->setValue($secondReferenceKey, $inputValue[$i]);
// 									$i++;
// 								}
// 							} else {
// 								$objectPrimaryKeyNames=$cmsRelationObject->getPrimaryKeyNames();
// 								if (count($objectPrimaryKey)!=count($inputValue)) {
// 									die("Relation bad primary key number !");
// 								}
// 								$i=0;
// 								foreach ($objectPrimaryKeyNames as $objectPrimaryKeyName) {
// 									echo "\$cmsRelationObject->setValue($objectPrimaryKeyName,".$inputValue[$i].");";
// 									$cmsRelationObject->setValue($objectPrimaryKeyName, $inputValue[$i]);
// 									$i++;
// 								}
	
// 							}
// 							$cmsRelationObject->save();
// 						}
// 						$index++;
// 					}*/
// 				}
				
// 			}
// 		}
// 		try{
// 			$sShaResponse = "";
// 			if (isset($configuration['beforeSave'])) {
// 				eval("\$sShaResponse = ".$configuration['beforeSave']."(\$configuration, \$cmsObject);");
// 			}
// 			if ($sShaResponse=="") {

// 				if (isset($cmsObject)) {
// 					$cmsObject->save();
// 				}
// 				if (isset($configuration['afterSave'])) {
// 					eval($configuration['afterSave']."(\$configuration, \$cmsObject);");
// 				} 
// 				if (isset($configuration['autoClearFromulaire']) && $configuration['autoClearFromulaire']) {
// 					ShaGarbageCollector::deleteEntry($config);
// 				}
// 				if (isset($configuration['autoRedirect'])) {
// 					ShaPage::redirect($configuration['autoRedirect']);
// 					return;
// 				} else {
// 					if (isset($configuration['successMsg'])) {
// 						return self::_goodFormatter(Dao::getTranslatorInstance()->t($configuration['successMsg']));
// 					} else {
// 						return self::_goodFormatter(Dao::getTranslatorInstance()->t("yourFormulaireWasSuccessfullySaved"));
// 					}
// 				}
// 			} else {
// 				return self::_errorFormatter($sShaResponse);
// 			}
			
// 		}catch(Exception $e){
// 			return $e->getMessage();
// 		}
// 	}

// 	/**
// 	 * ShaFormat msg adding css class for 'error'
// 	 * 
// 	 * @param string $msg Message to format
// 	 * 
// 	 * @return HTML
// 	 */
// 	private static function _errorFormatter($msg){
// 		return Cms::popinConstructor($msg, "cms-formulaire-".Dao::getNextContentId(), ShaContext::t("Error"), "", "", "red");
// 	}
	
// 	/**
// 	 * ShaFormat msg adding css class for 'all is good'
// 	 *
// 	 * @param string $msg Message to format
// 	 *
// 	 * @return HTML
// 	 */
// 	private static function _goodFormatter($msg){
// 		return Cms::popinConstructor($msg, "cms-formulaire-".Dao::getNextContentId(), ShaContext::t("Save"));
// 	}
	
// }

?>