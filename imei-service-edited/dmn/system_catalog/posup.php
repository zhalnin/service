<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01.05.12
 * Time: 20:29
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
ob_start();

// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем управление позициями
require_once("../utils/utils.position.php");

// Защита от SQL-инъекции
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_catalog'] = intval($_GET['id_catalog']);

try
{
    up($_GET['id_position'], $tbl_cat_position,
        " AND id_catalog=$_GET[id_catalog]");
    header("Location: position.php?".
            "id_catalog=$_GET[id_catalog]&page=$_GET[page]");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>