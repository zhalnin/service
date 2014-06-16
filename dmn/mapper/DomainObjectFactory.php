<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:12
 */

namespace dmn\mapper;


require_once( 'dmn/domain/FaqPosition.php' );
require_once( 'dmn/domain/FaqParagraphImage.php' );


abstract class DomainObjectFactory {
    abstract function createObject( array $array );

    protected function getFromMap( $class, $id ) {
        return \dmn\domain\ObjectWatcher::exists( $class, $id );
    }

    protected function addToMap( \dmn\domain\DomainObject $obj ) {
        \dmn\domain\ObjectWatcher::add( $obj );
    }
}

?>