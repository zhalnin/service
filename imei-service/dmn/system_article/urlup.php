<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 10:16 AM
 * To change this template use File | Settings | File Templates.
 */

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Блок управления позициями( show(), hide(), up(), down())
require_once("../utils/utils.position.php");

// Проверяем, передано ли в параметре число
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_position'] = intval($_GET['id_position']);

try
{
    up($_GET['id_position'],
        $tbl_position,
        " AND id_catalog=$_GET[id_catalog]");
    header("Location: index.php?".
        "id_parent=$_GET[id_catalog]&page=$_GET[page]");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>