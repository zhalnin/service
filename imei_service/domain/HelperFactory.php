<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:49
 */

namespace imei_service\domain;

require_once( "imei_service/mapper/DomainObjectAssembler.php" );
require_once( "imei_service/mapper/PersistenceFactory.php" );

class HelperFactory {
    static  function getFinder( $type ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type ); // получаем PersistenceFactory по имени класса \imei_service\domain\News
        return new \imei_service\mapper\DomainObjectAssembler( $factory ); // создаем экземпляр DomainObjectAssembler для работы с БД нужного класса
    }

    static function getCollection( $type, array $array ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type );
        return $factory->getCollection( $array );
    }

    /**
     * Из imei_service\domain\DomainObject принимаем имя класса и
     * возвращаем итератор с условными операторами
     * @param $type - класс
     * @return \imei_service\mapper\ContactsIdentityObject|\imei_service\mapper\GuestbookIdentityObject|\imei_service\mapper\NewsIdentityObject
     */
    static function getIdentityObject( $type ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type );
        return $factory->getIdentityObject();
    }
}
?>