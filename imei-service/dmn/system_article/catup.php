<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединения с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем блок управления позициями (show(), hide(), up(), down())
require_once("../utils/utils.position.php");

// Проверяем, передано лв в параметре чилс
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_parent'] = intval($_GET['id_parent']);
try
{
    up($_GET['id_catalog'],
        $tbl_catalog,
        "AND id_parent = $_GET[id_parent]",
        "id_catalog");
    header("Location: index.php?".
        "id_parent=$_GET[id_parent]&page=$_GET[page]");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>