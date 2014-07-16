<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/07/14
 * Time: 23:45
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

class NewsUpdateFactory extends UpdateFactory{

    function newUpdate( \dmn\domain\DomainObject $obj ) {
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