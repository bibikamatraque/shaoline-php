<?php

/**
 * Class Configuration
 * Use this class to load configuration file
 * ShaFormat allowed :
 *  YAML
 *  XML
 *
 * @category   CMS
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaConfiguration {

    /** @typ array $_configuration */
    private $_configuration = array();

    /**
     * Load datas from YAML file path
     *
     * @param $path
     */
    public function loadFromYamlFile($path){
        $this->loadFromYaml(file_get_contents($path));
    }

    /**
     * Load datas from YAML string sequence
     *
     * @param $content
     */
    public function loadFromYaml($content){
        $this->_configuration = ShaUtilsYaml::parse($content);
    }

    /**
     * Load datas from XML file path
     *
     * @param $path
     */
    public function loadFromXmlFile($path){
        $this->loadFromXml(file_get_contents($path));
    }

    /**
     * Load datas from XML string sequence
     *
     * @param $content
     */
    public function loadFromXml($content){
        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $this->_configuration = json_decode($json,TRUE);
    }

    /**
     * Check if configuration path exist
     *
     * Ex : $this->get("root/bdd/host"); => true
     *
     * @param string $path Path to test
     *
     * @return bool
     */
    public function has($path){
        $pathItems = explode("/", $path);
        $node = $this->_configuration;
        foreach ($pathItems as $item){
            if (!isset($node[$item])) { return false; }
            $node = $node[$item];
        }
        return true;
    }

    /**
     * Return configuration value
     *
     * Ex : $this->get("root/bdd/host"); => 127.0.0.1
     *
     * @param string $path Path to value
     * @param mixed $default Default value
     *
     * @return mixed Return default value if not found (default = null)
     */
    public function get($path, $default = null){
        $pathItems = explode("/", $path);
        $node = $this->_configuration;
        foreach ($pathItems as $item){
            if (!isset($node[$item])) { return $default; }
            $node = $node[$item];
        }
        return $node;
    }

    /**
     * Return node child name
     */
    public function listParams($path) {
    	$root = $this->get($path);
    	$result = array();
    	foreach ($root as $key => $value){
    		$result[$key] = $value;
    	}
    	return $result;
    }
    
}