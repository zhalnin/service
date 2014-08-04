<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 20:36
 */

namespace dmn\view\utils;
error_reporting( E_ALL & ~E_NOTICE );
require_once( "dmn/domain/Accounts.php" );
use dmn\base\SessionRegistry;

//echo "<tt><pre>".print_r( $_COOKIE , true ) ."</pre></tt>";
//echo "<tt><pre>".print_r( SessionRegistry::getSession('uid') , true ) ."</pre></tt>";
function isAuth() {
    $sesUid  = SessionRegistry::getSession('uid');
    if( isset( $_COOKIE['auto'] ) ) {
//        print 'kuki';
        if( isset( $_COOKIE['login'] ) && isset( $_COOKIE['pass'] ) ) {
            $login = $_COOKIE['login'];
            $pass  = $_COOKIE['pass'];
            $uid   = \dmn\domain\Accounts::find( SessionRegistry::getSession('uid') );

            if( is_object( $uid ) ) {
                if( $login !== $uid->getName() && $pass !== $uid->getPass() ) {
                    return false;
                } else {
                    $uid->setLastvisit( date( 'Y-m-d H:i:s', time() ) );
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } elseif( isset( $sesUid )  ) {
//        print 'sessia';
        $sesLogin = SessionRegistry::getSession('login');
        $sesPass  = SessionRegistry::getSession('pass');
        $uid      = \dmn\domain\Accounts::find( SessionRegistry::getSession('uid') );
            if( is_object( $uid ) ) {
                if( $sesLogin !== $uid->getName() && $sesPass !== $uid->getPass() ) {
                    return false;
                } else {
                    $uid->setLastvisit( date( 'Y-m-d H:i:s', time() ) );
                    return true;
                }
            } else {
                return false;
            }
    } else {
        return false;
    }
}

if( isAuth() === false ) {
    header( 'Location: dmn.php?cmd=Login' );
}


?>