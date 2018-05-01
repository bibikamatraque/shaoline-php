<?php

class ShaOperationListTree extends ShaOperation
{

    const CONST_OPERATION_LIB = "OPERATION_LIST_TREE";

    protected $_domId;
    protected $_daoClass = null;
    protected $_daoPrimaryKeys = null;
    protected $_tree = false;
    protected $_treeDatas = array();
    protected $_buildAll = true;
    protected $_cssClass = null;
    protected $_parentKey = null;

    /** @type bool $_activeRefreshButton */
    protected $_activeRefreshButton;
    /** @type bool $_activeAddButton */
    protected $_activeAddButton;
    /** @type bool $_activeDeleteButton */
    protected $_activeDeleteButton;
    /** @type bool $_activeEditBtn */
    protected $_activeEditBtn;

    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    /**
     * @return string
     */
    public function getDaoClass()
    {
        return $this->_daoClass;
    }

    /**
     * @param string $daoClass
     *
     * @return ShaOperationListTree
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }

    /**
     * @param null $parentKey
     *
     * @return ShaOperationListTree
     */
    public function setParentKey($parentKey)
    {
        $this->_parentKey = $parentKey;
        return $this;
    }

    /**
     * @return null
     */
    public function getParentKey()
    {
        return $this->_parentKey;
    }

    /**
     * @return boolean
     */
    public function isTree()
    {
        return $this->_tree;
    }

    /**
     * @param string $cssClass
     *
     * @return ShaOperationListTree
     */
    public function setCssClass($cssClass)
    {
        $this->_cssClass = $cssClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getCssClass()
    {
        return $this->_cssClass;
    }

    /**
     * @param boolean $tree
     *
     * @return ShaOperationEditTree
     */
    public function setTree($tree)
    {
        $this->_tree = $tree;
        return $this;
    }

    /**
     * @return array
     */
    public function getTreeDatas()
    {
        return $this->_treeDatas;
    }

    /**
     * @param array $treeDatas
     *
     * @return ShaOperationEditTree
     */
    public function setTreeDatas($treeDatas)
    {
        $this->_treeDatas = $treeDatas;
        return $this;
    }

    /**
     * @return string
     */
    public function getDaoPrimaryKeys()
    {
        return $this->_daoPrimaryKeys;
    }

    /**
     * @param string $daoPrimaryKeys
     *
     * @return ShaOperationEditTree
     */
    public function setDaoPrimaryKeys($daoPrimaryKeys)
    {
        $this->_daoPrimaryKeys = $daoPrimaryKeys;
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
     * @return ShaOperationEditTree
     */
    public function setDomId($domId)
    {
        $this->_domId = $domId;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isBuildAll()
    {
        return $this->_buildAll;
    }

    /**
     * @param boolean $buildAll
     *
     * @return ShaOperationEditTree
     */
    public function setBuildAll($buildAll)
    {
        $this->_buildAll = $buildAll;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActiveAddButton()
    {
        return $this->_activeAddButton;
    }

    /**
     * @param boolean $activeAddButton
     *
     * @return ShaOperationEditTree
     */
    public function setActiveAddButton($activeAddButton)
    {
        $this->_activeAddButton = $activeAddButton;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActiveDeleteButton()
    {
        return $this->_activeDeleteButton;
    }

    /**
     * @param boolean $activeDeleteButton
     *
     * @return ShaOperationEditTree
     */
    public function setActiveDeleteButton($activeDeleteButton)
    {
        $this->_activeDeleteButton = $activeDeleteButton;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActiveEditBtn()
    {
        return $this->_activeEditBtn;
    }

    /**
     * @param boolean $activeEditBtn
     *
     * @return ShaOperationEditTree
     */
    public function setActiveEditBtn($activeEditBtn)
    {
        $this->_activeEditBtn = $activeEditBtn;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActiveRefreshButton()
    {
        return $this->_activeRefreshButton;
    }

    /**
     * @param boolean $activeRefreshButton
     *
     * @return ShaOperationEditTree
     */
    public function setActiveRefreshButton($activeRefreshButton)
    {
        $this->_activeRefreshButton = $activeRefreshButton;
        return $this;
    }

    /**
     * Construct editing formulaire for treetype  object
     *
     * @throws Exception
     * @return string
     */
    public function run(){

    }

    public function render(){

        $cssClass = ($this->getCssClass() != null) ? " class = '".$this->getCssClass()."' " : "";

        /** @var ShaOperationEditTree $instance */
        $instance = new \ReflectionClass($this->getDaoClass());
        if (!$instance->load($this->getDaoPrimaryKeys())) {
            throw new Exception(
                __CLASS__."::".__FUNCTION__.": Error class not found : ".$this->getDaoClass());
        }

        $ShaOperationEdit = new ShaOperationEditTree();
        $ShaOperationEdit
            ->setBuildAll(false)
            ->setDomId(ShaContext::getNextContentId())
            ->setDaoClass($this->getDaoClass())
            ->setDaoPrimaryKeys($this->getDaoPrimaryKeys())
            ->save();

        $list = "";

        $html = $instance->drawEditingRecurciveTreeFormulaire($list, $ShaOperationEdit);
        if ($this->_buildAll) {

            $html = "
                <div $cssClass>
                    <ul id='".$ShaOperationEdit->getDomId()."' class='filetree'>
                        $html
                    </ul>
                    <div class='clear'></div>
                    <div class='cms_cmo_list_paggination'>
                        <div class='cms_cmo_list_btn_refresh' ".$ShaOperationEdit->getDomEvent()."></div>
                    </div>
                    </div class='clear'></div>
                 </div>
			";

        }

        $ShaResponse = new ShaResponse();
        $ShaResponse
            ->setRenderDomId($this->getDomId())
            ->setContent($html)
            ->setRenderer(ShaResponse::CONST_RENDERER_DIV)
        ;
        return $ShaResponse;

    }

}

?>