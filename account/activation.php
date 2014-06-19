<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10/12/13
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */

ob_start();
error_reporting(E_ALL & ~E_NOTICE );


require_once("base/Registry.php");
require_once("utils/utils_server_name.php");
require_once("view/ViewHelper.php");

header("Content-type:text/html; charset=utf-8");


    try {
        $DBH = \account\base\DataBaseRegistry::getDB();
        $request = \account\view\VH::getRequest();
        $login = $request->getProperty('login');
        $activation = $request->getProperty('code');
//        echo "<tt><pre>".print_r($request,true)."</pre></tt>";

        $server     = serverName()."ind_site.php";
        $ud = array($login,$activation);

        $update = "UPDATE account SET status=1 WHERE login=? AND activation=?";
                    $STH = $DBH->prepare( $update );
                    $STH->execute( $ud );
                    echo "Вы подтвердили ваш Email и теперь вы можете зайти на сайт под своим логином!
                <a href='ind_controller.php'>Вход на сайт</a>";
    } catch (PDOException $e ) {
        echo $e->getMessage();
    } catch (Exception $e ) {
        echo $e->getMessage();
    }
ob_get_flush();
?>
