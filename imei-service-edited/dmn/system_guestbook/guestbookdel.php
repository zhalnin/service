<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 05/05/14
 * Time: 20:38
 */
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем FrameWork
require_once("../../config/class.config.dmn.php");

// Проверяем параметр id_news, предотвращая SQL-инъекцию
$_GET['id'] = intval($_GET['id']);

try {
    // Если новостное сообщение содержит
    // изображение - удаляем его
    $query = "SELECT * FROM system_guestbook
            WHERE id=$_GET[id]";
    $guestbook = mysql_query($query);
    if( ! $guestbook )  {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка удаление
            поста гостевой книги");
    }

    // Формируем и выполняем SQL-запрос
    // на удаление новостного блока из базы данных
    $query = "DELETE FROM system_guestbook
                WHERE id=$_GET[id]
                LIMIT 1";
    if( mysql_query( $query ) ) {
        header( "Location: index.php?page=$_GET[page]" );
    }  else {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка удаления
            поста гостевой книги");
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}