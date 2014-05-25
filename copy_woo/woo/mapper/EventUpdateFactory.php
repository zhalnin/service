<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23/03/14
 * Time: 20:25
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/mapper/UpdateFactory.php" );

class EventUpdateFactory extends UpdateFactory {
    function newUpdate( \woo\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['name'] = $obj->getName();
        $values['space'] = $obj->getSpace()->getId();

        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( 'event', $values, $cond );
    }
}
?>