<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 15:59
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/SelectionFactory.php" );


class UsersSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() ); // разбиваем искомые поля в Select id,name, и т.д. в классе IdentityObject
        $core = "SELECT $fields FROM system_account";  // составляем запрос
//        $orderby = " ORDER BY pos";
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj ); // из родительского класса
        return array( $core." ".$where. " " . $orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }

    /**
     * Для получения максимальной позиции в таблице
     * @param IdentityObject $obj
     * @return array
     */
    function newSelectionCountPos( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() );
        $core = "SELECT COUNT(*) as count FROM system_account";  // составляем запрос
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj );
//        echo "<tt><pre>".print_r( $core." ".$where." ".$orderby, true )."</pre></tt>";
        return array( $core." ".$where." ".$orderby, $values ); // возвращаем запрос с условными операторами WHERE ... < ? AND id = ? и массив с значениями
    }


}
?>