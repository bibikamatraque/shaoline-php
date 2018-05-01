<?php



/**
 * JS lib
 *
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  mon-referendum.com copyright
 * @link     No link
 *
 */
class ShaUtilsJs
{


	/**
	 * Fill array width div for menu of type 'start menu'
	 *
	 * @param array  &$output    Output datas
	 * @param array  $datas      Input datas
	 * @param string $id         Dom id
	 * @param int    $index      Current index
	 * @param int    $decalIndex Index of decalage
	 * @param string $puce       Puce HTML style
	 * @param bool   $first      Is first
	 *
	 * @return void
	 */
	public static function constructMenuType1(&$output, $datas, $id, $index, $decalIndex, $puce="", $first=false) {
		$display = ($first)?"display:block;":"display:none;";
		$isMainFolder = ($first)?"class='".$id."_panel_first'":"class='".$id."_panel'";
		$html = "<div id='".$id."_".$index."' ".$isMainFolder." style='".$display."' >";
		$currentIndex = 0;
		foreach ($datas as $child) {
			if ($child['type']=='folder') {
				$html.= "<div class='".$id."_folder' id='".$id."_".$index."_".$currentIndex."_item' onmouseover=\"cms_displayAndClearMenuPanel('".$id."','".$index."_".$currentIndex."')\">
						".$puce."
								<span>".$child['name']."</span>
										</div>";
				self::constructMenuType1($output, $child['children'], $id, $index."_".$currentIndex, $decalIndex+1, $puce, false);
				$currentIndex++;
			} else {
				if ($index>0) {
					$html.= $child['name'];
				} else {
					$html.= "<div class='".$id."_folder' id='".$id."_".$index."_".$currentIndex."_item' onmouseover=\"cms_displayAndClearMenuPanel('".$id."','".$index."_".$currentIndex."')\">
							".$child['name']."
									</div>";
					$currentIndex++;
				}
			}
		}

		$html .= "</div>";		
		
		$output[] = $html;
	}

	/**
	 * Fill array width div for menu of type 'vertical clssic web menu'
	 * Maximum deep = 3
	 *
	 * @param array  $datas            Input datas
	 * @param string $id               Dom id
	 * @param int    $iStartSecondItem Start from the second item or not
	 *
	 * @return void
	 */
	public static function constructMenuType2($datas, $id, $iStartSecondItem=true, $bIsStaticMenu=false){
		if (!is_array($datas)) {
			return "";
		}
		
		if ($iStartSecondItem) {
			$datas = $datas[0]['children'];
		}
		
		$display = "display:block;";
		$hide = "display:none;";
		$html = "<div class='shaoline_menu_type_2_panel_level_0' id='shaoline_menu_type_2_".$id."' style='$display'>";
		$currentIndex = 0;
		foreach ($datas as $mainItem) {
			$sJsAction  = (!$bIsStaticMenu) ? "onmouseout=\"cms_displayAndClearMenuType2('$id','$currentIndex');\"" : "";
			$html .= "<div class='shaoline_menu_type_2_item_level_0' id='shaoline_menu_type_2_item_level_0_".$id."_$currentIndex' $sJsAction >".$mainItem['name']."</div>";
			$html .= "<div class='shaoline_menu_type_2_panel_level_1' id='shaoline_menu_type_2_panel_level_1_".$id."_".$currentIndex."' style='$hide' \">";
				if (isset($mainItem['children'])){
					foreach ($mainItem['children'] as $children) { 
						$html.="<div class='shaoline_menu_type_2_part_level_1'>";
						$html .= "<div class='shaoline_menu_type_2_item_level_1'>".$children['name']."</div>";
						if (isset($children['children'])) {
							foreach ($children['children'] as $item) {
								$html .= "<div class='shaoline_menu_type_2_item_level_2'>".$item['name']."</div>";
							}
						}
						$html.= "</div>";
					}
				}
				$html.="<div class='clear'></div>";
			$html .= "</div>";
			$html .= "
					<script type='text/javascript'>
						cms_hideMenuType2('$id','$currentIndex');
					</script>
					";
			
			$currentIndex++;
		}
		$html.= "</div>";
		return $html;
	}
}

?>