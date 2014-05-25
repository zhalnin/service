<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/01/14
 * Time: 21:55
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

interface VenueCollection extends \Iterator {
    function add( DomainObject $venue );
}

interface SpaceCollection extends \Iterator {
    function add( DomainObject $space );
}

interface EventCollection extends \Iterator {
    function add( DomainObject $event );
}
?>