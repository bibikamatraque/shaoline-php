<?php

/**
 * 
 * @author Bastien Duhot
 *
 */
class ShaUtilsAriane {
	
	private $_nodes = array();
	
	/**
	 * Add new node
     *
	 * @param string $name
	 * @param string $linkUrl
	 * @param array $linkParam
     *
	 * @return ShaUtilsAriane
	 */
	public function addNode($name, $linkUrl = null, $linkParam = null) {
		$this->_nodes[] = array($name, $linkUrl, $linkParam);
		return $this;
	}
	
	/**
	 * Return HTML de for breadcrumb
	 */
	public function render($separator = ">"){
		$result = '<ul class="breadcrumb">';
		
		$max = count($this->_nodes) - 1;
		$index = 0;
		foreach ($this->_nodes as $node) {
			
			$url = ($node[1] != null) ? ShaPage::getUrlFromName($node[1], $node[2]) : "javascript:window.location.href=window.location.href";
		
			$result .= '
				<li itemtype="http://data-vocabulary.org/Breadcrumb" >
					<a href="'.$url.'" itemprop="url">
						<span itemprop="title">'.$node[0].'</span>
					</a>
					 '.(($index < $max) ? $separator : "").'	
				</li>	
			';
			$index++;
		}
		
		$result .= '</ul>';
				
		return $result; 
	}
	
}

?>