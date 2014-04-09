<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 2:32 PM
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
// Подключаем блок управления позициями (show(), hide(), up(), down() )
require_once("../utils/utils.position.php");

// Защита от SQL-инъекции
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_paragraph'] = intval($_GET['id_paragraph']);

try
{
    // Формируем и выполняем SQL-запрос на сокрытие позиции
    hide($_GET['id_paragraph'],
        $tbl_paragraph,
        " AND id_position = $_GET[id_position]
               AND id_catalog = $_GET[id_catalog]",
        "id_paragraph");
    header("Location: paragraph.php?".
        "id_position=$_GET[id_position]&".
        "id_catalog=$_GET[id_catalog]&".
        "page=$_GET[page]");
}
catch(ExceptionMySQL $exc)
{
    require_once("../utils/exception_mysql.php");
}
ob_get_flush();
?>