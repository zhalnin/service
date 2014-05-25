<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/01/14
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/domain/Collections.php" );

abstract class Collection {
    protected $mapper;
    protected $dofact;
    protected $total = 0;
    protected $raw = array();

    private $result;
    private $pointer = 0;
    private $objects = array();

    /**
     * Конструктор
     * @param array $raw - это объект со свойствами и методами, к примеру: SpaceMapper
     * @param DomainObjectFactory $dofact - createObject()
     */
//    function __construct( array $raw=null, Mapper $mapper=null ) {
    function __construct( array $raw=null, \woo\mapper\DomainObjectFactory $dofact ) {
//        echo "<tt><pre> Collection raw - ".print_r($raw,true)."</pre></tt>";
//        echo "<tt><pre> Collection dofact - ".print_r($dofact,true)."</pre></tt>";
        if( ! is_null( $raw ) && ! is_null( $dofact ) ) {
            // Весь результат выборки (select)
            $this->raw = $raw;
            // Количество полей из выборки (select)
            $this->total = count( $raw );
//            echo "<tt><pre> raw[] - ".print_r($raw,true)."</pre></tt>";
        }
        // Экземпляр класса, к примеру: woo/mapper/SpaceMapper
        $this->dofact = $dofact;
//        echo "<tt><pre> total - ".print_r($this->total,true)."</pre></tt>";
//                echo "<tt><pre> objects[] - ".print_r($this->objects,true)."</pre></tt>";
    }

    /**
     * Вызываем, к примеру: из метода woo/domain/Venue->addSpace()
     * @param \woo\domain\DomainObject $object - woo/domain/Space
     * @throws \Exception
     */
    function add( \woo\domain\DomainObject $object ) {
//        echo "<tt><pre> Collection - add() -  ".print_r($object,true)."</pre></tt>";
//        echo "<tt><pre> Collection - targetClass -  ".print_r($this->targetClass(),true)."</pre></tt>";
        $class = $this->targetClass();
        if( ! ( $object instanceof $class ) ) {
            throw new \Exception("This collection - {$class}" );
        }

        $this->notifyAccess();
        // Добавляем к массиву objects с ключом [количество полей в выборке] = woo/domain/Space
        $this->objects[$this->total] = $object;
//        echo "<tt><pre> Collection -  ".print_r($this->objects,true)."</pre></tt>";
        // К количеству полей добавляем 1
//        echo "<tt><pre> Collection - objects[] ".print_r($this->objects,true)."</pre></tt>";
        $this->total++;
    }

    abstract function targetClass();

    protected function notifyAccess() {
        // Empty for a while
    }

    private function getRow( $num ) {
        $this->notifyAccess();
//        echo "<tt><pre> getRow() - ".print_r($num,true)."</pre></tt>";
        // Если $num - перебираемые индексы
        // больше или равно $this->total - общее количество элементов(из выборки)
        if( $num >= $this->total || $num < 0 ) {
            return null;
        }

        // Если присутствует объект, то возвращаем его
        if( isset( $this->objects[$num] ) ) {
//            echo "<tt><pre> getRow this->objects - ".print_r($this->objects[$num],true)."</pre></tt>";
            return $this->objects[$num];
        }

        // Если есть набор данных, но нет объекта,
        // то создаем объект из набора данных
        if( isset( $this->raw[$num] ) ) {
//            echo "<tt><pre> this->raw[num] -  ".print_r($this->raw[$num],true)."</pre></tt>";
            $this->objects[$num] = $this->dofact->createObject( $this->raw[$num] );
//            echo "<tt><pre> this->objects[num] -  ".print_r($this->objects[$num],true)."</pre></tt>";
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