<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:01
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Collections.php" );

/**
 * Class Collection
 * @package imei_service\mapper
 * Главное назначение это имплементация класса Iterator
 * в цикле foreach во вьюшках
 */
abstract class Collection {
    protected $dofact;
    protected $total = 0;
    protected $raw = array();

    private $result;
    private $pointer = 0;
    private $objects = array();

    /**
     * Из PersistenceFactory->getCollection получаем результирующий массив запроса и
     * определенного домена ...ObjectFactory (NewsObjectFactory)
     * @param array $raw
     * @param DomainObjectFactory $dofact
     */
    function __construct( array $raw=null, DomainObjectFactory $dofact ) {
//        echo "<tt><pre> Collection raw - ".print_r($dofact,true)."</pre></tt>";
        if( ! is_null( $raw ) && ! is_null( $dofact ) ) { // если есть результат и есть DomainObjectFactory
            $this->raw = $raw; // сохраняем результат в переменную raw
            $this->total = count( $raw ); // а количество результирующих наборов в переменную total
        }
        $this->dofact = $dofact; // DomainObjectFactory сохраняем в переменную dofact
//        echo "<tt><pre> Collection raw - ".print_r($this->total,true)."</pre></tt>";
    }

    function add( \imei_service\domain\DomainObject $object ) {
        $class = $this->targetClass();
        if( ! ( $object instanceof $class ) ) {
            throw new \imei_service\base\AppException( "This is a {$class} collection" );
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    abstract function targetClass();

    protected function notifyAccess() {

    }

    private function getRow( $num ) {
//        echo "<tt><pre> getRow num - ".print_r($num,true)."</pre></tt>";
        $this->notifyAccess();
        if( $num >= $this->total || $num < 0 ) { // если указатель больше или равен количеству результирующего набора после SELECT или меньше 0
            return null;
        }
        if( isset( $this->objects[$num] ) ) { // если массив $objects содержит объект \imei_service\domain\News
//            echo "<tt><pre> Collection objects - ".print_r($this->objects[$num],true)."</pre></tt>";
            return $this->objects[$num]; // возвращаем его
        }
        if( isset( $this->raw[$num] ) ) { // если результирующий набор(массив данных после SELECT) с индексом $num имеет место ( если не имеет, то итерация просто закончена )
//            echo "<tt><pre> Collection raw - ".print_r($this->raw[$num],true)."</pre></tt>";
            $this->objects[$num] = $this->dofact->createObject( $this->raw[$num] ); // то добавляем в массив $objects объект
            return $this->objects[$num];
        }
    }

    /**
     *  - 1 -
     * Ставим итератор на позицию 0
     */
    public function rewind() {
        $this->pointer = 0;
    }

    /**
     * - 3 -
     * Возвращаем текущий элемент
     * @return null
     */
    public function current() {
        return $this->getRow( $this->pointer );
    }

    /**
     * - ? -
     * Возвращает ключ текущего элемента, нужен в .. foreach ( ... as $KEY => $value ) ..
     * @return int
     */
    public function key() {
        return $this->pointer;
    }

    /**
     * - 4 -
     * Переводим указатель на следующий элемент
     * @return null
     */
    public function next() {
        $row = $this->getRow( $this->pointer );
        if( $row ) { $this->pointer++; }
        return $row;
    }

    /**
     * - 2 -
     * Проверяем, чтобы текущий элемент не был NULL
     * @return bool
     */
    public function valid() {
        return ( ! is_null( $this->current() ) );
    }
}
?>