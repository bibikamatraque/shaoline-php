<?php
/**
 * Class Serializable
 *
 * PUT HERE YOUR DESCRIPTION
 * Becareful : _parent must be private to avoid 'too nested' error
 *
 * @author      Bastien DUHOT <bastien.duhot@free.fr>
 * @version     1.0.0
 * @category    ?
 * @namespace   ${NAMESPACE}
 * @licence     Please contact Bastien DUHOT
 */
abstract class ShaSerializable {

    /**
     * Serialize object datas
     *
     * @param ShaOperation $instance
     *
     * @return string
     */
    public static function stringify($instance){
        $data = self::_stringify($instance);
        return serialize($data);
    }

    /**
     * Get all properties an put them into array
     *
     * @param $instance
     */
    private static function _stringify($instance){
        $data = array();
        $data['shaS'] = true;
        $data['shaC'] = get_class($instance);
        $attributes = get_object_vars ($instance);
        foreach ($attributes as $key => $value) {

            if ($value instanceof ShaSerializable){
                $data[$key] = self::_stringify($value);
            } else {
                if (is_array($value)){
                    $data[$key]  = array();
                    foreach($value as $subKey => $subValue){
                        if ($subValue instanceof ShaSerializable){
                            $data[$key][$subKey] = self::_stringify($subValue);
                        } else {
                            $data[$key][$subKey] = $subValue;
                        }
                    }
                } else {
                    $data[$key] = $value;
                }

            }

        }

        return $data;
    }

    /**
     * Unserialize object
     *
     * @param string $code
     *
     * @return ShaOperation
     */
    public static function unstringify($code){
        $data = unserialize($code);
        $result =  self::_unstringify(null, $data);
        return $result;
    }

    /**
     * Get all array entriess and construct instance
     *
     * @param array $data
     *
     * @return ShaOperation
     */
    public static function _unstringify($parent, $data){


        $class = $data['shaC'];
        if (!class_exists($class)){
            die("unknown_action_you_probably_lost_your_session");
        }
        $instance = new $class();

        if ($instance instanceof ShaChildSerializable){
            $instance->setParent($parent);
        }

        unset($data['shaS']);
        unset($data['shaC']);
        foreach ($data as $key => $value) {

            if (is_array($value) && in_array('shaS', $value) && in_array("shaC", $value) && isset($value['shaS']) && isset($value['shaC']) ){
                $instance->$key = self::_unstringify($instance, $value);
            } else {
                if (is_array($value)){
                   // eval ("\$instance->$key = array();");
                    foreach($value as $subKey => $subValue){
                        if (is_array($subValue) && in_array('shaS', $subValue) && in_array("shaC", $subValue) && isset($subValue['shaS']) && isset($subValue['shaC'])){
                           // echo "B : \$instance->".$key."['".$subKey."']<br/>";
                           // $instance->$key[$subKey] = self::_unstringify($instance, $subValue);
                            eval ("\$instance->".$key."['".$subKey."'] = self::_unstringify(\$instance, \$subValue);");
                        } else {
                            //echo "C : \$instance->".$key."['".$subKey."']<br/>";
                           // $instance->$key[$subKey] = $subValue;
                            eval ("\$instance->".$key."['".$subKey."'] = \$subValue;");

                        }
                    }
                } else {
                  // echo "D : $key<br/>";
                    $instance->$key = $value;
                }

            }

        }

        return $instance;

    }



}

?>