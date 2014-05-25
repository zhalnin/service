<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 03/02/14
 * Time: 21:21
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

abstract class DomainObjectFactory {
    abstract function createObject( array $array );

    protected function getFromMap( $class, $id ) {
        return \woo\domain\ObjectWatcher::exists( $class, $id );
    }

    protected function addToMap( \woo\domain\DomainObject $obj ) {
        \woo\domain\ObjectWatcher::add( $obj );
    }
}


class VenueObjectFactory extends DomainObjectFactory {
    /**
     * Создаем объект (из Mapper)
     * @param array $array
     * @return null
     */
    function createObject( array $array ) {
        $class = '\woo\domain\Venue';
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}

class SpaceObjectFactory extends DomainObjectFactory {
    function createObject( array $array ) {
//        echo "<tt><pre> SpaceObjectFactory - ".print_r($array, true)."</pre></tt>";
        $class = '\woo\domain\Space';
        $old = $this->getFromMap( $class, $array['id'] );
//        echo "<tt><pre> old -  ".print_r($old,true)."</pre></tt>";
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
//        echo "<tt><pre> SpaceObjectFactory - obj ".print_r($obj,true)."</pre></tt>";
        $obj->setName( $array['name'] );


////        echo "<tt><pre> SpaceObjectFactory - obj ".print_r($obj,true)."</pre></tt>";
//        $ven_mapper = new VenueMapper();
////        echo "<tt><pre> SpaceObjectFactory - venue_mapper ".print_r($ven_mapper,true)."</pre></tt>";
////        echo "<tt><pre> SpaceObjectFactory - venue_id ".print_r($array['venue'],true)."</pre></tt>";
//
//        $venue = $ven_mapper->find( $array['venue'] );
////        echo "<tt><pre> SpaceObjectFactory - venue ".print_r($array['venue'],true)."</pre></tt>";
//
//        $obj->setVenue( $venue );
////        echo "<tt><pre> SpaceObjectFactory - obj ".print_r($obj,true)."</pre></tt>";

        $factory = PersistenceFactory::getFactory( 'woo\domain\Venue' );
        $ven_assembler = new DomainObjectAssembler( $factory );
        $ven_idobj = new VenueIdentityObject( 'id' );
        $ven_idobj->eq( $array['id'] );
        $venue = $ven_assembler->findOne( $ven_idobj );



//        $event_mapper = new EventMapper();
////        echo "<tt><pre> SpaceObjectFactory - event_mapper ".print_r($array['id'],true)."</pre></tt>";
//        $event_collection = $event_mapper->findBySpaceId( $array['id'] );
////        echo "<tt><pre> SpaceObjectFactory - event_collection ".print_r($event_collection,true)."</pre></tt>";
//        $obj->setEvents( $event_collection );
////        $this->addToMap( $obj );
//        $obj->markClean();

        $factory = PersistenceFactory::getFactory( 'woo\domain\Event' );
        $event_assembler = new DomainObjectAssembler( $factory );
        $event_idobj = new EventIdentityObject( 'space' );
        $event_idobj->eq( $array['id'] );
        $event_collection = $event_assembler->find( $event_idobj );
        $obj->setEvents( $event_collection );
        $obj->markClean();

        return $obj;
    }
}

class EventObjectFactory extends DomainObjectFactory {
    function createObject( array $array ) {
        $class = '\woo\domain\Event';
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setStart( $array['start'] );
        $obj->setDuration( $array['duration'] );
        $obj->setName( $array['name'] );

//        $space_mapper = new SpaceMapper();
//        $space = $space_mapper->find( $array['space'] );

        $factory = PersistenceFactory::getFactory( 'woo\domain\Space' );
        $spc_assembler = new DomainObjectAssembler( $factory );
        $spc_idobj = new SpaceIdentityObject('id');
        $spc_idobj->eq( $array['space'] );
        $space = $spc_assembler->findOne( $spc_idobj );

        $obj->setSpace( $space );
//        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}

?>