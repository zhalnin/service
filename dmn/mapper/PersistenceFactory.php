<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/07/14
 * Time: 22:14
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper.php" );

abstract class PersistenceFactory {

    abstract function getMapper();
    abstract function getDomainObjectFactory();
    abstract function getCollection( array $array );
    abstract function getSelectionFactory();
    abstract function getUpdateFactory();
//    abstract function getDeleteFactory();
    abstract function getUpDownFactory();

    static function getFactory( $target_class ) {
        switch( $target_class ) {
            case "dmn\\domain\\News":
                return new NewsPersistenceFactory();
                break;
        }
    }
}

class NewsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new NewsMapper();
    }

    function getDomainObjectFactory() {
        return new NewsObjectFactory();
    }

    function getCollection( array $array ) {
        return new NewsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new NewsSelectionFactory();
    }

    function getUpdateFactory() {
        return new NewsUpdateFactory();
    }

    function getIdentityObject() {
        return new NewsIdentityObject();
    }

    function getDeleteFactory() {
        return new NewsDeleteFactory();
    }

    function getUpDownFactory() {
        return new NewsUpDownFactory();
    }
}