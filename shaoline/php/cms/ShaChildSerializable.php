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
abstract class ShaChildSerializable extends ShaSerializable {

    abstract public function setParent($parent);

}

?>