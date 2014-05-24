<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:01
 */

namespace imei_service\mapper;

require_once( "imei_service/domain/Collections.php" );

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
        if( $num >= $this->total || $num < 0 ) {
            return null;
        }
        if( isset( $this->objects[$num] ) ) {
//            echo "<tt><pre> Collection raw - ".print_r($this->objects[$num],true)."</pre></tt>";
            return $this->objects[$num];
        }
        if( isset( $this->raw[$num] ) ) {
            $this->objects[$num] = $this->dofact->createObject( $this->raw[$num] );
            return $this->objects[$num];
        }
    }

    public function rewind() {
        $this->pointer = 0;
    }

    public function current() {
        return $this->getRow( $this->pointer );
    }

    public function key() {
        return $this->pointer;
    }

    public function next() {
        $row = $this->getRow( $this->pointer );
        if( $row ) { $this->pointer++; }
        return $row;
    }

    public function valid() {
        return ( ! is_null( $this->current() ) );
    }
}
?>