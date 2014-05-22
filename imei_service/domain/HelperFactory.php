<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:49
 */

namespace imei_service\domain;

require_once( "imei_service/mapper/DomainObjectAssembler.php" );

class HelperFactory {
    static  function getFinder( $type ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type );
        return new \imei_service\mapper\DomainObjectAssembler( $factory );
    }

    static function getCollection( $type, array $array ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type );
        return $factory->getCollection( $array );
    }
}
?>