<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 13:28
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

class CartItemsUpdateFactory extends UpdateFactory{

    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['product_id']       = $obj->getProductId();
        $values['order_id']         = $obj->getOrderId();
        $values['title']            = $obj->getTitle();
        $values['price']            = $obj->getPrice();
        $values['qty']              = $obj->getQty();

        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_cart_items", $values, $cond );
    }
}
