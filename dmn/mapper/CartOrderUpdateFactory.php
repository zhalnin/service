<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 13:13
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

class CartOrderUpdateFactory  extends UpdateFactory{

    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['firstname']        = $obj->getFirstName();
        $values['lastname']         = $obj->getLastName();
        $values['email']            = $obj->getEmail();
        $values['data']             = $obj->getData();
        $values['country']          = $obj->getCountry();
        $values['address']          = $obj->getAddress();
        $values['city']             = $obj->getCity();
        $values['zip_code']         = $obj->getZipCode();
        $values['state']            = $obj->getState();
        $values['status']           = $obj->getStatus();
        $values['amount']           = $obj->getAmount();
        $values['paypal_trans_id']  = $obj->getPaypalTransId();
        $values['created_at']       = $obj->getCreatedAt();

        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_cart_orders", $values, $cond );
    }
}
