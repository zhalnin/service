<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/01/14
 * Time: 18:48
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

require_once( "woo/domain/Collections.php" );
require_once( "woo/domain/ObjectWatcher.php" );
require_once( "woo/domain/HelperFactory.php" );

abstract class DomainObject {
    private $id = -1;

    // Конструктор сохраняет номер id в переменной $id
    function __construct( $id=null ) {
        if( is_null( $id ) ) {
            $this->markNew();
        } else {
            $this->id = $id;
        }
    }

    /**
     * Отмечаем как вновь создаваемый,
     * который еще не имеет id
     */
    function markNew() {
        // Вызываем метод, который добавит этот экземпляр в массив new[] (для новых экземпляров)
        ObjectWatcher::addNew( $this );
    }

    function markDeleted() {
        ObjectWatcher::addDelete( $this );
    }

    /**
     * Если данные не заносятся впервые, то добавляем их к массиву dirty[]
     */
    function markDirty() {
        ObjectWatcher::addDirty( $this );
    }

    /**
     * После, к примеру: insert() из Mapper, вставки данных и сохранения в массив all[] ObjectWatcher
     * удаляем этот объект из массива new[] - убираем из ожидающих изменений
     */
    function markClean() {
        ObjectWatcher::addClean( $this );
    }


    function collection() {
        return self::getCollection( get_class( $this ) );
    }

    static function getCollection( $type ) {
        return HelperFactory::getCollection( $type );
        // Delete after debugging
//        return array();
    }

    /**
     * Для доступа к статическому методу getFinder()
     * передаем имя класса
     * @return \woo\mapper\DomainObjectAssembler
     */
    function finder() {
        return self::getFinder( get_class( $this ) );
    }

    /**
     * Для получения
     * @param $type - имя класса
     * @return \woo\mapper\DomainObjectAssembler
     */
    static function getFinder( $type ) {
//        echo "<tt><pre> DO - getFinder() -  ".print_r($type,true)."</pre></tt>";
        return HelperFactory::getFinder( $type );
    }

    function getId() {
        return $this->id;
    }

    function setId( $id ) {
        $this->id = $id;
    }

    function __clone() {
        $this->id = -1;
    }
}

?>