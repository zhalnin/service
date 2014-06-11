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
            case "imei_service\\domain\\Udid":
                return new UdidPersistenceFactory();
                break;
            case "imei_service\\domain\\CarrierCheck":
                return new CarrierCheckPersistenceFactory();
                break;
            case "imei_service\\domain\\FastCheck":
                return new FastCheckPersistenceFactory();
                break;
            case "imei_service\\domain\\BlacklistCheck":
                return new BlacklistCheckPersistenceFactory();
                break;
            case "imei_service\\domain\\Faq":
                return new FaqPersistenceFactory();
                break;
        }
    }
}


class FaqPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new FaqMapper();
    }

    function getDomainObjectFactory() {
        return new FaqObjectFactory();
    }

    function getCollection( array $array ) {
        return new FaqCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new FaqSelectionFactory();
    }

    function getUpdateFactory() {
        return new FaqUpdateFactory();
    }

    function getIdentityObject() {
        return new FaqIdentityObject();
    }
}

class BlacklistCheckPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new BlacklistCheckMapper();
    }

    function getDomainObjectFactory() {
        return new BlacklistCheckObjectFactory();
    }

    function getCollection( array $array ) {
        return new BlacklistCheckCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new BlacklistCheckSelectionFactory();
    }

    function getUpdateFactory() {
        return new BlacklistCheckUpdateFactory();
    }

    function getIdentityObject() {
        return new BlacklistCheckIdentityObject();
    }
}

class FastCheckPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new FastCheckMapper();
    }

    function getDomainObjectFactory() {
        return new FastCheckObjectFactory();
    }

    function getCollection( array $array ) {
        return new FastCheckCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new FastCheckSelectionFactory();
    }

    function getUpdateFactory() {
        return new FastCheckUpdateFactory();
    }

    function getIdentityObject() {
        return new FastCheckIdentityObject();
    }
}

class CarrierCheckPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new CarrierCheckMapper();
    }

    function getDomainObjectFactory() {
        return new CarrierCheckObjectFactory();
    }

    function getCollection( array $array ) {
        return new CarrierCheckCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new CarrierCheckSelectionFactory();
    }

    function getUpdateFactory() {
        return new CarrierCheckUpdateFactory();
    }

    function getIdentityObject() {
        return new CarrierCheckIdentityObject();
    }
}


class UdidPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new UdidMapper();
    }

    function getDomainObjectFactory() {
        return new UdidObjectFactory();
    }

    function getCollection( array $array ) {
        return new UdidCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new UdidSelectionFactory();
    }

    function getUpdateFactory() {
        return new UdidUpdateFactory();
    }

    function getIdentityObject() {
        return new UdidIdentityObject();
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