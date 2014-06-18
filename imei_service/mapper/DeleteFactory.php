<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 18/06/14
 * Time: 16:52
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

abstract class DeleteFactory {
    abstract function newDelete( IdentityObject $obj );

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

    /**
     * Этот метод только для конструкции,
     * которая обязательно содержит условие: WHERE - UNIX_TIMESTAMP()-UNIX_TIMESTAMP(date)
     * @param IdentityObject $obj
     * @return array
     */
    function buildWhereForDate( IdentityObject $obj ) {
//        echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        if( $obj->isVoid() ) { // если поле для сравнения не было передано
            return array( "", array() ); // возвращаем массив, где array( WHERE = пустой строке, values = пустому массиву)
        }
        $compstrings = array();
        $values = array();
        foreach ( $obj->getComps() as $comp ) { // получаем массив с условными операторами из класса IdentityOjbect
            if( $comp['name'] == 'date' ) {
                $comp['name'] = 'UNIX_TIMESTAMP()-UNIX_TIMESTAMP(date)';
            }
            $compstrings[] = "{$comp['name']} {$comp['operator']} ?"; // создаем строку для запроса, к примеру: "hide = ?"
            $values[] = $comp['value']; // здесь само значение для сравнения
        }

        $where = "WHERE " . implode( " AND ", $compstrings ); // если операторов сравнения более одного, то соединяем их оператором AND
//        echo "<tt><pre>".print_r($where, true)."</pre></tt>";
        return array( $where, $values ); // возвращаем массив, к примеру: array( "hide = ? AND id = ?", "'show', 27" );
    }
}
?>