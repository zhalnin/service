<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 13/07/14
 * Time: 21:48
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/UpdateFactory.php" );

class CartItemsUpdateFactory  extends UpdateFactory{

    function newUpdate( \imei_service\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;

        $values['product_id']   = $obj->getProductId();
        $values['order_id']     = $obj->getOrderId();
        $values['title']        = $obj->getTitle();
        $values['price']        = $obj->getPrice();
        $values['qty']          = $obj->getQty();


        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_cart_items", $values, $cond );
    }
}

?>