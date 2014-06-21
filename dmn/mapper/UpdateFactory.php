<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:20
 */

namespace dmn\mapper;


abstract class UpdateFactory {
    abstract function newUpdate( \dmn\domain\DomainObject $obj );

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