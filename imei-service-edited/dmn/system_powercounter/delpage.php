<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 05.06.12
 * Time: 19:44
 * To change this template use File | Settings | File Templates.
 */
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
require_once("config.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем блок отображения текста в окне браузера
require_once("../utils/utils.print_page.php");
// Формирование WHERE-условий
require_once("utils.where.php");
// Выполнение запроса
require_once("utils.query_result.php");

try
{
    // Защита от SQL-инъекции
    $_GET['id_page'] = intval($_GET['id_page']);
    // Удаляем записи из таблицы $tbl_ip
    $query = "DELETE FROM $tbl_ip
                WHERE id_page=$_GET[id_page]";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при очистке
                                таблицы посещений");
    }
    // Удаляем записи из таблиц $tbl_refferer
    $query = "DELETE FROM $tbl_refferer
                WHERE id_page=$_GET[id_page]";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при очистке
                                таблицы рефереров");
    }
    // Удаляем записи зи таблицы $tbl_pages
    $query = "DELETE FROM $tbl_pages
                WHERE id_page=$_GET[id_page]";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при очистке
                                таблицы страниц");
    }
    header("Location: index.php");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}
catch(ExceptionObject $exc)
{
  require("../utils/exception_object.php");
}
ob_get_flush();
?>