<?php

/**
 * Class ShaResponse
 * Http ShaResponse
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaResponse extends ShaSerializable
{

    const CONST_RENDERER_NOTHING = "NOTHING";
    const CONST_RENDERER_NORMAL = "NORMAL";
    const CONST_RENDERER_DIV = "DIV";
    const CONST_RENDERER_POPIN = "POPIN";
    const CONST_RENDERER_FULL = "FULL";
    const CONST_RENDERER_REDIRECT = "REDIRECT";

    const CONST_URL_CURRENT_PAGE = "SHA_CURRENT";

    protected $_renderer = null;
    protected $_content = "";
    protected $_renderDomId = null;
    protected $_popin = null;
    protected $_jsActions = null;
    protected $_phpActions = null;

    static public $_additionalJs = null; 

    /**
     * Add script to add in the js reponse code 
     * 
     * @param unknown $scriptName
     * @param unknown $scriptCode
     * 
     * @return ShaResponse
     */
    static public function addJsScript($scriptCode){
    	if (self::$_additionalJs == null) {
    		self::$_additionalJs = array();
    	}
    	self::$_additionalJs[] = $scriptCode;
    }
    
    /**
     * Constructor
     */
    public function __construct(){
        $this->_renderer = self::CONST_RENDERER_NORMAL;
        $this->_content = "";
        $this->_renderDomId = null;
        $this->_jsActions = array();
        $this->_phpActions = array();
        $this->_popin = new ShaResponsePopin($this);
    }

    /**
     * @return ShaResponsePopin
     */
    public function getPopin()
    {
        return $this->_popin;
    }

    /**
     * @return mixed
     */
    public function getRenderDomId()
    {
        return $this->_renderDomId;
    }

    /**
     * @param int $renderDomId
     *
     * @return ShaResponse
     */
    public function setRenderDomId($renderDomId)
    {
        $this->_renderDomId = $renderDomId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * @param int $renderer
     *
     * @return ShaResponse
     */
    public function setRenderer($renderer)
    {
        $this->_renderer = $renderer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param string $content
     *
     * @return ShaResponse
     */
    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJsActions()
    {
        return $this->_jsActions;
    }

    /**
     * @param array $jsActions
     *
     * @return ShaResponse
     */
    public function addJsActions($jsActions)
    {
    	if (is_array($jsActions)){
    		$this->_jsActions = array_merge( $this->_jsActions, $jsActions);
    	} else {
    		$this->_jsActions[] = $jsActions;
    	}
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhpActions()
    {
        return $this->_phpActions;
    }

    /**
     * @param mixed $phpActions
     *
     * @return ShaResponse
     */
    public function addPhpActions($phpActions)
    {
        $this->_phpActions[] = $phpActions;
        return $this;
    }

    /**
     * render
     *
     * @return string
     */
    public function getRender(){
    
    	if ($this->_renderer == self::CONST_RENDERER_NORMAL){
    		return $this->_content;
    	}
    
    	if ($this->_renderDomId == null){
    		$this->_renderDomId = ShaContext::getNextContentId();
    	}

        if (is_array(self::$_additionalJs)){
            foreach (self::$_additionalJs as $script) {
                $this->_jsActions[] = $script;
            }
        }

    	
    	$result = new stdClass();
    	$result->renderer = $this->_renderer;
    	$result->domId = $this->_renderDomId;
    	$result->content = $this->_content;
    	$result->jsActions = $this->_jsActions;
    	$result->phpActions = $this->_phpActions;
    
    	$result->popin = new stdClass();
    	$result->popin->domId = $this->_popin->getDomId();
    	$result->popin->title = $this->_popin->getTitle();
    	$result->popin->color = $this->_popin->getColor();
    	$result->popin->width = $this->_popin->getWidth();
    	$result->popin->height = $this->_popin->getHeight();
    	$result->popin->dragdrop = $this->_popin->getDragdrop();
    	$result->popin->activeMask = $this->_popin->getMask();
    	$result->popin->style = $this->_popin->getStyle();
    
    	if (!isset($result->popin->dragdrop)){
    		$result->popin->dragdrop = 1;
    	}
    	if (!isset($result->popin->mask)){
    		$result->popin->activeMask = 1;
    	}
    
    	return json_encode($result);
    
    }
    
    /**
     * render
     *
     * @return string
     */
    public function render(){

        echo $this->getRender();

    }

    /**
     * Return popin response
     *
     * @param $msg
     * @param $title
     * @param $color
     *
     * @return ShaResponse
     */
    public static function inlinePopinResponse($msg, $title, $color){
        $response = new ShaResponse();
        $response
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($msg)
            ->getPopin()
            ->setTitle(ShaContext::tj($title))
            ->setColor($color)
        ;
        return $response;
    }

}

?>