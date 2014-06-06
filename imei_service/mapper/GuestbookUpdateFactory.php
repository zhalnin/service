<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 06/06/14
 * Time: 13:38
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/UpdateFactory.php" );

class GuestbookUpdateFactory extends UpdateFactory {

    function newUpdate( \imei_service\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['name'] = $obj->getName();
        $values['city'] = $obj->getCity();
        $values['email'] = $obj->getEmail();
        $values['url'] = $obj->getUrl();
        $values['message'] = $obj->getMessage();
        $values['answer'] = $obj->getAnswer();
        $values['putdate'] = $obj->getPutdate();
        $values['hide'] = $obj->getHide();
        $values['id_parent'] = $obj->getIdparent();
        $values['ip'] = $obj->getIp();
        $values['browser'] = $obj->getBrowser();
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_guestbook", $values, $cond );
    }
}

?>