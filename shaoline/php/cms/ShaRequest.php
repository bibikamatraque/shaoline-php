<?php

class ShaRequest{

    /**
     * Treate called action (list, edit, add, delete)
     *
     * @param type $ShaUser       Current ShaUser
     * @param type $configuration Action configuration
     * @param bool $deserialize   Deserialize before use
     *
     * @return Reponse
     */
    public static function run($gcId) {

        $ShaOperation = ShaOperation::load($gcId);
        return $ShaOperation->run();

    }


}

?>