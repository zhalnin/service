<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 19:01
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/SelectionFactory.php" );

class ContactsSelectionFactory extends SelectionFactory {

    /**
     * Принимает объект с условными операторами
     * @param IdentityObject $obj
     * @return array
     */
    function newSelection( IdentityObject $obj ) {
        $fields = implode( ',', $obj->getObjectFields() );
        $core = "SELECT $fields FROM system_contactaddress";
        list( $where, $values ) = $this->buildWhere( $obj );
        return array( $core ." ". $where, $values );
    }
}