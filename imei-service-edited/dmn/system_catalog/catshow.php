<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.04.12
 * Time: 22:55
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
// Блок управления элементами (show(), hide(), up(), down())
require_once("../utils/utils.position.php");

// Защита от SQL-инъекции
$_GET['id_catalog'] = intval($_GET['id_catalog']);

try
{
    // Формируем и выполняем SQL-запрос для отображения раздела
    show($_GET['id_catalog'], $tbl_cat_catalog, "", "id_catalog");
    header("Location: index.php?".
            "id_parent=$_GET[id_parent]&page=$_GET[page]");
}
catch( ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>