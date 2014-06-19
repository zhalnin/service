<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/12/13
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once("utils/utils_server_name.php");
header("Content-type:text/html; charset=utf-8");
ob_start();
$server = serverName()."ind_controller.php";

print("Вы успешно сбросили пароль к вашей учетной записи.<br />
    К вам на email придет письмо с вашим логином и новым паролем для входа на сайт.<br />
    Позже вы сможете сменить пароль в своем профиле.");



header('Refresh:5; URL=http://'.$server);
//ob_get_flush();
?>