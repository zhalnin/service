<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 24/02/14
 * Time: 17:19
 * To change this template use File | Settings | File Templates.
 */

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизацию
require_once("../utils/security_mod.php");
// Поключаем классы
require_once("../../config/class.config.dmn.php");
// Блок управление позициями (show(), hide(), up(), down())
require_once("../utils/utils.position.php");

// Проверяем, передано ли в параметре число
$_GET['id'] = intval($_GET['id']);
$_GET['id_parent'] = intval($_GET['id_parent']);
try
{
    uppest($_GET['id'],
        $tbl_news,
        "",
        "id");
    header("Location: index.php?".
        "id_parent=$_GET[id_parent]&page=$_GET[page]");

}
catch (ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>