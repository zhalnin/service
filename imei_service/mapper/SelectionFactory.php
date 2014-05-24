<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 19:59
 */

namespace imei_service\mapper;


abstract class SelectionFactory {
    abstract function newsSelection( IdentityObject $obj );

    function buildWhere( IdentityObject $obj ) {
//        echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        if( $obj->isVoid() ) {
            return array( "", array() );
        }
        $compstrings = array();
        $values = array();
        foreach ( $obj->getComps() as $comp ) {
            $compstrings[] = "{$comp['name']} {$comp['operator']} ?";
            $values[] = $comp['value'];
        }
        $where = "WHERE " . implode( " AND ", $compstrings );
        return array( $where, $values );
    }

//    function buildOrder( IdentityObject $obj ) {
//        if( $obj->isVoid() ) {
//            return array( "", array() );
//        }
//        $compstrings = array();
//        $values = array();
//        foreach ( $obj->getComps() as $comp ) {
//            $compstrings[] = "{$comp['name']} ?";
//            $values[] = $comp['value'];
//        }
//        $order = "ORDER BY ". implode(" AND ", $compstrings );
//        return array( $order, $values);
//    }
}
?>