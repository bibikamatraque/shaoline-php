<?php


/**
 * yaml parser
 *
 * PHP version 5.3
 *
 * @category   ShaUtils
 * @package    Shaoline
 * @subpackage Php
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    mon-referendum.com copyright
 * @link        No link
 *
 */
class ShaUtilsYaml {

    /**
     * Parse yaml content and return array
     *
     * @param string $content
     * @param int $indent
     *
     * @return array
     */
    public static function parse($content, $indent = 4){

        //TODO : use yaml_parse if defined !

        $root = new YamlNode();
        /** @type YamlNode $currentNode */
        $currentNode = $root;
        $lastKey = null;
        /** @type YamlNode $parentNode */
        $parentNode = null;

        $lines = explode("\n", $content);

        foreach ($lines as $line ) {
            $qtyIndent = self::_getQtyIndent($line, $indent);
            $keyValue = self::_getKeyValue($line);
            $qtyValue = count($keyValue);

            //The is at less one value
            if ($qtyValue != 0 && trim($keyValue[0]) != "") {

                //If we are on the indentation level that current node
                if ($currentNode->getDeep() == $qtyIndent) {
                    if ($qtyValue == 1) {
                        $currentNode->setIsArray(true);
                        $currentNode->addValue($keyValue[0]);
                    } else {
                        $lastKey = $keyValue[0];
                        $currentNode->addChild($keyValue[0], $keyValue[1]);
                    }

                }

                //Go deeper
                elseif ($currentNode->getDeep() < $qtyIndent) {

                    //Array value mode
                    if ($qtyValue == 1){
                        $currentNode = $currentNode->getChildren($lastKey);
                        $currentNode->setIsArray(true);
                        $currentNode->addValue($keyValue[0]);
                        $lastKey = $keyValue[0];
                    }
                    //Add child
                    else {
                        $currentNode = $currentNode->getChildren($lastKey);
                        $currentNode->addChild($keyValue[0], $keyValue[1]);
                        $lastKey = $keyValue[0];
                    }
                }

                //Go deepless
                else if ($currentNode->getDeep() > $qtyIndent) {
                    $currentNode = $currentNode->getAncestor($qtyIndent);
                    $lastKey = $keyValue[0];
                    if ($qtyValue == 1) {
                        $currentNode->addChild($keyValue[0], null);
                    } else {
                        $currentNode->addChild($keyValue[0], $keyValue[1]);
                    }

                }

            }
        }

        $output = array();
        self::_converToArray($root, $output);
        return $output;
    }

    /**
     * Return qty indent
     *
     * @param string $line
     * @param int $indent
     *
     * @return int
     */
    private static function _getQtyIndent($line, $indent){
        $parts = explode(str_repeat(" ", $indent), $line);

        $qty = count($parts);
        if ($qty == 0){
            return 0;
        }

        $count = 0;
        while ($count < $qty && $parts[$count] == "") {
            $count++;
        }
        return $count;
    }

    /**
     * Return key/value
     */
    private static function _getKeyValue($line){
        if ($line == ""){
            return array();
        }
        $tmpLine = trim($line);
        if (substr($tmpLine, 0, 1) == "#") {
            return array();
        }
        $parts = explode(":", $line);

        if (count($parts) == 1){
            return array(trim($parts[0]));
        } else {

            $key = ShaUtilsString::replace($parts[0], " ", "");
            unset($parts[0]);
            $value = implode(":", $parts);
            $value = trim($value);
            return array($key, $value);

        }


    }

    /**
     * Convert YamlNode to array
     *
     * @param YamlNode $yamlNode
     * @param array &$output
     *
     * return array
     */
    private static function _converToArray($yamlNode, &$output){

        if (is_array($yamlNode)){
            return;
        }

        $child = $yamlNode->getChild();
        /** @type YamlNode $children */
        foreach ($child as $children) {

            if (trim($children->getKey()) != "") {

                if (count($children->getChild()) == 0){
                    $values = $children->getValues();
                    $qtyValues = count($values);

                    if ($children->getIsArray()){
                        $output[$children->getKey()] = $values;
                    } else {

                        if ($qtyValues == 0) {
                            $output[$children->getKey()] = "";
                        } elseif ($qtyValues == 1) {
                            if ($values[0] == "~") {
                                $output[$children->getKey()] = null;
                            } else {
                                $output[$children->getKey()] = $values[0];
                            }
                        } elseif ($qtyValues > 1) {
                            $output[$children->getKey()] = $values;
                        }
                    }
                } else {
                    $output[$children->getKey()] = array();
                    self::_converToArray($yamlNode->getChildren($children->getKey()), $output[$children->getKey()]);
                }
            }
        }
    }

}


class YamlNode {

    private $_key = "root";
    private $_values = array();
    private $_parent = null;
    private $_child = array();
    private $_deep = 0;
    private $_isArray = false;

    /**
     * Add children
     *
     * @param $key
     * @param $value
     */
    public function addChild($key, $value) {
        $yamlNode = new YamlNode();
        $yamlNode->_key = $key;
        $yamlNode->_values= (isset($value)) ? array($value) : array();
        $yamlNode->_parent = $this;
        $yamlNode->_deep = $this->_deep + 1;

        $this->_child[$key] = $yamlNode;
    }

    public function setIsArray($value){
        $this->_isArray = $value;
    }

    public function getIsArray(){
        return $this->_isArray;
    }

    public function addValue($value){
        $this->_values[] = $value;
        $this->_values = ShaUtilsArray::deleteProhibedEntries($this->_values, array(""));
    }

    /**
     * Return value
     *
     * @return string
     */
    public function getValues(){
        return $this->_values;
    }

    /**
     * Return key
     */
    public function getKey(){
        return $this->_key;
    }

    /**
     * Return children
     */
    public function getChildren($key){
        return $this->_child[$key];
    }

    /**
     * Return node deep
     *
     * @return int
     */
    public function getDeep(){
        return $this->_deep;
    }

    /**
     * Return parent node
     *
     * @return YamlNode
     */
    public function getParent(){
        return $this->_parent;
    }

    /**
     * Return child
     *
     * @return array
     */
    public function getChild(){
        return $this->_child;
    }

    /**
     * Return ancestor
     *
     * @param int $qtyIndent
     *
     * @return YamlNode
     */
    public function getAncestor($qtyIndent){
        $node = $this;
        $qtyBack = $this->getDeep() - $qtyIndent;
        while ($qtyBack > 0){
            $node = $node->_parent;
            $qtyBack--;
        }
        return $node;
    }

}