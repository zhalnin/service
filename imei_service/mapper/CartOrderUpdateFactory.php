<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/07/14
 * Time: 21:47
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/UpdateFactory.php" );

class CartOrderUpdateFactory extends UpdateFactory {

    function newUpdate( \imei_service\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;

        $values['firstname']        = $obj->getFirstName();
        $values['lastname']         = $obj->getLastName();
        $values['email']            = $obj->getEmail();
        $values['country']          = $obj->getCountry();
        $values['address']          = $obj->getAddress();
        $values['city']             = $obj->getCity();
        $values['zip_code']         = $obj->getZipCode();
        $values['state']            = $obj->getState();
        $values['status']           = $obj->getStatus();
        $values['amount']           = $obj->getAmount();
        $values['paypal_trans_id']  = $obj->getPaypalTransId();
        $values['created_at']       = $obj->getCreatedAt();
        $values['data']             = $obj->getData();

        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_cart_orders", $values, $cond );
    }
}

?>