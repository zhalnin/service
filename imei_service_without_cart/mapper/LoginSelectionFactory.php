<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/06/14
 * Time: 14:27
 */

namespace imei_service\mapper;

error_reporting( E_ALL & ~E_NOTICE );
require_once( 'imei_service/mapper/SelectionFactory.php' );

class LoginSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() ); // разбиваем искомые поля в Select id,name, и т.д. в классе IdentityObject
        $core = "SELECT $fields FROM system_account";  // составляем запрос
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj ); // из родительского класса
        return array( $core." ".$where. " " . $orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }
}
?>