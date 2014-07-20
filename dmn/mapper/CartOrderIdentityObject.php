<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 19:17
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/IdentityObject.php" );

class CartOrderIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array('id',
                'firstname',
                'lastname',
                'email',
                'data',
                'country',
                'address',
                'city',
                'zip_code',
                'state',
                'status',
                'amount',
                'paypal_trans_id',
                'created_at')
        );
    }
}
?>