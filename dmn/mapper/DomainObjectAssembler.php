<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:14
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );



class DomainObjectAssembler {
    protected static $PDO;

    /**
     * Получаем объект PersistenceFactory
     * Создаем подключение к БД
     * @param PersistenceFactory $factory
     */
    function __construct( PersistenceFactory $factory ) {
        $this->factory = $factory; // сохраняем в переменную
        if( ! isset( self::$PDO ) ) { // если еще нет
            $dsn = \dmn\base\DBRegistry::getDB(); // то сохраняем дескриптор БД
            if( is_null( $dsn ) ) { // если неудача
                throw new \dmn\base\AppException( "No DSN" ); // вызываем исключение
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
//        echo "<tt><pre>".print_r($collection, true)."</pre></tt>";
        return $collection->current();
    }

    /**
     * Принимает объект IdentityObject нужного класса,
     * выполняет Select и возвращает коллекцию найденных полей
     * @param IdentityObject $idobj
     * @return mixed
     */
    function find( IdentityObject $idobj ) {
//        echo "<tt><pre>".print_r($idobj, true)."</pre></tt>";
        $selfact = $this->factory->getSelectionFactory(); // из PersistenceFactory вызываем Select
        list( $selection, $values ) = $selfact->newSelection( $idobj ); // из ...SelectionFactory получаем SELECT, если есть с WHERE и массив со значениями
//        echo "<tt><pre>".print_r($selection, true)."</pre></tt>";
        $stmt = $this->getStatement( $selection ); // проверяем наличие такого запроса в кэше, если не было еще - сохраняем, а возвращается на уже с дескриптором соединения и после prepare
        $stmt->execute( $values ); // выполняем запрос
        $raw = $stmt->fetchAll(); // получаем результирующий массив
        return $this->factory->getCollection( $raw ); // из PersistenceFactory возвращаем экземпляр ...Collection
    }

    /**
     * Метод для вставки в БД
     * @param \dmn\domain\DomainObject $obj
     */
    function insert( \dmn\domain\DomainObject $obj ) {
        // получаем мтод для обновления из фабрики
        $upfact = $this->factory->getUpdateFactory();
        // инициализируем переменные значениями UPDATE и VALUES
        list( $update, $values ) = $upfact->newUpdate( $obj );
//        echo "<tt><pre>".print_r($update, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        // формируем запрос
        $stmt = $this->getStatement( $update );
        // выполняем запрос
        $stmt->execute( $values );
        // если id = -1 - то запись новая, значит после вставки в поле БД
        if( $obj->getId() < 0 ) {
            // сохраняем только что вставленное значение
            $obj->setId( self::$PDO->lastInsertId() );
        }
        // очищаем массивы
        $obj->markClean();
    }

    /**
     * Метод для выброки (SELECT)
     * для перемещения позиции
     * 1- выбираем текущую позицию
     * 2- выбираем следующую или предыдущую позиции
     * @param IdentityObject $idobj
     * @param null $orderBy - сортировка ORDER BY
     * @return mixed
     */
    function upDownSelect( IdentityObject $idobj, $orderBy=null ) {
        $upDownfact = $this->factory->getUpDownFactory();
//        $upDownfact->newUpDown( $idobj, '');
        list( $selection, $values ) = $upDownfact->newUpDownSelect( $idobj, $orderBy );
        $stmt = $this->getStatement( $selection );
//        echo "<tt><pre>".print_r($selection, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $stmt->execute( $values );
        $raw = $stmt->fetchAll();
        $collection = $this->factory->getCollection( $raw );
        $stmt->closeCursor();
        return $collection->current();
    }

    /**
     * Метод для обновления (UPDATE)  полей в БД
     * для перемещения позиции
     * @param array $result
     * @param $direct
     */
    function upDownUpdate( array $result, $direct ){
        $upDownfact = $this->factory->getUpDownFactory();
        list( $update, $values ) = $upDownfact->newUpDownUpdate( $result, $direct );
//        echo "<tt><pre>".print_r($update, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $stmt = $this->getStatement( $update );
        $stmt->execute( $values );
    }


    /**
     * Метод для постраничной навигации для Гостевой книги
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

//        return $pagfact->getPage();
        return array ( "navigation"=>$pagfact->printPageNav(), "select"=>$this->factory->getCollection( $pagfact->getPage() ) );

    }
}
?>