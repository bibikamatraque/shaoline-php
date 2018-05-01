<?php

/**
 * Description of ShaUser
 *
 * PHP version 5.2
 *
 * @category   ShaDao
 * @package    Core
 * @subpackage Core
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    mon-referendum.com copyright
 * @link       No link
 *
 */
abstract class ShaPlugin
{

    /**
     * Return directory name
     */
    public abstract function getName();

    /**
     * Setup procedure
     *
     * @return void
     */
    abstract public function setup();

    /**
     * Return plugin path
     *
     * @return string
     */
    public function getPath()
    {
        /** @type ShaPlugin $class */
        $class = get_called_class();
        return ShaContext::getPathToPlugins().$class::CONST_DIRECTORY . "/";
    }

    /**
     * Insert flag in datas base to mark plugin has installed
     *
     * @param string $name Plugin name
     *
     * @return void
     */
    protected function setInstalled()
    {
    	$key = 'PLUGIN_' . strtoupper($this->getName());
    	if (ShaParameter::exist($key)){
    		return ShaParameter::set($key, "1");
    	} else {
    		ShaHelper::add()->parameter($key, "1", "Insatlation of plugin : ".$this->getName());
    	}
    }

    /**
     * Return true if plugins basic datas are already installed
     *
     * @param string $name Plugin name
     *
     * @return bool
     */
    protected function isInstalled()
    {
    	$key = 'PLUGIN_' . strtoupper($this->getName());
    	if (ShaParameter::exist($key)){
    		return (ShaParameter::get($key) == 1);
    	} 
    	return false;
    }
    
    /**
     * Return plugins treatments progress
     */
    public function getProgress(){
    	return "";
    }

	public function instanciateAllCmoInFolder(){
		ShaContext::instanciateAllCmoInFolder(ShaContext::getPathToPlugins() . $this->getName());
	}
    
}

?>