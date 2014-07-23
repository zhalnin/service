<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.04.12
 * Time: 20:47
 * To change this template use File | Settings | File Templates.
 */
ob_start();
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем FrameWork
require_once("../../config/class.config.dmn.php");

// Проверяем параметр id, предотвращая SQL-инъекцию
$_GET['id_news'] = intval($_GET['id_news']);

try
{
    // Если новостное сообщение содержит
    // изображение - удаляем его
    $query = "SELECT * FROM $tbl_news
            WHERE id_news=$_GET[id_news]";
    $new = mysql_query($query);
    if(!$new)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка удаление
                                новостного блока");
    }
    if(mysql_num_rows($new) > 0)
    {
        $news = mysql_fetch_array($new);
//        echo "<tt><pre>".print_r( $news, true )."</pre></tt>";
        if(file_exists("../../".$news['urlpict']))
        {
            @unlink("../../".$news['urlpict']);
        }
        if(file_exists("../../".$news['urlpict_s']))
        {
            @unlink("../../".$news['urlpict_s']);
        }
    }
    // Формируем и выполняем SQL-запрос
    // на удаление новостного блока из базы данных
    $query = "DELETE FROM $tbl_news
                WHERE id_news=$_GET[id_news]
                LIMIT 1";
    if(mysql_query($query))
    {
//        header("Location: index.php?page=$_GET[page]");
    }
    else
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка удаления
                                новостного блока");
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>
