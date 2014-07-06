<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 18/06/14
 * Time: 16:50
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/DeleteFactory.php" );

class LoginOshibkaDeleteFactory extends DeleteFactory {

    /**
     * Общий метод для конструкции удаления полей
     * по заданному условию
     * @param IdentityObject $obj
     * @return array
     */
    function newDelete( IdentityObject $obj ) {
        $fields = implode(',', $obj->getObjectFields() );
        $core = "DELETE FROM system_login_oshibka";
        $orderby = "";
        list( $where, $values ) = $this->buildWhere( $obj );
//        echo "<tt><pre>".print_r($where, true)."</pre></tt>";

        return array( $core." ".$where." ".$orderby, $values );
    }

    /**
     * Специфический метод для удаления из БД записей,
     * которые подходят под выборку в buildWhereForDate - WHERE UNIX_TIMESTAMP()-UNIX_TIMESTAMP(date) - нужно 15 минут
     * @param IdentityObject $obj
     * @return array
     */
    function newDeleteEarly( IdentityObject $obj ) {
        $fields = implode(',', $obj->getObjectFields() );
        $core = "DELETE FROM system_login_oshibka";
        $orderby = "";
        list( $where, $values ) = $this->buildWhereForDate( $obj );
//        echo "<tt><pre>".print_r($where, true)."</pre></tt>";

        return array( $core." ".$where." ".$orderby, $values );
    }
}
?>