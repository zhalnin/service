<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/06/14
 * Time: 19:50
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/UpdateFactory.php" );

class LoginOshibkaUpdateFactory extends UpdateFactory {

    function newUpdate( \imei_service\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['ip'] = $obj->getIp();
        $values['date'] = $obj->getDate();
        $values['col'] = $obj->getCol();;
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_login_oshibka", $values, $cond );
    }
}

?>