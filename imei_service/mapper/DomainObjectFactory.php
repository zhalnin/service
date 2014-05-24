<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 22:30
 */

namespace imei_service\mapper;


abstract class DomainObjectFactory {
    abstract function createObject( array $array );

    protected function getFromMap( $class, $id ) {
        return \imei_service\domain\ObjectWatcher::exists( $class, $id );
    }

    protected function addToMap( \imei_service\domain\DomainObject $obj ) {
        return \imei_service\domain\ObjectWatcher::add( $obj );
    }
}

class NewsObjectFactory extends DomainObjectFactory {
    function createObject( array $array ) {

    }
}