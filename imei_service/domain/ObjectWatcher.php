<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/05/14
 * Time: 18:04
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;


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

    }

    static function addDirty( DomainObject $obj ) {

    }

    static function addClean( DomainObject $obj ) {

    }

}