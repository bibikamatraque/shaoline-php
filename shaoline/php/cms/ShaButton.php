<?php

class ShaButton {

    private $_action;
    private $_cssClass;
    private $_title;


    public function render(){
        $cssClass = (isset($this->_cssClass)) ? " class='" . $this->_cssClass . "' " : "";
        $title = (isset($this->_title)) ? " title='" . ShaUtilsString::cleanForBalise($this->_title) . "' " : "";
        $result = "<tr><div $title $cssClass ".$this->_action."></div></tr>";
        return $result;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @param mixed $action
     *
     * @return ShaButton
     */
    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCssClass()
    {
        return $this->_cssClass;
    }

    /**
     * @param mixed $cssClass
     *
     * @return ShaButton
     */
    public function setCssClass($cssClass)
    {
        $this->_cssClass = $cssClass;
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
     * @return ShaButton
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }


}

?>