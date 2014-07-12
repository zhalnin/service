<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 22:08
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

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
//        echo "<tt><pre>".print_r($idobj, true)."</pre></tt>";
        $collection = $this->find( $idobj );
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
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
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
//            echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
            $obj->setId( self::$PDO->lastInsertId() );
        }
        $obj->markClean();
    }

    function delete( IdentityObject $idobj ) {
        $delfact = $this->factory->getDeleteFactory();
        list( $delete, $values ) = $delfact->newDelete( $idobj );
//        echo "<tt><pre>".print_r($delete, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
//        echo "<tt><pre>".print_r( $delfact, true ) ."</pre></tt>";

    }

    /**
     * newDeleteEarly - имеет специфическую конструкцию для поля date:
     * WHERE UNIX_TIMESTAMP()-UNIX_TIMESTAMP(date)
     * @param IdentityObject $idobj
     */
    function deleteEarly( IdentityObject $idobj ) {
//                echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        $delfact = $this->factory->getDeleteFactory();
        list( $delete, $values ) = $delfact->newDeleteEarly( $idobj );
        $stmt = $this->getStatement( $delete );
        $stmt->execute( $values );

//        echo "<tt><pre>".print_r($delete, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
//        echo "<tt><pre>".print_r( $delfact, true ) ."</pre></tt>";
    }

    /**
     * Метод для постраничной навигации
     * возвращает:
     * "navigation" - сама навигация
     * "select" - это содержимое выборки
     * @param $tableName
     * @param IdentityObject $where
     * @param $order
     * @param $pageNumber
     * @param $pageLink
     * @param $parameters
     * @param $page
     * @return array
     */
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
//        echo "<tt><pre>".print_r( $pagfact->getPage(), true )."</pre></tt>";
//        return $pagfact->getPage();
        return array ( "navigation"=>$pagfact->printPageNav(), "select"=>$this->factory->getCollection( $pagfact->getPage() ) );

    }
}
?>