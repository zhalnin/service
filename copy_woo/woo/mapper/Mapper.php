<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/01/14
 * Time: 21:39
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/base/Registry.php" );
require_once( "woo/base/Exceptions.php" );
require_once( "woo/domain/Finders.php" );

abstract class Mapper implements \woo\domain\Finder {
//abstract class Mapper {
    protected static $PDO;

    function __construct() {
////        echo "<tt><pre> PDO ".print_r(self::$PDO,true)."</tt></pre>";
//        if( ! isset( self::$PDO ) ) {
//            $dsn = \woo\base\ApplicationRegistry::getDSN();
//            if( is_null( $dsn ) ) {
//                throw new \woo\base\AppException( "DSN undefined!" );
//            }
////            self::$PDO = new \PDO( $dsn, 'root', 'password',
////            echo "<tt><pre> DSN ".print_r($dsn,true)."</tt></pre>";
//            try {
//                self::$PDO = new \PDO( $dsn, 'root', 'zhalnin5334',
//                    array(
//                        \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
//                        \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',
//                        \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC
//                ) );
//
//            } catch( \PDOException $ex) {
//                echo $ex->getMessage();
//            }
////            self::$PDO = new \PDO( $dsn );
////            self::$PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
//        }

        if( ! isset( self::$PDO ) ) {
            self::$PDO = \woo\base\PDORegistry::getPDO();
        }
        // Получаем из Registry сохраненную ссылку на дескриптор БД, если сохранена,
        // если не сохранена, то там же его и создаем
//        self::$PDO = \woo\base\PDORegistry::getPDO();
    }

    /**
     * Получаем из массива all[] запись
     * @param $id
     * @return null
     */
    private function getFromMap( $id ) {
//        echo $this->targetClass();
        return \woo\domain\ObjectWatcher::exists( $this->targetClass(), $id );
    }

    /**
     * Добавляем к массиву all[] новую запись, к примеру: all['Venue.1'] = \\woo\\domain\\Venue;
     * @param \woo\domain\DomainObject $obj
     */
    private function addToMap( \woo\domain\DomainObject $obj ) {
        \woo\domain\ObjectWatcher::add( $obj );
    }

    /**
     * Поиск по id
     * @param $id
     * @return null
     */
    function find( $id ) {
//        echo "<tt><pre> find() id - ".print_r($id,true)."</pre></tt>";
        // Пробуем найти $id из массива
        $old = $this->getFromMap( $id );
//        echo "<tt><pre>".print_r($id,true)."</pre></tt>";
        // Если есть, то его возвращаем
        if( $old ) {
//            echo "<tt><pre> find() old - ".print_r($old,true)."</pre></tt>";
            return $old; }
//        echo "<tt><pre> find() - ".print_r($old,true)."</pre></tt>";
        // Делаем выборку из MapperVenue->selectStmt()
        $this->selectStmt()->execute( array( $id ) );
        // Получаем результат запроса
        $array = $this->selectStmt()->fetch();
//        echo "<tt><pre> find() selectStmt() - ".print_r($array,true)."</pre></tt>";
        $this->selectStmt()->closeCursor();
        // Если не массив - результат не получен
        if( ! is_array( $array ) ) { return null; }
        // Если в результате нет поля id
        if( ! isset( $array['id'] ) ) { return null; }
        // Создаем объект
        $object = $this->createObject( $array );
//        echo "<tt><pre> find() - ".print_r($object,true)."</pre></tt>";
        $object->markClean();
        // Возвращаем объект - woo\domain\Venue
        return $object;
    }

    function findAll() {
        $this->selectAllStmt()->execute( array() );
//        echo "<tt><pre> findAll() - ".print_r($this->selectAllStmt()->fetchAll(), true)."</pre></tt>";
        return $this->getCollection( $this->selectAllStmt()->fetchAll( \PDO::FETCH_ASSOC ) );
    }

    /**
     * Возвращаем из PersistenceFactory нужный нам экземпляр класса -
     * $this->targetClass() - woo\\domain\\Venue
     * @return EventPersistenceFactory|SpacePersistenceFactory|VenuePersistenceFactory - new VenuePersistenceFactory()
     */
    function getFactory() {
        // Возвращаем - new VenuePersistenceFactory()
        return PersistenceFactory::getFactory( $this->targetClass() );
    }

    /**
     * Создаем объект
     * @param $array - результат выборки Select
     * @return mixed
     */
    function createObject( $array ) {
//        $old = $this->getFromMap( $array['id'] );
//        if( $old ) { return $old; }
//        $obj = $this->doCreateObject( $array );
//        $this->addToMap( $obj );
//        $obj->markClean();
//        return $obj;
        // Получаем дочерний класс из абстрактного класса PersistenceFactory - new VenuePersistenceFactory()
        // вызываем его метод: getDomainObjectFactory() - получаем экземпляр: new VenueObjectFactory();
        $objfactory = $this->getFactory()->getDomainObjectFactory();
        // Возвращаем из DomainObjectFactory, VenueObjectFactory->createObject
        return $objfactory->createObject( $array );
    }

    function getCollection( array $raw ) {
//        echo "<tt><pre> getCollection() - ".print_r($this->getFactory()->getCollection( $raw ), true)."</pre></tt>";
        return $this->getFactory()->getCollection( $raw );
    }

    /**
     * Подготовительные методы для вставки в БД
     * @param \woo\domain\DomainObject $obj
     */
    function insert( \woo\domain\DomainObject $obj ) {
//        echo "<tt><pre> insert() -  ".print_r($obj,true)."</pre></tt>";
        $this->doInsert( $obj );    // Вызываем метод для вставки данных в БД
        $this->addToMap( $obj );    // Добавляем к массиву all[] наблюдателя наш объект(и помним о нем)
        $obj->markClean();          // Удаляем из массива new[] этот объект
    }

//    protected abstract function getCollection( array $raw );
//    abstract function update( \woo\domain\DomainObject $object );
//    protected abstract function doCreateObject( array $array );
    protected abstract function doInsert( \woo\domain\DomainObject $object );
    protected abstract function targetClass();
    protected abstract function selectStmt();
    protected abstract function selectAllStmt();
}

?>