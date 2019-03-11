<?php

/**
 * Array lib
 *
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  Shaoline-php copyright
 * @link     No link
 *
 */
class ShaUtilsArray
{

	/**
	 * Clear all the items of an array using 'unset' function
	 *
	 * @param array &$items array
	 *
	 * @return void
	 */
	public static function clearArray(&$items) {
		if (!is_array($items)) {
			unset($items);
            return;
		}
		foreach ($items as $item) {
			unset($item);
		}
		unset($items);
		$items = array();
	}

	/**
	 * Récupère une variable en POST puis en GET si aucun valeur n'est trouvée en POST
	 *
	 * @param string $name Variable name
	 *
	 * @return string
	 */
	public static function getPostGet($name) {
		if (!isset($_POST[$name])) {
			if (!isset($_GET[$name])) {
				return null;
			} else {
				$commande = $_GET[$name];
			}
		} else {
			$commande = $_POST[$name];
		}
		return $commande;
	}

	/**
	 * Chech the intergity of POST/GET parameter using $pattern
	 *
	 * @param array $pattern Pattern
	 *
	 * array[
	 *          array['type'=>Type of parametre, 'ascii' => Is ASCII decoding necessary,'max'=>max length, 'min'=> min length],
	 *          ...
	 *      ]
	 *
	 * @return array
	 */
	public static function analysePostParameters($pattern) {
		$result = array();
		foreach ($pattern as $key => $value) {
			$val = ShaUtilsArray::getPostGet($key);
			
			$optional = (isset($value['optional'])) ? $value['optional'] : false;
			
			if ($val === null && $optional) {
				$result[$key] = null;
			}
			
			if ($val === null && !$optional) {
				throw new Exception(ShaContext::tt("Missing parameter : '%0%'", $key));
			}
			
			if ($val === null && isset($value['ifEmpty'])) {
				$val = $value['ifEmpty'];
			}
			
			if (!isset($value['type'])) {
				throw new Exception(ShaContext::tt("Missing parameter type : '%0%'", $key));
			}
			if (isset($value['max']) && strlen($val) > $value['max']) {
				throw new Exception(ShaContext::tt("Too long parameter : '%0%'", $key));
			}
			if (isset($value['min']) && strlen($val) < $value['min']) {
				throw new Exception(ShaContext::tt("Too short parameter : '%0%'", $key));
			}
			if ($value['type'] != -1 && !ShaUtilsString::isRegex($val, $value['type'])) {
				ShaUtilsLog::security("Security : Bad '".$key."' value in analysePostParameters ! Value : '".$val."'");
				throw new Exception(ShaContext::tt("Bad parameter format : '%0%'", $key));
			}
			$result[$key] = $val;
		}
		return $result;
	}

	/**
	 * Convert a array to SQL concat string
	 *
	 * @param array  $items     Items
	 * @param string $separator Separator
	 *
	 * @return string
	 */
	public static function arrayToSqlConcat($items,$separator = ",'_',"){
		if (count($items)>0) {
			$sql = "CONCAT(";
			foreach ($items as $item) {
				$sql.=$item.$separator;
			}
			$sql = substr($sql, 0,  strlen($sql)-strlen($separator)).")";
			return $sql;
		} else {
			return "";
		}

	}

	/**
	 * Convert a mapping key/value to SQL condtiion
	 *
	 * @param array $mapping Mapping key/values
	 *
	 * @return string
	 */
	public static function arrayToSqlCondition($mapping, $useLike = false){
		$sql = " ";
		foreach ($mapping as $key => $value) {
            if ($useLike){
                $sql.= $key." LIKE '%".$value."%' AND ";
            } else {
                $sql.= $key."='".$value."' AND ";
            }
		}
		$sql = substr($sql, 0,  strlen($sql)-4);
		return $sql;
	}


	/**
	 * Convert a array width key names to sql relation (a.mykey=b.mykey AND ....)
	 *
	 * @param array  $items  Items
	 * @param string $aliasA SQL alias A
	 * @param string $aliasB SQL alias B
	 *
	 * @return string
	 */
	public static function arrayKeysToSqlLink($items,$aliasA = "a", $aliasB = "b"){
		$sql = " ";
		foreach ($items as $item) {
			$sql.= $aliasA.".".$item."=".$aliasB.".".$item." AND ";
		}
		$sql = substr($sql, 0,  strlen($sql)-4);
		return $sql;
	}

	/**
	 * Insert new entry in array at defined position
	 *
	 * @param array &$objectArray Array
	 * @param string $value Value
	 * @param int $index Index to put value
	 *
	 * @return void
	 */
	public static function insertAt(&$objectArray, $value, $index){
		if ($index>=count($objectArray)) {
			$objectArray[] = $value;
			return;
		}
		$lastValue = $objectArray[count($objectArray)-1];
		$size = count($objectArray);
		for ($i=$size-1; $i > $index; $i--) {
			$objectArray[$i] = $objectArray[$i-1];
		}
		$objectArray[$index] = $value;
		$objectArray[] = $lastValue;
	}

	/**
	 * Sort object array
	 *
	 * @param array &$objectArray   Array to sort
	 * @param array $objectAttribut Sort rules
	 *
	 * @return void
	 */
	public static function sortObjectArray(&$objectArray, $objectAttribut) {

        $valueToSort = null;
        $valueToItem = null;
		$tmpArray = array();
		foreach ($objectArray as $item) {
			$index=0;
            eval("\$valueToSort = \$item->$objectAttribut;");
			if ($index<count($tmpArray)) {
                eval("\$valueToItem = \$tmpArray[$index]->$objectAttribut;");
				while ($index<count($tmpArray) && ($valueToSort > $valueToItem)) {
					$index++;
					if ($index<count($tmpArray)) {
						eval("\$valueToItem = \$tmpArray[$index]->$objectAttribut;");
					}
				}
			}
			self::insertAt($tmpArray, $item, $index);
		}
		self::clearArray($objectArray);
		$objectArray = $tmpArray;
	}

    /**
     * Delete prohiben entriesin array
     *
     * @param array $array
     * @param array $prohibedEntries
     *
     * @return array
     */
    public static function deleteProhibedEntries($array, $prohibedEntries){
        $newArray = array();
        foreach ($array as $item){
            if (!in_array($item, $prohibedEntries)){
                $newArray[] = $item;
            }
        }
        return $newArray;

    }

    /**
     * Fill assoc array with other assoc array (only common keys)
     * 
     * @param array $array	Array to fill
     * @param array $values Array with values
     */
    public static function fillCommonKeys(&$array, $values){
    	foreach ($values as $key => $value) {
    		if (isset($array[$key])){
    			$array[$key] = $value;
    		}
    	}
    }
    
    /**
     * Fill assoc array with other assoc array (only common keys)
     *
     * @param array $values	Array of values
     * @param array $value  Default values for new array
     */
    public static function createFromValues($values, $defaultValue = 0){
    	$result = array();
    	foreach ($values as $key => $value) {
    		if (!isset($result[$value])){
    			$result[$value] = $defaultValue;
    		}
    	}
    	return $result;
    }


    public static function insert(&$array, $position, $insert)
    {

        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {

            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
            $position = $pos;
        }
        return $position;
    }
}

?>