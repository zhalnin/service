<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:20
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

/**
 * Class UpdateFactory
 * Для получения условия и обновления/вставки записи в таблицу БД
 * @package dmn\mapper
 */
abstract class UpdateFactory {
    // метод для получения id обновляемой записи и вызова метода buildStatement
    abstract function newUpdate( \dmn\domain\DomainObject $obj );

    /**
     * Получаем целевую таблицу, условие и
     * строим запрос
     * @param $table
     * @param array $fields
     * @param array $conditions
     * @return array
     */
    protected function buildStatement( $table, array $fields, array $conditions=null ) {
        $terms = array();
        if( ! is_null( $conditions ) ) { // если есть условие
            $query = "UPDATE {$table} SET ";
            $query .= implode( " = ?, ", array_keys( $fields ) ). " = ?"; // формируем массив из: id = ?, name = ?, ...
            $terms = array_values( $fields ); // сохраняем значения
            $query .= " WHERE ";
            foreach ( $conditions as $key => $val ) { // проходим в цикле по условию
                $cond[] = "$key = ?"; // формируем массив из: id = ?, name = ?, ...
                $terms[] = $val; // сохраняем значения
            }
            $query .= implode( " AND ", $cond ); // если несколько условий, то объединяем их с помощью AND

        } else { // если условия нет
            $query = "INSERT INTO {$table} (";
            $query .= implode( ",", array_keys( $fields ) ); // формируем массив из: id, name, ...
            $query .= ") VALUES (";
            foreach ( $fields as $name => $value ) { // проходим в цикле по значениям
                $terms[] = $value; // получаем массив с добавляемыми значениями
                $qs[] = '?'; // и такое же количество знаков вопроса
            }
            $query .= implode( ",", $qs ); // если несколько знаков вопроса, то объединяем их с помощью запятой
            $query .= ")";
        }
        return array( $query, $terms ); // возвращаем сам запрос и значения
    }
}
?>