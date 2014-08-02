<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 16:11
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/DeleteFactory.php" );

/**
 * Class AccountsDeleteFactory
 * Для получения условия и удаления записи из
 * таблицы system_accounts
 * @package dmn\mapper
 */
class AccountsDeleteFactory extends DeleteFactory{

    /**
     * Получаем объект,
     * из него id удаляемой записи
     * и строим запрос
     * @param \dmn\domain\DomainObject $obj
     * @return array
     */
    function newDelete( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId(); // получаем id записи
        $cond = null;

        if( $id > -1 ) { // если есть условие, а оно есть
            $cond['id_account'] = $id;
        }
        return $this->buildStatement( "system_accounts", $cond ); // строим запрос
    }
}

?>