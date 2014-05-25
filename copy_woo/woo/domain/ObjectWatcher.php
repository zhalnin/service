<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31/01/14
 * Time: 17:45
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

require_once( "woo/base/Registry.php" );

class ObjectWatcher {

    private $all = array();    // Глобальный массив, содержит: [Имя_класса.id_Экзмепляра] = Экзепляр_класса
    private $dirty = array();
    private $new = array();    // Массив для экземпляров, которые создаются впервые(не имеют своих id)
    private $delete = array();
    private static $instance;


    private function __construct(){}

    /**
     * Singleton
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }





// IDENTITY MAP из \woo\mapper\Mapper - find() и createObject()

    /**
     * Глобальный ключ - возвращает имя класса и его id
     * @param DomainObject $obj
     * @return string, к примеру: Venue.1
     */
    function globalKey( DomainObject $obj ) {
//        echo "<tt><pre> globalKey ".print_r($obj, true)."</pre></tt>";
        $key = get_class( $obj ).".".$obj->getId();
//        echo "<tt><pre> globalKey ".print_r($key, true)."</pre></tt>";
        return $key;
    }

    /**
     * Добавляем в массив $all ключ(после метода globalKey)
     * и значение(экземпляр класса DomainObject)
     * @param DomainObject $obj
     * к примеру all['Venue.1'] = \\woo\\domain\\Venue;
     */
    static function add( DomainObject $obj ) {
        $inst = self::instance();
        $inst->all[$inst->globalKey( $obj )] = $obj;
    }

    /**
     * Проверяем массив all[] на наличие экземпляра класса(\\woo\\domain\\Venue)
     * по его ключу('Venue.1')
     * @param $classname
     * @param $id
     * @return экзмепляр класса, к примеру: all['Venue.1'],
     * что содержит экземпляр класса \\woo\\domain\\Venue
     * @return null
     */
    static function exists( $classname, $id ) {
        $inst = self::instance();
        $key = "$classname.$id";
        if( isset( $inst->all[$key] ) ) {
            return $inst->all[$key];
        }
        return null;
    }





// UNIT OF WORK для Insert, Update, Delete

    static function addDelete( DomainObject $obj ) {
        $self = self::instance();
        $self->delete[$self->globalKey( $obj )] = $obj;
    }

    /**
     * Вызывается, к примеру из: DomainObject - woo/domain/Space
     * @param DomainObject $obj
     */
    static function addDirty( DomainObject $obj ) {
        $inst = self::instance();
        // Если данные не те, что заносятся в первый раз
        if( ! in_array( $obj, $inst->new, true ) ) {
            // добавляем к массиву параметр, к примеру: Space.74 = woo/domain/Space
            $inst->dirty[$inst->globalKey( $obj )] = $obj;
        }
    }

    /**
     * Вызывается из метода markNew(),
     * который вызывается из конструктора DomainObject - addNew(\woo\domain\AddVenue)
     * @param DomainObject $obj
     */
    static function addNew( DomainObject $obj ) {
        $inst = self::instance();
        // we don't have an id - new[0]='woo\domain\AddVenue
        $inst->new[] = $obj;
    }

    /**
     * Вызывается после вставки данных в БД(insert() - Mapper)
     * @param DomainObject $obj
     */
    static function addClean( DomainObject $obj ) {
        $self = self::instance();
        // Удаляем массив delete[], который содержит вхождение массива all[] с указанными данными
        // delete[all['woo\domain\Venue.1']];
        unset( $self->delete[$self->globalKey( $obj )] );
        // Удаляем массив dirty[], который содержит вхождение массива all[] с указанными данными
        // dirty[all['woo\domain\Venue.1']];
        unset( $self->dirty[$self->globalKey( $obj )] );

        // Если массив new[] имеет искомый объект, то удаляем его из него - отмечаем его как не ожидающего
        // обновления
//        echo "<tt><pre> performOperations() ".print_r($self->new,true)."</pre></tt>";
        $self->new = array_filter( $self->new,
            function($a) use ($obj) { return !( $a === $obj ); } );
//        echo "<tt><pre> performOperations() ".print_r($self->new,true)."</pre></tt>";
    }

    /**
     * Метод исполнитель
     */
    function performOperations() {
//        echo "<tt><pre> performOperations() - ".print_r($this,true)."</pre></tt>";
//        echo "<tt><pre> performOperations() ".print_r($this,true)."</pre></tt>";
        foreach ( $this->dirty as $key=>$obj ) {
//            echo "<tt><pre> UPDATE - ".print_r($this->dirty,true)."</pre></tt>";
            $obj->finder()->insert( $obj );
        }
        // проходим по массиву в поиске объекта для добавления в БД
        foreach ( $this->new as $key=>$obj ) {
//            echo "<tt><pre> INSERT - ".print_r($obj,true)."</pre></tt>";
//            echo "<tt><pre> INSERT - ".print_r($this->new,true)."</pre></tt>";
            $obj->finder()->insert( $obj );
        }
        $this->dirty = array();
        $this->new = array();
    }
}
?>