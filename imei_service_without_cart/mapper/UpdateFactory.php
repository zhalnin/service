<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 25/05/14
 * Time: 20:48
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;


abstract class UpdateFactory {
    abstract function newUpdate( \imei_service\domain\DomainObject $obj );

    protected function buildStatement( $table, array $fields, array $conditions=null ) {
        $terms = array();
        if( ! is_null( $conditions ) ) {
            $query = "UPDATE {$table} SET ";
            $query .= implode( " = ?, ", array_keys( $fields ) ). " = ?";
            $terms = array_values( $fields );
            $query .= " WHERE ";
            foreach ( $conditions as $key => $val ) {
                $cond[] = "$key = ?";
                $terms[] = $val;
            }
            $query .= implode( " AND ", $cond );

        } else {
            $query = "INSERT INTO {$table} (";
            $query .= implode( ",", array_keys( $fields ) );
            $query .= ") VALUES (";
            foreach ( $fields as $name => $value ) {
                $terms[] = $value;
                $qs[] = '?';
            }
            $query .= implode( ",", $qs );
            $query .= ")";
        }
        return array( $query, $terms );
    }
}
?>