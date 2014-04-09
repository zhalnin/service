<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 10:50 AM
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

// Защита от SQL-инъекции
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_position'] = intval($_GET['id_position']);

try
{
    // Извлекаем все изображения, принадлежащие разделу, и удаляем их
    $query = "SELECT * FROM $tbl_paragraph_image
            WHERE id_catalog=$_GET[id_catalog] AND
                    id_position = $_GET[id_position]";
    $img = mysql_query($query);
    if(!$img)
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при извлечении
            параметров изображения");
    }
    if(mysql_num_rows($img))
    {
        while($image = mysql_fetch_array($img))
        {
            if(file_exists("../../".$image['big']))
                @unlink("../../".$image['big']);
            if(file_exists("../../".$image['small']))
                @unlink("../../".$image['small']);
        }
    }

    // Формируем и выполняем SQL-запрос на удаление изображений
    $query = "DELETE FROM $tbl_paragraph_image
                WHERE id_catalog=".$_GET['id_catalog']." AND
                        id_position=".$_GET['id_position'];
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при удалении позиции");
    }

    // Формируем и выполняем SQL-запрос на удаление параграфов
    $query = "DELETE FROM $tbl_paragraph
                WHERE id_position=".$_GET['id_position']." AND
                      id_catalog=".$_GET['id_catalog'];
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при удалении элемента");
    }

    // Формируем и выполняем SQL-запрос на удаление элемента каталога
    $query = "DELETE FROM $tbl_position
                WHERE id_position=".$_GET['id_position']." AND
                        id_catalog=".$_GET['id_catalog'];
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при удалении позиции");
    }

//    // Формируем и выполняем SQL-запрос на удаление каталога
//    $query = "DELETE FROM $tbl_catalog
//                WHERE id_catalog=$_GET[id_catalog]
//                LIMIT 1";
//    if(!mysql_query($query))
//    {
//        throw new ExceptionMySQL(mysql_error(),
//            $query,
//            "Ошибка при удалении раздела");
//    }

    header("Location: index.php?id_parent=$_GET[id_catalog]&".
        "page=$_GET[page]");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>