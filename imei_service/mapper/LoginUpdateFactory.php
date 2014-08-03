<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/06/14
 * Time: 00:24
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/UpdateFactory.php" );

class LoginUpdateFactory extends UpdateFactory {

    function newUpdate( \imei_service\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['fio']          = $obj->getFio();
        $values['city']         = $obj->getCity();
        $values['email']        = $obj->getEmail();
        $values['url']          = $obj->getUrl();
        $values['login']        = $obj->getLogin();
        $values['pass']         = $obj->getPass();
        $values['activation']   = $obj->getActivation();
        $values['status']       = $obj->getStatus();
        $values['putdate']      = $obj->getPutdate();
        $values['lastvisit']    = $obj->getLastvisit();
        $values['block']        = $obj->getBlock();
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_account", $values, $cond );
    }
}

?>