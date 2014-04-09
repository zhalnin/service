<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.04.12
 * Time: 21:02
 * To change this template use File | Settings | File Templates.
 */
ob_start();
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем FrameWork
require_once("../../config/class.config.dmn.php");
// Проверяем параметр id_news, предотвращая SQL-инъекцию
$_GET['id_news'] = intval($_GET['id_news']);

// Скрываем новость
try
{
    $query = "UPDATE $tbl_news SET hide='hide'
                WHERE id_news=".$_GET['id_news'];
    if(mysql_query($query))
    {
        header("Location: index.php?page=$_GET[page]");
    }
    else
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении
                                к блоку новостей");
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>
