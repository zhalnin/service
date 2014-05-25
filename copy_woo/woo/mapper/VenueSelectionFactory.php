<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23/03/14
 * Time: 20:56
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/mapper/SelectionFactory.php" );
require_once("woo/mapper/VenueIdentityObject.php");

class VenueSelectionFactory extends SelectionFactory {

    function newSelection( IdentityObject $obj ) {
        $fields = implode(",", $obj->getObjectFields() );
        $core = "SELECT $fields FROM venue";
        list( $where, $values ) = $this->buildWhere( $obj );
        return array( $core." ". $where, $values);
    }
}
?>