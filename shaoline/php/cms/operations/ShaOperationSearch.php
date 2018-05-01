<?php

/**
 * class ShaOperationSearch
 *
 *
 * @category   ShaOperation
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaOperationSearch extends ShaOperation
{

    /** @type ShaOperationList $_operationList */
    protected $_operationListGcId;


    const CONST_OPERATION_LIB = "OPERATION_SEARCH";

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
     * @return ShaOperationSearch
     */
    public function setOperationList($operationListGcId)
    {
        $this->_operationListGcId = $operationListGcId;
        return $this;
    }

    /**
     * @return \ShaOperationList
     */
    public function getOperationList()
    {
        return $this->_operationListGcId;
    }

    public function run(){

        /** @type ShaOperationList $operationList */
        $operationList = ShaOperation::load($this->_operationListGcId);
        $form = new ShaForm();
        $form
            ->setDomId(ShaContext::getNextContentId())
            ->setSubmitFunction("ShaOperationSearch::submit")
            ->setDaoAdditionalDatas(
                array("shaOperationListGcId" => $this->_operationListGcId)
            )
        ;

        $defaultForm = $operationList->getFieldForm() ;
        $fields = $defaultForm->getFields();

        /** @type ShaFormField $field */
        foreach ($fields as $field){

            if ($field->canBeUseForSearch()){

                $lib = $field->getLib();
                $lib = (empty($lib)) ?  $field->getDaoField() : $lib;

                $form
                    ->addField($field->getDaoField())
                        ->setDaoField($field->getDaoField())
                        ->setAllowEmpty(true)
                        ->setEditable(true)
                        ->setLibEnable(true)
                        ->setLib($lib)
                        ->setEditable(true)
                        ->setRenderer(ShaFormField::RENDER_TYPE_TEXT)
                ;
            }
        }

        $form->addField()->setRenderer(ShaFormField::RENDER_TYPE_SUBMIT)->setValue(ShaContext::t("Search"));
        $form->save();

        $response = new ShaResponse();
        $response
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setContent($form->render())
            ->getPopin()
                ->setTitle(ShaContext::t("Search"))
                ->setColor("blue")
        ;

        $response->render();
        return;
    }


    /**
     * @param ShaForm $form
     * @param ShaCmo $instance
     */
    public static function submit($form, $instance){

        $datas = $form->getDaoAdditionalDatas();
        /** @type ShaOperationList $operationList */
        $operationList = ShaOperation::load($datas["shaOperationListGcId"]);

        $conditions = array();
        /** @type ShaFormField $field */
        foreach ($form->getFields() as $field){
            $value = $field->getSubmitValue();
            if ($field->getDaoField() != null && !empty($value)){

                $conditions[$field->getDaoField()] = ShaUtilsString::cleanForSQL($value);
            }
        }
        $conditions = ShaUtilsArray::arrayToSqlCondition($conditions, true);
        if ($operationList->getCondition() != ""){
            $conditions = " AND ".$conditions;
        }

        $operationList
            ->setBuidBorder(true)
            ->setAutoGenerateHeaderForPopin(true)
            ->setCondition($operationList->getCondition() . $conditions );

        $response = new ShaResponse();
        $response
            ->setContent($operationList->run())
            ->setRenderer(ShaResponse::CONST_RENDERER_POPIN)
            ->setRenderDomId(ShaContext::getNextContentId())
            ->getPopin()
                ->setColor("blue")
                ->setTitle("Search")
        ;

        $response->render();
        return;
    }
}

?>