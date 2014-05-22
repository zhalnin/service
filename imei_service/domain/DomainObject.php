<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:39
 */

namespace imei_service\domain;

require_once( "imei_service/domain/ObjectWatcher.php" );

abstract class DomainObject {
    private $id = -1;

    /**
     * Конструктор вызывается из дочернего класса
     * @param null $id
     */
    function __construct( $id = null ){
        if( is_null( $id ) ) {
            $this->markNew(); // отмечаем как новое обращение, т.е. не ( SELECT .... WHERE id = ... )
        } else {
            $this->id = $id; // если не новое обращение, то сохраняем id
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