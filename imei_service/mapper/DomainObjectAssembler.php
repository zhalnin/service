<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 22:08
 */

namespace imei_service\mapper;


class DomainObjectAssembler {
    protected static $PDO;

    /**
     * Получаем объект PersistenceFactory
     * Создаем подключение к БД
     * @param PersistenceFactory $factory
     */
    function __construct( PersistenceFactory $factory ) {
//        echo "<tt><pre>".print_r($factory, true)."</pre></tt>";

        $this->factory = $factory; // сохраняем в переменную
        if( ! isset( self::$PDO ) ) { // если еще нет
            $dsn = \imei_service\base\DBRegistry::getDB(); // то сохраняем дескриптор БД
            if( is_null( $dsn ) ) { // если неудача
                throw new \imei_service\base\AppException( "No DSN" ); // вызываем исключение
            }
            self::$PDO = $dsn; // собственно дескриптор БД
        }
    }

    /**
     * Проверяет наличие запроса в кэше,
     * к примеру: SELECT id FROM system_news
     * если нет, то возвращаем его уже после prepare,
     * если есть, то возвращаем из кэша
     * @param $str
     * @return mixed
     */
    function getStatement( $str ) {
        if( ! isset( $this->statements[$str] ) ) {
            $this->statements[$str] = self::$PDO->prepare( $str );
        }
        return $this->statements[$str];
    }

    /**
     * Возвращаем коллекцию SELECT по одному полю
     * @param IdentityObject $idobj
     * @return mixed
     */
    function findOne( IdentityObject $idobj ) {
        $collection = $this->find( $idobj );
//        echo "<tt><pre>".print_r($collection->current(), true)."</pre></tt>";
        return $collection->current();
    }

    /**
     * Принимает объект IdentityObject нужного класса,
     * выполняет Select и возвращает коллекцию найденных полей
     * @param IdentityObject $idobj
     * @return mixed
     */
    function find( IdentityObject $idobj ) {
        $selfact = $this->factory->getSelectionFactory(); // из PersistenceFactory вызываем Select
        list( $selection, $values ) = $selfact->newSelection( $idobj ); // из ...SelectionFactory получаем SELECT, если есть с WHERE и массив со значениями
//        echo "<tt><pre>".print_r($selection, true)."</pre></tt>";
        $stmt = $this->getStatement( $selection ); // проверяем наличие такого запроса в кэше, если не было еще - сохраняем, а возвращается на уже с дескриптором соединения и после prepare
        $stmt->execute( $values ); // выполняем запрос
        $raw = $stmt->fetchAll(); // получаем результирующий массив
//        echo "<tt><pre>".print_r($selection, true)."</pre></tt>";

        return $this->factory->getCollection( $raw ); // из PersistenceFactory возвращаем экземпляр ...Collection
    }

    function insert( \imei_service\domain\DomainObject $obj ) {
//        echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        $upfact = $this->factory->getUpdateFactory();
        list( $update, $values ) = $upfact->newUpdate( $obj );
//        echo "<tt><pre>".print_r($update, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $stmt = $this->getStatement( $update );
        $stmt->execute( $values );
        if( $obj->getId() < 0 ) {
            $obj->setId( self::$PDO->lastInsertId() );
        }
        $obj->markClean();
    }

    function findPagination( $tableName,
                             IdentityObject $where,
                            $order,
                            $pageNumber,
                            $pageLink,
                            $parameters,
                            $page ) {

        $pagfact = $this->factory->getPaginationFactory( $tableName,
                                                        $where,
                                                        $order,
                                                        $pageNumber,
                                                        $pageLink,
                                                        $parameters,
                                                        $page);

//        return $pagfact->getPage();
        return array ( "navigation"=>$pagfact->printPageNav(), "select"=>$this->factory->getCollection( $pagfact->getPage() ) );

    }
}
?>