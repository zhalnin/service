<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/06/14
 * Time: 18:44
 */

namespace imei_service\view\utils;
error_reporting( E_ALL & ~E_NOTICE );
//require_once( "imei_service/base/Registry.php" );
require_once( "imei_service/domain/Login.php" );
use imei_service\base\SessionRegistry;


function isAuth() {
    $sesUid  = SessionRegistry::getSession('uidu');
    if( isset( $_COOKIE['autou'] ) ) {
//        file_put_contents('security.txt', 'cookie'."\n", FILE_APPEND );
//        print 'kuki';
        if( isset( $_COOKIE['loginu'] ) && isset( $_COOKIE['passu'] ) ) {
            $login = $_COOKIE['loginu'];
            $pass  = $_COOKIE['passu'];
            $uid   = \imei_service\domain\Login::find( SessionRegistry::getSession('uidu') );

            if( is_object( $uid ) ) {
                if( $login !== $uid->getLogin() && $pass !== $uid->getPass() ) {
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
        $sesLogin = SessionRegistry::getSession('loginu');
        $sesPass  = SessionRegistry::getSession('passu');
        $uid      = \imei_service\domain\Login::find( SessionRegistry::getSession('uidu') );
        if( is_object( $uid ) ) {
            if( $sesLogin !== $uid->getLogin() && $sesPass !== $uid->getPass() ) {
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

    $enter = SessionRegistry::getSession('autou');
    $login = SessionRegistry::getSession('loginu');
//echo "<tt><pre>".print_r( $enter , true ) ."</pre></tt>";
    if( isAuth() === true ) {
?>

        <ul id="login-wrap">
            <li>
                <a id="login-nav" class="login-globalNavLink login-elem" href="#">
                    <span class="nav-link login-elem">
                        <img class="login-avatar" width="26" height="26" title="alezhal" alt="alezhal" data-random="11237289575920228" data-height="26" data-username="alezhal" data-avatarid="1316" src="imei_service/view/files/login/avatar/26_rounded.png">
                        <span class="login-user-name login-username-navLabel">Добро пожаловать, <?php print $login; ?></span>
                    </span>
                </a>
                |
                <a href="?cmd=Logout">Выйти</a>
                <div id="j-satNav-menu" class="clearfix" style="display:none;"></div>
            </li>
            <li class="cart">
                <?php if( isset( $_SESSION['total_items_imei_service'] ) && $_SESSION['total_items_imei_service'] != 0  ) {
                    $num_items = "( {$_SESSION['total_items_imei_service']} )";
                } else {
                    $num_items = "";
                }
                ?>

                <a href="?cmd=Cart" >Корзина <?php echo $num_items; ?></a>
            </li>
            <li>
                <div id="globalsearch">
                    <form id="g-search" class="search empty" method="get" >
                        <input id="search-command" type="hidden" name="cmd" value="Search">
                        <div class="sp-label">
                            <label for="sp-searchtext">Search</label>
                            <input id="sp-searchtext"  type="text" name="q" autocomplete="off" title="Введите строку для поиска. Строка запроса не должна начинаться с пробела" />
                            <div class="reset"></div>
                            <div class="spinner hide"></div>
                        </div>
                        <input id="search-section" type="hidden" name="sec" value="global">
                    </form>
                    <div id="sp-magnify">
                        <div class="magnify-searchmode"></div>
                        <div class="magnify"></div>
                    </div>
                    <div id="sp-results"></div>
                </div>
            </li>
        </ul>

<?php
    } else {
?>

        <ul id="login-wrap">
            <li>
                <ul id="login-nav">
                    <li class="welcome-guest">Добро пожаловать, Гость</li>
                    <li class="login">
                        |
                        <a title="Login" href="?cmd=Login">Войти</a>
                    </li>
                </ul>
                <div id="j-satNav-menu" class="clearfix" style="display:none;"></div>
            </li>
            <li class="cart">
                <?php if( isset( $_SESSION['total_items_imei_service'] ) && $_SESSION['total_items_imei_service'] != 0 ) {
                    $num_items = "( {$_SESSION['total_items_imei_service']} )";
                } else {
                    $num_items = "";
                }
                ?>

                <a href="?cmd=Cart" >Корзина <?php echo $num_items; ?></a>
            </li>
            <li>
                <div id="globalsearch">
                    <form id="g-search" class="search empty" method="get" action="">
                        <input id="search-command" type="hidden" name="cmd" value="Search">
                        <div class="sp-label">
                            <label for="sp-searchtext">Search</label>
                            <input id="sp-searchtext" class="sp-searchtext" type="text" name="q" autocomplete="off" title="Введите строку для поиска. Строка запроса не должна начинаться с пробела" />
                            <div class="reset"></div>
                            <div class="spinner hide"></div>
                        </div>
                        <input id="search-section" type="hidden" name="sec" value="global">
                    </form>
                    <div id="sp-magnify">
                        <div class="magnify-searchmode"></div>
                        <div class="magnify"></div>
                    </div>
                    <div id="sp-results"></div>
                </div>
            </li>
        </ul>

<?php

    }
?>
