<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:00
 */

namespace dmn\domain;


class ObjectWatcher {

    private $all = array();    // Глобальный массив, содержит: [Имя_класса.id_Экзмепляра] = Экзепляр_класса
    private $dirty = array();
    private $new = array();    // Массив для экземпляров, которые создаются впервые(не имеют своих id)
    private $delete = array();
    private static $instance;

    private function __construct(){}

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }


// IDENTITY MAP из \dmn\mapper\Mapper - find() и createObject()

    /**
     * Глобальный ключ - возвращает имя класса и его id
     * @param DomainObject $obj
     * @return string, к примеру: Venue.1
     */
    private function globalKey( DomainObject $obj ) {
        $key = get_class( $obj ) . "." . $obj->getId();
        return $key;
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

    /**
     * Добавляем в массив $all ключ(после метода globalKey)
     * и значение(экземпляр класса DomainObject)
     * @param DomainObject $obj
     * к примеру all['Venue.1'] = \\woo\\domain\\Venue;
     */
    static function add( DomainObject $obj ) {
//        echo "<tt><pre> globalKey ".print_r($obj, true)."</pre></tt>";
        $inst = self::instance();
        $inst->all[$inst->globalKey( $obj )] = $obj;
    }





// UNIT OF WORK для Insert, Update, Delete
    /**
     * Вызывается из метода markNew(),
     * который вызывается из конструктора DomainObject, если это обращение без id
     * - addNew(\woo\domain\AddVenue)
     * @param DomainObject $obj
     */
    static function addNew( DomainObject $obj ) {
//        echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        $inst = self::instance();
        $inst->new[] = $obj;
    }

    static function addDelete( DomainObject $obj ) {
        $inst = self::instance();
        $inst->delete[ $inst->globalKey( $obj )] = $obj;
    }

    /**
     * Вызывается, к примеру из: DomainObject - woo/domain/Space
     * @param DomainObject $obj
     */
    static function addDirty( DomainObject $obj ) {
//        echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        $inst = self::instance();
        if( ! in_array( $obj, $inst->new, true ) ) {
            $inst->dirty[ $inst->globalKey( $obj )] = $obj;
        }
    }

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
            function( $a ) use ( $obj ) { return !( $a === $obj ); } );
    }

    /**
     * Метод исполнитель
     */
    function performOperations() {
//        echo "<tt><pre>".print_r($this->new, true)."</pre></tt>";
        foreach ( $this->dirty as $key => $obj ) {
            $obj->finder()->insert( $obj );
        }
        // проходим по массиву в поиске объекта для добавления в БД - это индекс 0 и он имеет вложенный массив с вставляемыми данными: [id]=>2 и т.д.
        foreach ( $this->new as $key => $obj ) {
//            echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
            $obj->finder()->insert( $obj );
        }
        $this->dirty = array();
        $this->new = array();
    }

}
?>