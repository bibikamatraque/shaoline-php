<?php

/**
 * Class ShaOperationType
 * This class is use to générate pagginated list of dao object datas
 *
 * 		*class 						=> Class name to list
 * 		div 						=> Div ID (utils if buildAll = true)
 * 		*buildAll 					=> Build all structure (<div id='DIV'>...</div>)
 * 		sqlCondition 				=> Sql condition for listing
 * 		additionalSelect 			=> Additional SQL select datas
 * 		additionalCondition 		=> Additional SQL where clause datas
 * 		additionalOrder 			=> Additional SQL Oder datas
 * 		additionalLimit 			=> Additional SQL limit datas (used in ShaDaoList::count(...);)
 * 		start 						=> Starting item index
 * 		limit 						=> Qty of items by page
 * 		*autoGenerateHeaderForPopin => Adding header for popin
 * 		positionLabel 				=> Position of paggination label (left,middle,right) default:middle
 * 		infoPagginationBefore 		=> Text on the left of paggination label
 * 		infoPagginationMiddle 		=> Text on in the middle of paggination label
 * 		infoPagginationAfter 		=> Text on the right of paggination label
 * 		globalButtons 				=> List of additional buttons (src,alt,title,js)
 * 		addBtnAllowed 				=> True for add "ADD BUTTON"
 * 		deleteBtnAllowed 			=> True for add "DELETE BUTTON"
 * 		reference 					=> Association button (mappingShaOperation)
 * 		ShaOperation 				=> ShaOperation name (for title)
 * 		buttons						=> List of ShaDao buttons
 * 		renderFunction				=> Use specific render function ()
 * 		fields						=> ShaBddField list configuration (renderFunction must be empty)
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */

class ShaOperationList extends ShaOperation
{
    /** @type string $_select */
    protected $_select = "";
    /** @type string $_condition */
    protected $_condition = "";
    /** @type string $_order */
    protected $_order = "";

    /** @type int $_currentPage */
    protected $_currentPage = 0;
    /** @type int $_pageLength */
    protected $_pageLength = 10;

    /** @type string $_daoClass */
    protected $_daoClass;
    /** @type string $_pagginationPrefix */
    protected $_pagginationPrefix = "";
    /** @type string $_pagginationSuffix */
    protected $_pagginationSuffix = "";
    /** @type string $_pagginationSeparator */
    protected $_pagginationSeparator = "/";
    /** @type string $_pagginactionLabelPosition */
    protected $_pagginactionLabelPosition = "middle";
    /** @type int $_pagginationQtyPage */
    protected $_pagginationQtyPage = 0;

    /** @type string $_domId */
    protected $_domId = null;
    /** @type string $_cssClass */
    protected $_cssClass = "cms_default_admin_listing";
    /** @type bool $_buildAll */
    protected $_buildAll = false;
    /** @type bool $_buidBorder */
    protected $_buidBorder = true;
    /** @type bool $_autoGenerateHeaderForPopin */
    protected $_autoGenerateHeaderForPopin = false;

    /** @type ShaForm $_fieldForm */
    protected $_fieldForm = null;
    /** @type bool $_forceReloadDefaultFieldList */
    protected $_forceReloadDefaultFieldList = true;
    /** @type string $_renderFunction */
    protected $_renderFunction = null;
    /** @type array $_renderFunctionParameters */
    protected $_renderFunctionParameters = array();

    /** @type array $_buttons */
    protected $_buttons = array();

    /** @type bool $_activeRefreshButton */
    protected $_activeRefreshButton = true;
    /** @type bool $_activeAddButton */
    protected $_activeAddButton = true;
    /** @type bool $_activeDeleteButton */
    protected $_activeDeleteButton = true;
    /** @type bool $_activeEditBtn */
    protected $_activeEditBtn = true;
    /** @type bool $_activeSearchButton */
    protected $_activeSearchButton = true;
    /** @type bool $_activeSearchButton */
    protected $_header = "";
    /** @type bool $_activeSearchButton */
    protected $_footer = "";
    /** @type ShaRelation $_relation */
    protected $_relation = null;
	/** @type int $_empty */
    protected $_empty = true;
    /** @type $_pagginationIfOnePage*/
    protected $_pagginationIfOnePage=true;
    
    /** @type array */
    protected $_commonValues = array();
    
    const CONST_OPERATION_LIB = "OPERATION_LIST";



    /**
     * @param \ShaForm $fieldForm
     */
    public function setFieldForm($fieldForm)
    {
    	$this->_fieldForm = $fieldForm;
    }
    
    /**
     * @return \ShaForm
     */
    public function getFieldForm()
    {
    	return $this->_fieldForm;
    }
    
    /**
     * @param boolean $footer
     *
     * @return ShaOperationList
     */
    public function setFooter($footer)
    {
    	$this->_footer = $footer;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getFooter()
    {
    	return $this->_footer;
    }
    
    /**
     * @param boolean $header
     *
     * @return ShaOperationList
     */
    public function setHeader($header)
    {
    	$this->_header = $header;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getHeader()
    {
    	return $this->_header;
    }
    
    /**
     * @return boolean
     */
    public function isAutoGenerateHeaderForPopin()
    {
    	return $this->_autoGenerateHeaderForPopin;
    }
    
    /**
     * @param boolean $autoGenerateHeaderForPopin
     *
     * @return ShaOperationList
     */
    public function setAutoGenerateHeaderForPopin($autoGenerateHeaderForPopin)
    {
    	$this->_autoGenerateHeaderForPopin = $autoGenerateHeaderForPopin;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function isBuidBorder()
    {
    	return $this->_buidBorder;
    }
    
    /**
     * @param boolean $buidBorder
     *
     * @return ShaOperationList
     */
    public function setBuidBorder($buidBorder)
    {
    	$this->_buidBorder = $buidBorder;
    	return $this;
    }
    
    /**
     * @return boolean
     * @deprecated
     */
    public function isBuildAll()
    {
    	return $this->_buildAll;
    }
    
    /**
     * @param boolean $buildAll
     * @deprecated
     *
     * @return ShaOperationList
     */
    public function setBuildAll($buildAll)
    {
    	$this->_buildAll = $buildAll;
    	return $this;
    }
    
    /**
     * @return array
     */
    public function getButtons()
    {
    	return $this->_buttons;
    }
    
    /**
     * @param array $buttons
     *
     * @return ShaOperationList
     */
    public function setButtons($buttons)
    {
    	$this->_buttons = $buttons;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getCondition()
    {
    	return $this->_condition;
    }
    
    /**
     * @param string $condition
     *
     * @return ShaOperationList
     */
    public function setCondition($condition)
    {
    	$this->_condition = $condition;
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
     * @param string $cssClass
     *
     * @return ShaOperationList
     */
    public function setCssClass($cssClass)
    {
    	$this->_cssClass = $cssClass;
    	return $this;
    }
    
    /**
     * @return int
     */
    public function getCurrentPage()
    {
    	return $this->_currentPage;
    }
    
    /**
     * @param int $currentPage
     *
     * @return ShaOperationList
     */
    public function setCurrentPage($currentPage)
    {
    	$this->_currentPage = $currentPage;
    	return $this;
    }
    
    /**
     * @return mixed
     */
    public function getDaoClass()
    {
    	return $this->_daoClass;
    }
    
    /**
     * @param mixed $daoClass
     *
     * @return ShaOperationList
     */
    public function setDaoClass($daoClass)
    {
    	$this->_daoClass = $daoClass;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getDomId()
    {
    	return $this->_domId;
    }
    
    /**
     * @param string $domId
     *
     * @return ShaOperationList
     */
    public function setDomId($domId)
    {
    	$this->_domId = $domId;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getOrder()
    {
    	return $this->_order;
    }
    
    /**
     * @param string $order
     *
     * @return ShaOperationList
     */
    public function setOrder($order)
    {
    	$this->_order = $order;
    	return $this;
    }
    
    /**
     * @return int
     */
    public function getPageLength()
    {
    	return $this->_pageLength;
    }
    
    /**
     * @param int $pageLength
     *
     * @return ShaOperationList
     */
    public function setPageLength($pageLength)
    {
    	$this->_pageLength = $pageLength;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPagginactionLabelPosition()
    {
    	return $this->_pagginactionLabelPosition;
    }
    
    /**
     * @param string $pagginactionLabelPosition
     *
     * @return ShaOperationList
     */
    public function setPagginactionLabelPosition($pagginactionLabelPosition)
    {
    	$this->_pagginactionLabelPosition = $pagginactionLabelPosition;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPagginationQtyPag()
    {
    	return $this->_pagginationQtyPage;
    }
    
    /**
     * @param string $pagginactionLabelPosition
     *
     * @return ShaOperationList
     */
    public function setPagginationQtyPage($pagginationQtyPage)
    {
    	$this->_pagginationQtyPage = $pagginationQtyPage;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPagginationPrefix()
    {
    	return $this->_pagginationPrefix;
    }
    
    /**
     * @param string $pagginationPrefix
     *
     * @return ShaOperationList
     */
    public function setPagginationPrefix($pagginationPrefix)
    {
    	$this->_pagginationPrefix = $pagginationPrefix;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPagginationSeparator()
    {
    	return $this->_pagginationSeparator;
    }
    
    /**
     * @param string $pagginationSeparator
     *
     * @return ShaOperationList
     */
    public function setPagginationSeparator($pagginationSeparator)
    {
    	$this->_pagginationSeparator = $pagginationSeparator;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPagginationSuffix()
    {
    	return $this->_pagginationSuffix;
    }
    
    /**
     * @param string $pagginationSuffix
     *
     * @return ShaOperationList
     */
    public function setPagginationSuffix($pagginationSuffix)
    {
    	$this->_pagginationSuffix = $pagginationSuffix;
    	return $this;
    }
    
    /**
     * Return Relation
     *
     * @return ShaRelation
     */
    public function getRelation()
    {
    	return $this->_relation;
    }
    
    /**
     * @param ShaRelation $relation
     *
     * @return ShaOperationList
     */
    public function setRelation($relation)
    {
    	$this->_relation = $relation;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getSelect()
    {
    	return $this->_select;
    }
    
    /**
     * @param string $select
     *
     * @return ShaOperationList
     */
    public function setSelect($select)
    {
    	$this->_select = $select;
    	return $this;
    }
    
    /**
     * @param boolean $activeAddButton
     *
     * @return ShaOperationList
     */
    public function setActiveAddButton($activeAddButton)
    {
    	$this->_activeAddButton = $activeAddButton;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getActiveAddButton()
    {
    	return $this->_activeAddButton;
    }
    
    /**
     * @param boolean $activeDeleteButton
     *
     * @return ShaOperationList
     */
    public function setActiveDeleteButton($activeDeleteButton)
    {
    	$this->_activeDeleteButton = $activeDeleteButton;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getActiveDeleteButton()
    {
    	return $this->_activeDeleteButton;
    }
    
    /**
     * @param boolean $activeEditBtn
     *
     * @return ShaOperationList
     */
    public function setActiveEditBtn($activeEditBtn)
    {
    	$this->_activeEditBtn = $activeEditBtn;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getActiveEditBtn()
    {
    	return $this->_activeEditBtn;
    }
    
    /**
     * @param boolean $activeRefreshButton
     *
     * @return ShaOperationList
     */
    public function setActiveRefreshButton($activeRefreshButton)
    {
    	$this->_activeRefreshButton = $activeRefreshButton;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getActiveRefreshButton()
    {
    	return $this->_activeRefreshButton;
    }
    
    /**
     * @return string
     */
    public function getRenderFunction()
    {
    	return $this->_renderFunction;
    }
    
    /**
     * @param string $renderFunction
     *
     * @return ShaOperationList
     */
    public function setRenderFunction($renderFunction)
    {
    	$this->_renderFunction = $renderFunction;
    	return $this;
    }
    
    /**
     * @return array
     */
    public function getRenderFunctionParameters()
    {
    	return $this->_renderFunctionParameters;
    }
    
    /**
     * @param array $renderFunctionParameters
     *
     * @return ShaOperationList
     */
    public function setRenderFunctionParameters($renderFunctionParameters)
    {
    	$this->_renderFunctionParameters = $renderFunctionParameters;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function isForceReloadDefaultFieldList()
    {
    	return $this->_forceReloadDefaultFieldList;
    }
    
    /**
     * @param boolean $forceReloadDefaultFieldList
     *
     * @return ShaOperationList
     */
    public function setForceReloadDefaultFieldList($forceReloadDefaultFieldList)
    {
    	$this->_forceReloadDefaultFieldList = $forceReloadDefaultFieldList;
    	return $this;
    }
    
    
    /**
     * Definie if paggination must be displayed when list containe only one page
     * 
     * @param bool $status
     * 
     * @return ShaOperationList
     */
    public function setPagginationIfOnePage($status){
    	$this->_pagginationIfOnePage = $status;
    	return $this; 
    }   
     
    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    /**
     * Return true if list is empty
     * 
     * @return boolean
     */
    public function isEmpty(){
    	return $this->_empty;
    }

    
    
    /**
     * Draw Abstract Cms object listing width paggination
     *
     * @return mixed
     */
    public function run() {

    	
        if (!isset($this->_domId)){
            $this->_domId = ShaContext::getNextContentId();
        }

        $relation = $this->getRelation();
       
        /* @var ShaCmo $class */
        $class = $this->_daoClass;
        /* @var ShaCmo $instanceClass */
        $instanceClass = new $class();

        //Get field description if not set and if dao list
        if (!isset($this->_fieldForm) || $this->_forceReloadDefaultFieldList) {
            $this->_fieldForm = $instanceClass->defaultLineRender();
        }

        $ShaOperationListTmp = clone $this;
        $ShaOperationListTmp->_buidBorder = false;
        $ShaOperationListTmp->_autoGenerateHeaderForPopin = false;
        
        //Count total line qty
        $qtyObject = $instanceClass::countByWhereClause(
            $this->_condition,
            $this->getRelation()
        );
       
        $totalPage = ($this->_pageLength == 0) ? 0 : ceil($qtyObject / $this->_pageLength);
        $this->_empty = ($qtyObject == 0);

        $this->_currentPage = min($this->_currentPage, $totalPage);
        $start = $this->_currentPage * $this->_pageLength;
        $limit = $this->_pageLength;

        //Get Object list
        $objects = $class::getList(
            $this->_condition,
            $this->_order,
            $start,
            $limit,
            $this->getRelation(),
            $this->_select
        );

        //FIRST
        $ShaOperationListTmp->_currentPage = 0;
        $ShaOperationListTmp->save();
        $startBtn = ($this->_currentPage > 0) ? "<div class='cms_cmo_list_btn cms_cmo_list_btn_first active' ".$ShaOperationListTmp->getDomEvent()." >«</div>" : "<div class='cms_cmo_list_btn cms_cmo_list_btn_first'>«</div>";

        //PREVIEWS
        $ShaOperationListTmp->_currentPage = max(0, $this->_currentPage - 1);
        $ShaOperationListTmp->save();
        $previousBtn = ($this->_currentPage > 0) ? "<div class='cms_cmo_list_btn cms_cmo_list_btn_previous active' ".$ShaOperationListTmp->getDomEvent()." ><</div>" : "<div class='cms_cmo_list_btn cms_cmo_list_btn_previous'><</div>";
        $previewPage = ($this->_currentPage > 0)? "<div class='cms_cmo_list_btn active' ".$ShaOperationListTmp->getDomEvent().">".$this->_currentPage."</div>":"";

        //NEXT
        $ShaOperationListTmp->_currentPage = $this->_currentPage + 1;
        $ShaOperationListTmp->save();
        $nextBtn = ($this->_currentPage < $totalPage-1) ? "<div class='cms_cmo_list_btn cms_cmo_list_btn_next active' ".$ShaOperationListTmp->getDomEvent()." >></div>" : "<div class='cms_cmo_list_btn cms_cmo_list_btn_next'>></div>";
        $nextPage = ($this->_currentPage < $totalPage-1) ? "<div class='cms_cmo_list_btn active' ".$ShaOperationListTmp->getDomEvent().">".($this->_currentPage + 2)."</div>":"";

        //LAST
        $ShaOperationListTmp->_currentPage = $totalPage - 1;
        $ShaOperationListTmp->save();
        $lastBtn = ($this->_currentPage < $totalPage-1) ? "<div class='cms_cmo_list_btn cms_cmo_list_btn_last active' ".$ShaOperationListTmp->getDomEvent()."  >»</div>" : "<div class='cms_cmo_list_btn cms_cmo_list_btn_last'>»</div>";

        //INFO PAGE
        $infoPaggination = "<span class='cms_cmo_list_paggination_label'>" . $this->_pagginationPrefix.($this->_currentPage + 1) . $this->_pagginationSeparator . $totalPage . $this->_pagginationSuffix."</span>";
        $currentPage = "<div class='cms_cmo_list_btn current_page'>".($this->_currentPage + 1)."</div>";

        //PAGGINATION LABEL
        if ($this->_pagginactionLabelPosition == "left") {
            $pagginationBtn = $infoPaggination.$startBtn.$previousBtn.$previewPage.$currentPage.$nextPage.$nextBtn.$lastBtn;
        } elseif ($this->_pagginactionLabelPosition == "right") {
            $pagginationBtn = $startBtn.$previousBtn.$infoPaggination.$lastBtn.$nextBtn.$nextPage.$currentPage.$previewPage;
        } else {
            $pagginationBtn = $infoPaggination.$startBtn.$previousBtn.$lastBtn.$nextBtn;
        }

        if ($totalPage <= 1){
        	if ($this->_pagginationIfOnePage){
        		$pagginationBtn = "<span class='cms_cmo_list_paggination_label'>0/0</span>";
        	} else {
        		$pagginationBtn = "";
        	}
        }

        //GLOBAL STRUCTURE
        $html = ($this->_buidBorder) ? "<div id='" . $this->_domId . "'>" : "";
        $html .= "
			<div class='" . $this->_cssClass . " cms_cmo_list'>
			    [HEADER]
				[DATAS]
				[FOOTER]
				<div class='cms_cmo_list_paggination'>
					" . $pagginationBtn . "
					<div class='clear'></div>
					[BOTTOM_BUTTONS]
				</div>
			</div>
			";
        
        $html .= ($this->_buidBorder) ? "</div>" : "";

        //More BTN

        $bottomButtons = "<div class='cms_cmo_list_command'>";

        //REFRESH BUTTON
        $operationRefresh = null;
        if ($this->_activeRefreshButton) {
            $operationRefresh = clone $this;
            $operationRefresh->setBuidBorder(false);
            $operationRefresh->save();

            $refreshButton = new ShaButton();
            $refreshButton
                ->setTitle(ShaContext::t("refresh"))
                ->setAction($operationRefresh->getDomEvent())
                ->setCssClass("shaoline_list_btn_refresh");
            $bottomButtons .= $refreshButton->render();
        }

        //ADD BUTTON
        if ($this->_activeAddButton) {

            $operationAdd = new ShaOperationAdd($this);
            $operationAdd
                ->setOperationList($this)
                ->setRefreshActions($this->getRefreshActions())
                ->addRefreshAction($operationRefresh)
                ->save();

            $addButton = new ShaButton();
            $addButton
                ->setTitle(ShaContext::t("add"))
                ->setAction($operationAdd->getDomEvent())
                ->setCssClass("shaoline_list_btn_add");

            $bottomButtons .= $addButton->render();
        }
        //SEARCH BUTTON
        if ($this->_activeSearchButton) {

            $operationSearch = new ShaOperationSearch();
            $operationSearch
                ->setOperationList($operationRefresh->getGcId())
                ->save();

            $addButton = new ShaButton();
            $addButton
                ->setTitle(ShaContext::t("search"))
                ->setAction($operationSearch->getDomEvent())
                ->setCssClass("shaoline_list_btn_search");

            $bottomButtons .= $addButton->render();
        }


        //OTHER BUTTON
        //TODO: Add other button gestion
        /* if (isset($configuration['globalButtons'])) {
            foreach ($configuration['globalButtons'] as $buttons) {
                $moreCommand.="<img alt='".$buttons['alt']."' title='".$buttons['title']."' src='".$buttons['src']."' onclick='".$buttons['js']."' />";
            }
        }*/

        $bottomButtons .= "</div>";
        $html = ShaUtilsString::replace($html, "[BOTTOM_BUTTONS]", $bottomButtons);

        $datas = "<div class='shaoline_list'>";
        $index = 0;
      
        
        /** @type ShaCmo $object */
        foreach ($objects as $object) {

            //DEFAULT RENDING
            if ($this->_renderFunction == null) {

            	//Get field description if not set and if dao list
            	if ($this->_forceReloadDefaultFieldList) {
            		$this->_fieldForm = $object->defaultLineRender();
            	}

                $moduloTwo = ($index % 2 == 0) ? "shaoline_modulo2" : "";
                $datas .= "<div class='shaoline_list_line " . $moduloTwo . "'>";
                $datas .= $this->_fieldForm->renderDao($object);


                //TODO ADD
                /*if (isset($configuration['buttons'])) {
                    foreach ($configuration['buttons'] as $buttons) {
                        $datas.=$object->drawDoActionBtn($buttons, $configuration, $configuration['div']);
                    }
                }*/

                //Btn d'édition
                $buttons = "";
                if ($this->_activeEditBtn) {

                    $operationEdit = new ShaOperationEdit();
                    $operationEdit
                        ->setDaoClass($class)
                        ->setDaoKeys($object->getPrimaryKeysAsArray())
                        ->save()
                    ;

                    $editButton = new ShaButton();
                    $editButton
                        ->setTitle(ShaContext::t("Edit"))
                        ->setAction($operationEdit->getDomEvent())
                        ->setCssClass("shaoline_list_btn_edit");

                    $buttons .= $editButton->render();

                }
                //Btn de suppression
                if (!isset($relation) || (isset($relation) != null && $relation->getCursor() == 0) && $this->_activeDeleteButton) {

                    $deleteOperation = new ShaOperationDelete();
                    $deleteOperation
                        ->setDaoClass($class)
                        ->setDaoKeys($object->getPrimaryKeysAsArray())
                        ->setRelation($this->getRelation())
                        ->setRefreshActions($this->getRefreshActions())
                        ->addRefreshAction($operationRefresh)
                        ->save()
                    ;

                    $deleteButton = new ShaButton();
                    $deleteButton
                        ->setTitle(ShaContext::t("Delete"))
                        ->setAction($deleteOperation->getDomEvent())
                        ->setCssClass("shaoline_list_btn_delete");

                    $buttons .= $deleteButton->render();

                }
                //Btn d'association
                if (isset($relation) && $relation->getCursor() == 1) {
                	
                    $operationAddMapping = new ShaOperationAddMapping();
                    $operationAddMapping
                        ->setDaoClass($class)
                        ->setDaoKeys($object->getPrimaryKeysAsArray())
                        ->setRelation($this->getRelation())
                        ->setRefreshActions($this->getRefreshActions())
                        ->addRefreshAction($operationRefresh)
                        ->save()
                    ;

                    $addMappingBtn = new ShaButton();
                    $addMappingBtn
                        ->setTitle(ShaContext::t("Delete"))
                        ->setAction($operationAddMapping->getDomEvent())
                        ->setCssClass("shaoline_list_btn_add_mapping");

                    $buttons .= $addMappingBtn->render();
                }
                $datas .= $buttons;

                $datas .= "<div class='clear'></div></div>";


            }
            //SPECIFIC RENDING
            else {
            	$function = $this->_renderFunction;
            	$this->_renderFunctionParameters['line_index'] = $index;
            	$datas .= $object->$function($this->_renderFunctionParameters, $this->_commonValues);
            }

            $index++;
        }

        $datas .= "<div class='clear'></div></div>";
        $html = ShaUtilsString::replace($html, "[DATAS]", $datas);
        $html = ShaUtilsString::replace($html, "[HEADER]", $this->_header);
        $html = ShaUtilsString::replace($html, "[FOOTER]", $this->_footer);


        //Changing page mode
        if (!$this->_buidBorder){

            $ShaResponse = new ShaResponse();
            $ShaResponse
                ->setRenderer(ShaResponse::CONST_RENDERER_DIV)
                ->setRenderDomId($this->_domId)
                ->setContent($html)
            ;

            $ShaResponse->render();
            return;
        }
        
        //Predefined response type
        if ($this->_shaResponse != null) {
        
        	$this->_shaResponse
        		->setContent($html)
        		->render()
        	;
        	return;
        
        } else {
        	return $html;
        }
 
    }


}