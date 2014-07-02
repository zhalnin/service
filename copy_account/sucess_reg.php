<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/12/13
 * Time: 00:25
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once(dirname(__FILE__)."/utils/utils_server_name.php");

header("Content-type:text/html; charset=utf-8");
ob_start();
echo "Через несколько минут вы должны получить письмо на адрес электронной почты, которую вы указали,
            с ссылкой для подтверждения регистрации";

$server = serverName()."ind_controller.php";

header('Refresh: 5; URL=http://'.$server);
ob_get_flush();
