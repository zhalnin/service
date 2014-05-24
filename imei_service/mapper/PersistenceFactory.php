<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 22:03
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/Collections.php" );
require_once( "imei_service/mapper/DomainObjectFactory.php" );
require_once( "imei_service/mapper/NewsIdentityObject.php" );
require_once( "imei_service/mapper/NewsSelectionFactory.php" );

abstract class PersistenceFactory {

    abstract function getMapper();
    abstract function getDomainObjectFactory();
    abstract function getCollection( array $array );
    abstract function getSelectionFactory();
    abstract function getUpdateFactory();

    static function getFactory( $target_class ) {
        switch( $target_class ) {
            case "imei_service\\domain\\News":
                return new NewsPersistenceFactory();
                break;
            case "imei_service\\domain\\Guestbook":
                return new GuestbookPersistenceFactory();
                break;
            case "imei_service\\domain\\Contacts":
                return new ContactsPersistenceFactory();
                break;
        }
    }
}


class NewsPersistenceFactory extends PersistenceFactory {
    function getMapper() {
        return new NewsMapper();
    }

    function getDomainObjectFactory() {
        return new NewsObjectFactory;
    }

    function getCollection( array $array ) {
        return new NewsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() { // из DomainObjectAssembler
        return new NewsSelectionFactory();
    }

    function getUpdateFactory() {
        return new NewsUpdateFactory();
    }

    function getIdentityObject() { // из \imei_service\domain\News
        return new NewsIdentityObject();
    }
}


class GuestbookPersistenceFactory extends PersistenceFactory {
    function getMapper() {
        return new GuestbookMapper();
    }

    function getDomainObjectFactory() {
        return new GuestbookObjectFactory;
    }

    function getCollection( array $array ) {
        return new GuestbookCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new GuestbookSelectionFactory();
    }

    function getUpdateFactory() {
        return new GuestbookUpdateFactory();
    }

    function getIdentityObject() {
        return new GuestbookIdentityObject();
    }
}


class ContactsPersistenceFactory extends PersistenceFactory {
    function getMapper() {
        return new ContactsMapper();
    }

    function getDomainObjectFactory() {
        return new ContactsObjectFactory;
    }

    function getCollection( array $array ) {
        return new ContactsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new ContactsSelectionFactory();
    }

    function getUpdateFactory() {
        return new ContactsUpdateFactory();
    }

    function getIdentityObject() {
        return new ContactsIdentityObject();
    }
}

?>