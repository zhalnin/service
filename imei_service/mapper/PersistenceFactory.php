<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 22:03
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );
//require_once( "imei_service/mapper/Collections.php" );
//require_once( "imei_service/mapper/DomainObjectFactory.php" );
//require_once( "imei_service/mapper/NewsIdentityObject.php" );
//require_once( "imei_service/mapper/NewsSelectionFactory.php" );

require_once( "imei_service/mapper.php" );

abstract class PersistenceFactory {

    abstract function getMapper();
    abstract function getDomainObjectFactory();
    abstract function getCollection( array $array );
    abstract function getSelectionFactory();
    abstract function getUpdateFactory();

    /**
     * Фабрика для получения нужного объекта
     * @param $target_class - имя класса из DomainObject -- HelperFactory
     * @return ContactsPersistenceFactory|GuestbookPersistenceFactory|NewsPersistenceFactory
     */
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
            case "imei_service\\domain\\Unlock":
                return new UnlockPersistenceFactory();
                break;
            case "imei_service\\domain\\UnlockDetails":
                return new UnlockDetailsPersistenceFactory();
                break;
        }
    }
}


class UnlockPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new UnlockMapper();
    }

    function getDomainObjectFactory() {
        return new UnlockObjectFactory();
    }

    function getCollection( array $array ) {
        return new UnlockCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new UnlockSelectionFactory();
    }

    function getUpdateFactory() {
        return new UnlockUpdateFactory();
    }

    function getIdentityObject() {
        return new UnlockIdentityObject();
    }
}


class UnlockDetailsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new UnlockDetailsMapper();
    }

    function getDomainObjectFactory() {
        return new UnlockDetailsObjectFactory();
    }

    function getCollection( array $array ) {
        return new UnlockDetailsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new UnlockDetailsSelectionFactory();
    }

    function getUpdateFactory() {
        return new UnlockDetailsUpdateFactory();
    }

    function getIdentityObject() {
        return new UnlockDetailsIdentityObject();
    }
}


/**
 * Class NewsPersistenceFactory
 * @package imei_service\mapper
 * Класс для управления новостями
 */
class NewsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new NewsMapper();
    }

    function getDomainObjectFactory() {
        return new NewsObjectFactory();
    }

    /**
     * Получаем из DomainObjectAssembler результирующий массив запроса
     * @param array $array - результирующий набор БД из DomainObjectAssembler->find() - т.е. $raw
     * будет иметь методы:
     * - add()
     * - targetClass()
     * - notifyAccess()
     * - getRow()
     * - rewind()
     * - current()
     * - key()
     * - next()
     * - valid()
     * @return NewsCollection - $this->raw; $this->total; $this->dofact
     */
    function getCollection( array $array ) {
        return new NewsCollection( $array, $this->getDomainObjectFactory() ); // возвращаем экземпляр Collection, будет содержать метод createObject
    }

    /**
     * Получаем из DomainObjectAssembler
     * @return NewsSelectionFactory
     */
    function getSelectionFactory() {
        return new NewsSelectionFactory();
    }

    function getUpdateFactory() {
        return new NewsUpdateFactory();
    }

    /**
     * Получаем из \imei_service\domain\News
     * @return NewsIdentityObject
     */
    function getIdentityObject() {
        return new NewsIdentityObject();
    }
}


class GuestbookPersistenceFactory extends PersistenceFactory {
    function getMapper() {
        return new GuestbookMapper();
    }

    function getDomainObjectFactory() {
        return new GuestbookObjectFactory();
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

    function getPaginationFactory( $tableName,
                                    $where,
                                    $order,
                                    $pageNumber,
                                    $pageLink,
                                    $parameters,
                                    $page ) {
        return new GuestbookPaginationFactory(  $tableName,
                                                $where,
                                                $order,
                                                $pageNumber,
                                                $pageLink,
                                                $parameters,
                                                $page);
    }
}


class ContactsPersistenceFactory extends PersistenceFactory {
    function getMapper() {
        return new ContactsMapper();
    }

    function getDomainObjectFactory() {
        return new ContactsObjectFactory();
    }

    function getCollection( array $array ) {
        return new ContactsCollection( $array, $this->getDomainObjectFactory() );
    }

    /**
     *  Получаем из DomainObjectAssembler
     * @return ContactsSelectionFactory
     */
    function getSelectionFactory() {
        return new ContactsSelectionFactory();
    }

    function getUpdateFactory() {
        return new ContactsUpdateFactory();
    }

    /**
     * Получаем из \imei_service\domain\Contacts
     * @return ContactsIdentityObject
     */
    function getIdentityObject() {
        return new ContactsIdentityObject();
    }
}

?>