<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01.05.12
 * Time: 19:12
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

// Проверяем GET-параметры, предотвращая SQL-инъекцию
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_catalog'] = intval($_GET['id_catalog']);

try
{
    // Формируем и выполняем SQL-запрос
    // на удаление позиции из базы данных
    $query = "DELETE FROM $tbl_cat_position
                WHERE id_position=$_GET[id_position]
                        LIMIT 1";
    if(mysql_query($query))
    {
        header("Location: position.php?".
                "id_catalog=$_GET[id_catalog]&".
                "page=$_GET[page]");
    }
    else
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при удалении
                                позиции");
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>
