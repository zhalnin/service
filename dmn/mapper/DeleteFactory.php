<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/07/14
 * Time: 22:27
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

/**
 * Class DeleteFactory
 * Для получения условия и удаления записи из таблицы БД
 * @package dmn\mapper
 */
abstract class DeleteFactory {
    // метод для получения id удаляемой записи и вызова метода buildStatement
    abstract function newDelete( \dmn\domain\DomainObject $obj );

    /**
     * Получаем целевую таблицу, условие и
     * строим запрос
     * @param $table
     * @param array $conditions
     * @return array
     */
    protected function buildStatement( $table, array $conditions=null ) {
        $terms = array();
        if( ! is_null( $conditions ) ) { // если есть условие
            $query = "DELETE FROM {$table} ";
            $query .= " WHERE ";
            foreach ( $conditions as $key => $val ) { // проходимся по массиву условия
                $cond[] = "$key = ?"; // формируем массив: ИМЯ = ?
                $terms[] = $val; // сохраняем значения
            }
            $query .= implode( " AND ", $cond ); // если несколько условий, то объединяем их с помощью AND

        }
        return array( $query, $terms ); // возвращаем сам запрос и значения
    }
}
?>