<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 16:03
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/SelectionFactory.php" );


class CartOrderSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() ); // разбиваем искомые поля в Select id,name, и т.д. в классе IdentityObject
        $core = "SELECT $fields FROM system_cart_orders";  // составляем запрос
//        $orderby = " ORDER BY pos";
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj ); // из родительского класса
        return array( $core." ".$where. " " . $orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }
}
?>