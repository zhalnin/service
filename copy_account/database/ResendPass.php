<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/01/14
 * Time: 22:17
 * To change this template use File | Settings | File Templates.
 */

namespace account\database;

class ResendPassManager extends DataBase {

    static $countEmailActivate = "SELECT COUNT(*) FROM account WHERE email=? AND status=1";
    static $selectExistsAccount = "SELECT * FROM account WHERE email=?";
    static $updateAccount = "UPDATE account SET fio=?,city=?,email=?,login=?,pass=?,activation=? WHERE id=? AND email=?";

    function helperCountEmailActivate( $email ) {
        $sthc = $this->doStatement( self::$countEmailActivate, $email );
        return $sthc;
    }

    function helperSelectExistsAccount( $email ) {
        $sths = $this->doStatement( self::$selectExistsAccount, $email );
        return $sths;
    }

    function helperUpdateAccount( $args ) {
        $sthu = $this->doStatement( self::$updateAccount, $args );
    }

    function checkEmailActivate( array $email ) {
        if( $this->helperCountEmailActivate( $email )->fetchColumn() > 0 ) {
            $sths = $this->helperSelectExistsAccount( $email );
            while( $row = $sths->fetch() ) {
                $id = $row['id'];
                $fio = $row['fio'];
                $login = $row['login'];
                $city = $row['city'];
                $email = $row['email'];
                $pass_u = "password".time();
                $pass = md5( "password".time() );
                $activation = md5( $email.time() );
                $this->helperUpdateAccount( array( $fio, $city, $email,$login, $pass, $activation, $id, $email ) );
            }
            $server = serverName();
            $subject    = "Reset Password";
            $message    = "You reset your password successfuly!<br />
                Your login: $login<br />
                Your password: $pass_u<br />
                You can visit site with your account <a href=".$server."ind.php>Enter site</a>";
            $header = "Content-type:text/html; charset=utf-8";
            mail($email, $subject, $message, $header );
            return true;
        } else {
            return false;
        }
    }
}

?>