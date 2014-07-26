<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 20:44
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/SelectionFactory.php" );

class CatalogPositionSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() ); // разбиваем искомые поля в Select id,name, и т.д. в классе IdentityObject
        $core = "SELECT $fields FROM system_position";  // составляем запрос
        $orderby = " ORDER BY pos ";
        list( $where, $values ) = $this->buildWhere( $obj ); // из родительского класса
        return array( $core." ".$where. " " . $orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }

    /**
     * Для получения максимальной позиции в таблице
     * @return string
     */
    function newSelectionMaxPos( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() );
        $core = "SELECT MAX( pos ) as pos FROM system_position";  // составляем запрос
        $orderby = " ORDER BY pos ";
        list( $where, $values ) = $this->buildWhere( $obj );
        return array( $core." ".$where." ".$orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }

    /**
     * Для получения максимальной позиции в таблице
     * @return string
     */
    function newSelectionCountPos( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() );
        $core = "SELECT COUNT(*) as count FROM system_position";  // составляем запрос
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj );
//        echo "<tt><pre>".print_r( $core." ".$where." ".$orderby, true )."</pre></tt>";
        return array( $core." ".$where." ".$orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }


    /**
     * Для получения массива размеров изображений
     * @return string
     */
    function newSelectionPhotoSettings() {
        $core = "SELECT * FROM system_photo_settings LIMIT 1";
        return $core;
    }
}
?>