<?php

class ShaDaoUtils {


    /**
     * Return true if the class got field (language_id)
     *
     * @param string $className Name of class to test
     * @param string $fieldName Name of field to test
     *
     * @return bool
     */
    public static function hasField($className, $fieldName)
    {
        /** @var ShaDao $instance */
        $instance = new $className();
        return $instance->hasField($fieldName);
    }






}