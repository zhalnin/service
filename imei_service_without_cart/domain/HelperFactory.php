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
    /**
     * $factory будет иметь методы:
     * - getMapper()
     * - getDomainObjectFactory()
     * - getCollection()
     * - getSelectionFactory()
     * - getUpdateFactory()
     * - getIdentityObject()
     * @param $type - имя класса
     * @return \imei_service\mapper\DomainObjectAssembler с классом (NewsPersistenceFactory к примеру)
     * будет иметь методы:
     * - конструктор - в нем сохраняем ссылку на класс ($this->factory = NewsPersistenceFactory) и создаем подключение к БД
     * - getStatement()
     * - findOne()
     * - find()
     * - insert()
     */
    static  function getFinder( $type ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type ); // получаем PersistenceFactory по имени класса \imei_service\domain\News - NewsPersistenceFactory и т.д.
        return new \imei_service\mapper\DomainObjectAssembler( $factory ); // создаем экземпляр DomainObjectAssembler для работы с БД нужного класса
    }

    static function getCollection( $type, array $array ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type );
        return $factory->getCollection( $array );
    }

    /**
     * Из imei_service\domain\DomainObject принимаем имя класса и
     * возвращаем итератор с условными операторами
     * $factory будет иметь методы:
     * - getMapper()
     * - getDomainObjectFactory()
     * - getCollection()
     * - getSelectionFactory()
     * - getUpdateFactory()
     * - getIdentityObject() - нам нужен ОН
     * @param $type - класс
     * @return \imei_service\mapper\ContactsIdentityObject|\imei_service\mapper\GuestbookIdentityObject|\imei_service\mapper\NewsIdentityObject - возвращаем
     * к примеру: new NewsIdentityObject() - т.е. экземпляр класса, в зависимости от имени класса в $type
     * будет иметь методы:
     * - getObjectFields()
     * - field()
     * - isVoid()
     * - enforceField()
     * - add()
     * - eq()
     * - lt()
     * - gt()
     * - order()
     * - operator()
     * - getComps()
     * - __toString()
     */
    static function getIdentityObject( $type ) {
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( $type ); // получаем PersistenceFactory по имени класса \imei_service\domain\News - NewsPersistenceFactory и т.д.
        return $factory->getIdentityObject(); // возвращаем объект
    }
}
?>