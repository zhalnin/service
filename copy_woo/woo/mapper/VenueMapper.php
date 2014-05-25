<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/01/14
 * Time: 16:16
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/mapper/Mapper.php" );
require_once( "woo/base/Exceptions.php" );
require_once( "woo/domain/Venue.php" );
require_once( "woo/mapper/Collections.php" );
require_once( "woo/mapper/PersistenceFactory.php" );
require_once( "woo/domain.php" );


class VenueMapper extends Mapper implements \woo\domain\VenueFinder {
//class VenueMapper extends Mapper {

    function __construct() {
        parent::__construct();
        $this->selectAllStmt = self::$PDO->prepare(
                            "SELECT * FROM venue" );
        $this->selectStmt = self::$PDO->prepare(
                            "SELECT * FROM venue WHERE id=?" );
        $this->updateStmt = self::$PDO->prepare(
                            "UPDATE venue SET name=?, id=? WHERE id=?" );
        // Выражение для вставки полей в БД
        $this->insertStmt = self::$PDO->prepare(
                            "INSERT INTO venue ( name ) values( ? )" );
    }

//    function getCollection( array $raw ) {
//        return new SpaceCollection( $raw, $this->getFactory()->getDomainObjectFactory() );
//    }

//    protected function doCreateObject( array $array ) {
//        $obj = new \woo\domain\Venue( $array['id'] );
//        $obj->setName( $array['name'] );
//        $space_mapper = new SpaceMapper();
//        $space_collection = $space_mapper->findByVenue( $array['id'] );
//        $obj->setSpaces( $space_collection );
//        return $obj;
//    }

    protected function targetClass() {
        return "woo\\domain\\Venue";
    }

    /**
     * Вставляем данные в БД
     * @param \woo\domain\DomainObject $object
     */
    protected function doInsert( \woo\domain\DomainObject $object ) {
        print "INSERTING\n";
//        debug_print_backtrace();

        $values = array( $object->getName() );    // Получаем массив из имени
        $this->insertStmt->execute( $values );    // Выполняем запрос по вставке полей в БД
        $id = self::$PDO->lastInsertId();         // Получаем id только что вставленного элемента
        $object->setId( $id );                    // Запоминаем id в DomainObject, для конструктора
    }

    function update( \woo\domain\DomainObject $object ) {
        print "UPDATING\n";
//        debug_print_backtrace();

        $values = array( $object->getName(), $object->getId(), $object->getId() );
        $this->updateStmt->execute( $values );
    }

    function selectStmt() {
        return $this->selectStmt;
    }

    function selectAllStmt() {
        return $this->selectAllStmt;
    }
}


?>