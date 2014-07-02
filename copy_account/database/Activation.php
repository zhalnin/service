<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/01/14
 * Time: 22:18
 * To change this template use File | Settings | File Templates.
 */

namespace account\database;

class ActivationManager extends DataBase {
    static $countUserExists = "SELECT COUNT(*) FROM account WHERE login=? AND activation=?";
    static $countUserActivate = "SELECT * FROM account WHERE login=? AND activation=? AND status=1";

    function checkUserExists( array $ud ) {
        if( $this->doStatement( self::$countUserExists, $ud )->fetchColumn() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    function checkUserActivate( array $ud ) {
        if( $this->doStatement( self::$countUserActivate, $ud )->fetchColumn() > 0 ) {
            return true;
        } else {
            return false;
        }
    }
}
?>