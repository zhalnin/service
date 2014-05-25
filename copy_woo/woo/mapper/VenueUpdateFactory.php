<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23/03/14
 * Time: 18:07
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/mapper/UpdateFactory.php" );

class VenueUpdateFactory extends UpdateFactory {
    function newUpdate( \woo\domain\DomainObject $obj ) {
        // Проверка типов удалена
        $id = $obj->getId();
        $cond = null;
        $values['name'] = $obj->getName();
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "venue", $values, $cond );
    }
}
?>