<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 25/05/14
 * Time: 20:47
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/UpdateFactory.php" );

class NewsUpdateFactory extends UpdateFactory{

    function newUpdate( \imei_service\domain\DomainObject $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['name'] = $obj->getName();
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_news", $values, $cond );
    }
}

?>