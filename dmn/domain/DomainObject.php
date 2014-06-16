<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:07
 */

namespace dmn\domain;



require_once( "dmn/domain/ObjectWatcher.php" );
require_once( "dmn/domain/HelperFactory.php" );

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

    /**
     * Вызываем из дочернего класса
     * В HelperFactory вызываем метод getFactory
     * и должны получить PersistenceFactory (dmn\domain\News - NewsPersistenceFactory и т.д.)
     * и вернется DomainObjectAssembler с PersistenceFactory в качестве параметра
     * @param $type - класс
     * @return \dmn\mapper\DomainObjectAssembler
     */
    static function getFinder( $type ) {
        return HelperFactory::getFinder( $type );
    }
    function finder() {
        return self::getFinder( get_class( $this ) );
    }

    /**
     * Вызываем из дочернего класса
     * В HelperFactory вызываем метод getIdentityObject
     * и должны получить к примеру: new NewsIdentityObject()
     * - т.е. экземпляр класса, в зависимости от имени класса в $type
     * @param $type - имя класса
     * @return \dmn\mapper\ContactsIdentityObject|\dmn\mapper\GuestbookIdentityObject|\dmn\mapper\NewsIdentityObject
     */
    static function getIdentityObject( $type ) {
        return HelperFactory::getIdentityObject( $type );
    }
    function identityObject() {
        return self::getIdentityObject( get_class( $this ) );
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