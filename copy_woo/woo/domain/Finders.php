<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 26/03/14
 * Time: 20:36
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

interface Finder {
    function find( $id );
    function findAll();

    function update( DomainObject $object );
    function insert( DomainObject $object );
//    function delete();
}

interface SpaceFinder extends Finder {
    function findByVenue( $id );
}

interface VenueFinder extends Finder {

}

interface EventFinder extends Finder {

}
?>