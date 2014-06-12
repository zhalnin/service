<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 22:33
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/SelectionFactory.php" );

class FaqParagraphImageSelectionFactory extends SelectionFactory {

    function newSelection( IdentityObject $obj ) {
//        echo "<tt><pre>".print_r( $obj, true) ."</pre></tt>";
        $fields = implode(',', $obj->getObjectFields() );
        $core = "SELECT $fields FROM system_menu_paragraph_image";
        $orderby = "ORDER BY pos";
        list( $where, $values ) = $this->buildWhere( $obj );

        return array( $core." ".$where." ".$orderby, $values );
    }
}
?>