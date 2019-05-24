<?php

/**
 * Class ShaOperationAdd
 * This manage cmo adding ShaOperations in back office
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOperationAdd extends ShaOperation
{
    /** @type ShaOperationList $_operationList  */
    protected $_operationList = null;
    
    const CONST_OPERATION_LIB = "OPERATION_ADD";
    
    /**
     * Return the ShaOperation type lib
     *
     * @return string
     */
    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }
    
    
    /**
     * @param ShaOperationList $operationList
     *
     * @return ShaOperationAdd
     */
    public function setOperationList($operationList)
    {
        $this->_operationList = $operationList;
        return $this;
    }
    
    /**
     * @return ShaOperationAdd
     */
    public function getOperationList()
    {
        return $this->_operationList;
    }
    
    /**
     * Run operation
     *
     * @return null|ShaResponse
     */
    public function run(){
        
        
        $objA = null;
        $objB = null;
        
        /** @type ShaCmo $class */
        $class = $this->_operationList->getDaoClass();
        
        if ($this->_operationList->getRelation() == null){
            
            $response = new ShaResponse();
            
            /** @type ShaCmo $objA */
            $objA = new $class();
            $primaryKeys = $objA->getPrimaryFields();
            if (count($primaryKeys) == 1 && $objA->getField($primaryKeys[0])->isAutoValue()){
                $objA->save();
                $operation = new ShaOperationEdit();
                $operation
                ->setDaoClass($class)
                ->setDaoKeys($objA->getPrimaryKeysAsArray())
                ->save();
                $response
                ->addPhpActions($operation->getGcId())
                ->setRenderer(ShaResponse::CONST_RENDERER_NOTHING);
            } else {
                /** @type ShaForm $form */
                $form = $objA->defaultEditRender();
                $form
                ->setDaoClass($class)
                ->setSaveDao(true)
                ->addField()->setRenderer(ShaFormField::RENDER_TYPE_SUBMIT)->setValue(ShaContext::t("Save"))->end()
                ->save();
                $response
                ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
                ->setContent($form->render())
                ->getPopin()
                ->setColor("blue")
                ->setTitle(ShaContext::t("Add"))
                ;
                
            }
            
        } else {
            
            $relation = $this->_operationList->getRelation();
            $relation->setCursor(1);
            
            if ($relation->getType() == "n:n"){
                
                $operationRefresh = clone $this->_operationList;
                $operationRefresh
                ->setBuidBorder(false)
                ->save()
                ;
                
                $operationList = new ShaOperationList();
                $operationList
                ->setDaoClass($this->_operationList->getDaoClass())
                ->setRelation($relation)
                ->setRefreshActions($this->_operationList->getRefreshActions())
                ->addRefreshAction($operationRefresh)
                ->save()
                ;
                
                $content = $operationList->run();
                
            } else if ($relation->getType() == "1>n") {
                
                $class = $relation->getClassB()->getClass();
                $instance = new $class();
                
                $commandKeys = $relation->getLinkAToB()->getCommonKeys();
                $commandKeys = ShaUtilsArray::createFromValues($commandKeys);
                ShaUtilsArray::fillCommonKeys($commandKeys, $relation->getClassA()->getPrimaryKeysValues());
                $instance->setDatas($commandKeys);
                $instance->save();
                $form = $instance->defaultEditRender();
                $content = $form->render();
            }
            
            $response = new ShaResponse();
            $response
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($content)
            ->getPopin()
            ->setColor("blue")
            ->setTitle(ShaContext::t("Add"))
            ;
            
        }
        
        /** @type ShaOperation $actions */
        foreach ($this->getRefreshActions() as $actions){
            $response->addPhpActions($actions->getGcId());
        }
        
        $response->render();
        
    }
    
}


?>