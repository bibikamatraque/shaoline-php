<?php

/**
 * Class ShaOperationEditTree
 * This manage cmo tree edit
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOperationEditTree extends ShaOperation
{

    protected $_domId;
    protected $_daoClass = "";
    protected $_daoPrimaryKeys = "";
    protected $_tree = false;
    protected $_treeDatas = array();
    protected $_buildAll = true;

    const CONST_OPERATION_LIB = "OPERATION_EDIT_TREE";

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
     * @return ShaOperationEditTree
     */
    public function setDaoClass($daoClass)
    {
        $this->_daoClass = $daoClass;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTree()
    {
        return $this->_tree;
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
     * Construct editing formulaire for treetype  object
     *
     * @throws Exception
     * @return string
     */
    public function run(){

    }


}

?>