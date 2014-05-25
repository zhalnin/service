<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23/03/14
 * Time: 21:02
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/mapper/SelectionFactory.php" );
require_once("woo/mapper/SpaceIdentityObject.php");

class SpaceSelectionFactory extends SelectionFactory {

    function newSelection( IdentityObject $obj ) {
        $fields = implode(',', $obj->getObjectFields() );
        $core = "SELECT $fields FROM space";
        list( $where, $values ) = $this->buildWhere( $obj );
        return array( $core." ". $where, $values );
    }
}
?>