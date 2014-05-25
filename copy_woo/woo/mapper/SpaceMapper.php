<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31/01/14
 * Time: 12:54
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/base/Exceptions.php" );
require_once( "woo/mapper/Mapper.php" );
require_once( "woo/mapper/VenueMapper.php" );
require_once( "woo/mapper/Collections.php" );
require_once( "woo/domain.php" );
require_once( "woo/mapper/PersistenceFactory.php" );

class SpaceMapper extends Mapper implements \woo\domain\SpaceFinder {
//class SpaceMapper extends Mapper {
    function __construct() {
        parent::__construct();
        $this->selectAllStmt = self::$PDO->prepare( "SELECT * FROM space" );
        $this->selectStmt = self::$PDO->prepare( "SELECT * FROM space WHERE id=?" );
        $this->updateStmt = self::$PDO->prepare( "UPDATE space SET name=?, id=? WHERE id=?" );
        $this->insertStmt = self::$PDO->prepare( "INSERT INTO space (name, venue) VALUES( ?, ?)" );
        $this->findByVenueStmt = self::$PDO->prepare( "SELECT * FROM space WHERE venue=?" );
    }

//    function getCollection( array $raw ) {
//        return new SpaceCollection( $raw, $this );
//    }

//    protected function doCreateObject( array $array ) {
//        $obj = new \woo\domain\Space( $array['id'] );
//        $obj->setName( $array['name'] );
//        $ven_mapper = new VenueMapper();
//        $venue = $ven_mapper->find( $array['venue'] );
//        $obj->setVenue( $venue );
//
//        $event_mapper = new EventMapper();
//        $event_collection = $event_mapper->findBySpaceId( $array['id'] );
//        $obj->setEvents( $event_collection );
//        return $obj;
//    }

    protected function targetClass() {
        return "woo\\domain\\Space";
    }

    protected function doInsert( \woo\domain\DomainObject $object ) {
        $venue = $object->getVenue();
//        echo "<tt><pre> doInsert() -  ".print_r($object->getVenue(),true)."</pre></tt>";
        if( ! $venue ) {
            throw new \woo\base\AppException( "cannot save without venue" );
        }
        $values = array( $object->getName(), $venue->getId() );
//        echo "<tt><pre> values -  ".print_r($values,true)."</pre></tt>";
        $this->insertStmt->execute( $values );
        $id = self::$PDO->lastInsertId();
//        echo "<tt><pre> id -  ".print_r($id,true)."</pre></tt>";
        $object->setId( $id );
    }

    function update( \woo\domain\DomainObject $object ) {
        $values = array( $object->getName(), $object->getId(), $object->getId() );
        $this->updateStmt->execute( $values );
    }

    function selectStmt() {
        return $this->selectStmt;
    }

    function selectAllStmt() {
        return $this->selectAllStmt;
    }

    /**
     * Для итератора
     * Поиск Space по id в Venue
     * @param $vid
     * @return SpaceCollection
     */
    function findByVenue( $vid ) {
//        echo $vid;
        // Выполняем команду по поиску Space в Venue по id
        $this->findByVenueStmt->execute( array( $vid ) );
//        echo "<tt><pre> findByVenue - ".print_r($this->findByVenueStmt->fetchAll(),true)."</pre></tt>";
//        echo "<tt><pre>".print_r($this->getFactory(),true)."</pre></tt>";
//        echo "<tt><pre>".print_r($this->findByVenueStmt->fetchAll(),true)."</pre></tt>";
        // Возвращаем коллекцию
        // Вызываем из woo\mapper\Collection
        // Передаем:
        // 1. $this->findByVenueStmt->fetchAll() - получаем результат запроса
        // 2. $this->getFactory()->getDomainObjectFactory() -
        // получаем SpacePersistenceFactory->getDomainObjectFactory() -
        // return new SpaceObjectFactory()(т.е. createObject());
        // и в цикле foreach() можно использовать этот итератор
        return new SpaceCollection(
            $this->findByVenueStmt->fetchAll(),
            $this->getFactory()->getDomainObjectFactory()
        );
    }
}

?>