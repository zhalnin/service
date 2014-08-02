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

class UsersUpdateFactory  extends UpdateFactory{

    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['fio']          = $obj->getFio();
        $values['city']         = $obj->getCity();
        $values['email']        = $obj->getEmail();
        $values['url']          = $obj->getUrl();
        $values['login']        = $obj->getLogin();
        $values['activation']   = $obj->getActivation();
        $values['status']       = $obj->getStatus();
        $values['pass']         = $obj->getPass();
        $values['putdate']      = $obj->getPutdate();
        $values['lastvisit']    = $obj->getLastvisit();
        $values['block']        = $obj->getBlock();

        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "system_account", $values, $cond );
    }
}
