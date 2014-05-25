<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31/01/14
 * Time: 13:55
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;


require_once( "woo/base/Exceptions.php" );
require_once( "woo/mapper/Mapper.php" );
require_once( "woo/mapper/Collections.php" );
require_once( "woo/domain.php" );
require_once( "woo/mapper/PersistenceFactory.php" );



class EventMapper extends Mapper implements \woo\domain\EventFinder {
//class EventMapper extends Mapper {
    function __construct() {
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare( "SELECT * FROM event WHERE id=?" );
        $this->selectAllStmt = self::$PDO->prepare( "SELECT * FROM event");
        $this->selectBySpaceStmt = self::$PDO->prepare( "SELECT * FROM event WHERE space=?" );
        $this->updateStmt = self::$PDO->prepare( "UPDATE event SET start=?, duration=?, name=?, id=? WHERE id=?" );
        $this->insertStmt = self::$PDO->prepare( "INSERT INTO event (start, duration, space, name) VALUES (?, ?, ?, ?)" );
    }

//    function getCollection( array $raw ) {
//        return new EventCollection( $raw->fetchAll(), $this );
//    }


    /**
     * Для итератора
     * @param $s_id
     * @return DeferredEventCollection
     */
    function findBySpaceId( $s_id ) {
        return new DeferredEventCollection(
            $this->getFactory()->getDomainObjectFactory(),
            $this->selectBySpaceStmt,
            array( $s_id ) );
    }

//    protected function doCreateObject( array $array ) {
//        $obj = new \woo\domain\Event( $array['id'] );
//        $obj->setStart( $array['start'] );
//        $obj->setDuration( $array['duration'] );
//        $obj->setName( $array['name'] );
//        $space_mapper = new SpaceMapper();
//        $space = $space_mapper->find( $array['space'] );
//        $obj->setSpace( $space );
//        return $obj;
//    }

    protected function targetClass() {
        return "woo\\domain\\Event";
    }

    protected function doInsert( \woo\domain\DomainObject $object ) {
        $space = $object->getSpace();
        if( ! $space ) {
            throw new \woo\base\AppException( "cannot save without space" );
        }
        $values = array(
            $object->getStart(),
            $object->getDuration(),
            $object->getId(),
            $object->getName()
        );
        $this->insertStmt->execute( $values );
    }

    function update( \woo\domain\DomainObject $object ) {
        $values = array(
            $object->getStart(),
            $object->getDuration(),
            $object->getName(),
            $object->getId(),
            $object->getId()
        );
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