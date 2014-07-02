<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/01/14
 * Time: 22:05
 * To change this template use File | Settings | File Templates.
 */

namespace account\database;


class RegistrationManager extends DataBase {

    static $checkLoginExists = "SELECT COUNT(*) FROM account WHERE login=?";


    function checkLoginExists( array $login ) {
        $result = $this->doStatement(self::$checkLoginExists, $login );
        if( $result->fetchColumn() > 0 ) {
            return true;
        } else {
            return false;
        }
    }
}
?>