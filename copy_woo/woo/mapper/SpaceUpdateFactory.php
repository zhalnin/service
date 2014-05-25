<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23/03/14
 * Time: 20:22
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/mapper/UpdateFactory.php" );

class SpaceUpdateFactory extends UpdateFactory {
    function newUpdate( \woo\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['name'] = $obj->getName();
        $values['venue'] = $obj->getVenue()->getId();

        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "space", $values, $cond );
    }
}
?>