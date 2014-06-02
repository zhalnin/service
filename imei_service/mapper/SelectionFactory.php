<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 19:59
 */

namespace imei_service\mapper;


abstract class SelectionFactory {
    abstract function newSelection( IdentityObject $obj );

    /**
     * Строим конструкцию WHERE
     * @param IdentityObject $obj
     * @return array
     */
    function buildWhere( IdentityObject $obj ) {
//        echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        if( $obj->isVoid() ) { // если поле для сравнения не было передано
            return array( "", array() ); // возвращаем массив, где array( WHERE = пустой строке, values = пустому массиву)
        }
        $compstrings = array();
        $values = array();
        foreach ( $obj->getComps() as $comp ) { // получаем массив с условными операторами из класса IdentityOjbect
            $compstrings[] = "{$comp['name']} {$comp['operator']} ?"; // создаем строку для запроса, к примеру: "hide = ?"
            $values[] = $comp['value']; // здесь само значение для сравнения
        }
        $where = "WHERE " . implode( " AND ", $compstrings ); // если операторов сравнения более одного, то соединяем их оператором AND
//        echo "<tt><pre>".print_r($where, true)."</pre></tt>";
        return array( $where, $values ); // возвращаем массив, к примеру: array( "hide = ? AND id = ?", "'show', 27" );
    }
}
?>