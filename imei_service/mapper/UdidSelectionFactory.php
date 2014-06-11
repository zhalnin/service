<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/06/14
 * Time: 17:40
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/SelectionFactory.php" );

class UdidSelectionFactory extends SelectionFactory {

    function newSelection( IdentityObject $obj ) {
        $fields = implode(',', $obj->getObjectFields() );
        $core = "SELECT $fields FROM system_catalog";
        $orderby = "ORDER BY title_flag";
        list( $where, $values ) = $this->buildWhere( $obj );

        return array( $core." ".$where." ".$orderby, $values );
    }
}
?>