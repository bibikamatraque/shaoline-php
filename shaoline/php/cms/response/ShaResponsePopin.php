<?php

/**
 * Class ShaResponsePopin
 * Popin description for http ShaResponse
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaResponsePopin extends ShaSerializable{

    protected $_domId;
    protected $_title;
    protected $_color;
    protected $_width;
    protected $_height;
    protected $_dragdrop;
    protected $_mask;
    protected $_style;
    
    private $_parent;

    /**
     * Constructor
     *
     * @param ShaResponse $parent
     */
    public function __construct($parent = null){
    	$this->_parent = $parent;
    }
    
    
    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->_color;
    }

    /**
     * @param mixed $color
     *
     * @return ShaResponsePopin
     */
    public function setColor($color)
    {
        $this->_color = $color;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomId()
    {
        return $this->_domId;
    }

    /**
     * @param mixed $domId
     *
     * @return ShaResponsePopin
     */
    public function setDomId($domId)
    {
        $this->_domId = $domId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @param mixed $height
     *
     * @return ShaResponsePopin
     */
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     *
     * @return ShaResponsePopin
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @param mixed $width
     *
     * @return ShaResponsePopin
     */
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    /**
     * @return ShaResponse
     */
    public function end(){
        return $this->_parent;
    }

    /**
     * @param mixed $dragdrop
     *
     * @return ShaResponsePopin
     */
    public function setDragdrop($dragdrop)
    {
        $this->_dragdrop = $dragdrop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDragdrop()
    {
        return $this->_dragdrop;
    }

    /**
     * @param mixed $mask
     *
     * @return ShaResponsePopin
     */
    public function setMask($mask)
    {
        $this->_mask = $mask;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMask()
    {
        return $this->_mask;
    }


    /**
     * @param mixed $style
     *
     * @return ShaResponsePopin
     */
    public function setStyle($style)
    {
    	$this->_style = $style;
    	return $this;
    }
    
    /**
     * @return mixed
     */
    public function getStyle()
    {
    	return $this->_style;
    }
}


?>