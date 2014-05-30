<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/05/14
 * Time: 19:01
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/SelectionFactory.php" );

class GuestbookSelectionFactory extends SelectionFactory {

    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() );
        $core = "SELECT $fields FROM system_guestbook";
        $orderby = " ORDER BY putdate DESC";
        list( $where, $values ) = $this->buildWhere( $obj );
        return array( $core . " " . $where . " " . $orderby, $values );
    }

}
?>