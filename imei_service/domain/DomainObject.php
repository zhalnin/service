<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:39
 */

namespace imei_service\domain;


abstract class DomainObject {
    private $id = -1;

    function __construct( $id = null ){
        if( is_null( $id ) ) {
//            $this->markNew();
        } else {
            $this->id = $id;
        }
    }

    function getId() {
        return $this->id;
    }

    static function getCollection( $type ) {
        return HelperFactory::getCollection( $type );
    }

    function collection() {
        return self::getCollection( get_class( $this ) );
    }

    function getFinder( $type ) {
        return HelperFactory::getFinder( $type );
    }

    function finder() {
        return self::getFinder( get_class( $this ) );
    }

    function setId( $id ) {
        $this->id = $id;
    }

    function __clone() {
        $this->id = -1;
    }

    function markNew() {
        ObjectWatcher::addNew( $this );
    }

    function markDeleted() {
        ObjectWatcher::addDelete( $this );
    }

    function markDirty() {
        ObjectWatcher::addDirty( $this );
    }

    function markClean() {
        ObjectWatcher::addClean( $this );
    }
}
?>