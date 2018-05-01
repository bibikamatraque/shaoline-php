<?php

class ShaOperationMappingList extends ShaOperation {

    const CONST_OPERATION_LIB = "OPERATION_MAPPING_LIST";

    public function getShaOperationLib(){
        return self::CONST_OPERATION_LIB;
    }

    public function render(){
        return "";
    }

    /**
     * Return array with typical 'delete' button configuration
     *
     * @param Object $instance  Class name
     * @param ShaOperation $refererShaOperation Configuration
     *
     * @return ShaOperationMappingList
     */
    public static function get($instance, $refererShaOperation) {
        return null;
    }

    public function run(){

        $relation = $this->getRelation();

        $objA = null;
        if ($relation->getLinkAToB() == "n:n") {

            /** @var ShaCmo $objB */
            $objB = new ReflectionClass($relation->getClassB());
           /* foreach ($configuration['primary'] as $key => $value) {
                $objB->setValue($key, $value);
            }*/
            foreach ($relation->getPrimaryKeyValueOfClassA() as $key => $value) {
                $objB->setValue($key, $value);
            }
            $objB->save();

        } else if ($relation->getLinkAToB() == "1:n") {

            /** @var ShaCmo $objB */
            $objB = new ReflectionClass($relation->getClassB());
           /* foreach ($configuration['primary'] as $key => $value) {
                $objB->setValue($key, $value);
            }*/
            foreach ($relation->getPrimaryKeyValueOfClassA() as $key => $value) {
                $objB->setValue($key, $value);
            }
            $objB->save();

        } else {

            /** @var ShaCmo $objA */
            $objA =  new ReflectionClass($relation->getClassA());
            if (!$objA->load($relation->getPrimaryKeyValueOfClassA())) {
                die("object reference not found");
            }
            /*foreach ($configuration['primary'] as $key => $value) {
                $objA->setValue($key, $value);
            }*/
            $objA->save();

        }

    }

}


?>