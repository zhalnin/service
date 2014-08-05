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
    $sesUid  = SessionRegistry::getSession('uida');
    if( isset( $_COOKIE['autoa'] ) ) {
//        file_put_contents('security.txt', 'cookie'."\n", FILE_APPEND );
//        print 'kuki';
        if( isset( $_COOKIE['logina'] ) && isset( $_COOKIE['passa'] ) ) {
            $login = $_COOKIE['logina'];
            $pass  = $_COOKIE['passa'];
            $uid   = \dmn\domain\Accounts::find( SessionRegistry::getSession('uida') );

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
//        file_put_contents('security.txt', 'session'."\n", FILE_APPEND );
//        print 'sessia';
        $sesLogin = SessionRegistry::getSession('logina');
        $sesPass  = SessionRegistry::getSession('passa');
        $uid      = \dmn\domain\Accounts::find( SessionRegistry::getSession('uida') );
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
//    file_put_contents('security.txt', 'false'."\n", FILE_APPEND );
    header( 'Location: dmn.php?cmd=Login' );
    exit();
}
?>