<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/07/14
 * Time: 16:31
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );


/**
 * Class UpDownFactory
 * Фабрика для перемещения позиции вверх или вниз
 * @package dmn\mapper
 *
 */
abstract class UpDownFactory {

    // запрос SELECT
    abstract function newUpDownSelect( IdentityObject $obj, $orderby );
    // запрос UPDATE
    abstract function newUpDownUpdate( $terms, $direct );


    /**
     * Метод для построения запроса SELECT
     * @param IdentityObject $obj
     * @return array
     */
    protected function buildSelect( IdentityObject $obj ) {
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
     * Метод для построения запроса UPDATE
     * @param $table - имя талицы БД
     * @param array $params - массив со значениями
     * @param $direct - направление движения позиции
     * @return array - массив с построенным запросом и значениями
     */
    protected function buildUpdate( $table, array $params, $direct ) {
        switch( $direct ) {
            case 'up': // если перемещаемся вверх
                // формируем массив со значениями
                $terms = array( $params['current'],
                                $params['previous'],
                                $params['current'],
                                $params['previous']);
                // составляем запрос
                $query = "UPDATE {$table}
                            SET pos = ? + ? - pos
                            WHERE pos IN( ?, ? )";
                return array( $query, $terms );
                break;
            case 'down': // если перемещаемся вниз
                // формируем массив со значениями
                $terms = array( $params['next'],
                                $params['current'],
                                $params['next'],
                                $params['current']);
                // составляем запрос
                $query = "UPDATE {$table}
                            SET pos = ? + ? - pos
                            WHERE pos IN( ?, ? )";
                return array( $query, $terms );
                break;
            case 'uppest':
                // формируем массив со значениями
                $terms = array( $params['current'],
                                $params['previous'],
                                $params['current'],
                                $params['previous']);
                // составляем запрос
                $query = "UPDATE {$table}
                            SET pos = ? + ? - pos
                            WHERE pos IN( ?, ? )";
                return array( $query, $terms );
                break;
        }
    }
}
?>
