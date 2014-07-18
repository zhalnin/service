<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/14/13
 * Time: 7:15 PM
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
$_GET['id_news'] = intval($_GET['id_news']);
$_GET['id_parent'] = intval($_GET['id_parent']);
try
{
    up($_GET['id_news'],
        $tbl_news,
        "",
        "id_news");
    header("Location: index.php?".
        "id_parent=$_GET[id_parent]&page=$_GET[page]");

}
catch (ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>