<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/01/14
 * Time: 16:24
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

require_once( "woo/domain/DomainObject.php" );

class Space extends DomainObject {

    private $name;
    private $events;
    private $venue;

    function __construct( $id=null, $name='main' ) {
        parent::__construct( $id );
        $this->events = null;
        $this->name = $name;
    }

    function setEvents( EventCollection $events ) {
        $this->events = $events;
    }

    function getEvents() {
//        echo "<tt><pre> woo\domain\Space->getEvents() - ".print_r($this->events,true)."</pre></tt>";
        if( is_null( $this->events ) ) {
            $idobj = new \woo\mapper\EventIdentityObject( 'space' );
//            $this->events =
//                self::getFinder( "woo\\domain\\Event" )->findBySpaceId( $this->getId() );
            $this->events =
                self::getFinder( "woo\\domain\\Event" )->find( $idobj->eq( $this->getId() ) );
        }
        return $this->events;
    }

    function addEvent( Event $event ) {
        $this->events->add( $event );
        $event->setSpace( $this );
    }

    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }

    /**
     * Вызываем из woo/domain/Venue
     * @param Venue $venue
     */
    function setVenue( Venue $venue ) {
//        echo "<tt><pre> setVenue - ".print_r($venue,true)."</pre></tt>";
        $this->venue = $venue;
        $this->markDirty();
    }

    function getVenue() {
        return $this->venue;
    }

    function getName() {
        return $this->name;
    }
}
?>