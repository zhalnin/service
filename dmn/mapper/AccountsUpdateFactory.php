<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 16:00
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

class AccountsUpdateFactory  extends UpdateFactory{

    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['name']      = $obj->getName();
        $values['pass']      = $obj->getPass();
        $values['lastvisit'] = $obj->getLastvisit();

        if( $id > -1 ) {
            $cond['id_account'] = $id;
        }
        return $this->buildStatement( "system_accounts", $values, $cond );
    }
}
