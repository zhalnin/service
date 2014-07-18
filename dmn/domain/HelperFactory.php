<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:08
 */

namespace dmn\domain;

require_once( "dmn/mapper/DomainObjectAssembler.php" );
require_once( "dmn/mapper/PersistenceFactory.php" );

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
     * @return \dmn\mapper\DomainObjectAssembler с классом (NewsPersistenceFactory к примеру)
     * будет иметь методы:
     * - конструктор - в нем сохраняем ссылку на класс ($this->factory = NewsPersistenceFactory) и создаем подключение к БД
     * - getStatement()
     * - findOne()
     * - find()
     * - insert()
     */
    static  function getFinder( $type ) {
        $factory = \dmn\mapper\PersistenceFactory::getFactory( $type ); // получаем PersistenceFactory по имени класса \dmn\domain\News - NewsPersistenceFactory и т.д.
        return new \dmn\mapper\DomainObjectAssembler( $factory ); // создаем экземпляр DomainObjectAssembler для работы с БД нужного класса
    }

    static function getCollection( $type, array $array ) {
        $factory = \dmn\mapper\PersistenceFactory::getFactory( $type );
        return $factory->getCollection( $array );
    }

    /**
     * Из dmn\domain\DomainObject принимаем имя класса и
     * возвращаем итератор с условными операторами
     * $factory будет иметь методы:
     * - getMapper()
     * - getDomainObjectFactory()
     * - getCollection()
     * - getSelectionFactory()
     * - getUpdateFactory()
     * - getIdentityObject() - нам нужен ОН
     * @param $type - класс
     * @return \dmn\mapper\ContactsIdentityObject|\dmn\mapper\GuestbookIdentityObject|\dmn\mapper\NewsIdentityObject - возвращаем
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
        $factory = \dmn\mapper\PersistenceFactory::getFactory( $type ); // получаем PersistenceFactory по имени класса \dmn\domain\News - NewsPersistenceFactory и т.д.
        return $factory->getIdentityObject(); // возвращаем объект
    }
}
?>