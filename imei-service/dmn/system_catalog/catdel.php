<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.04.12
 * Time: 21:22
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

try
{
    // Удаляем каталог со всеми вложенными подкаталогами
    del_catalog($_GET['id_catalog'],
                $tbl_cat_catalog,
                $tbl_cat_position);
    // Осуществляем переадресацию на главную страницу
    header("Location: index.php?".
            "id_parent=$_GET[id_parent]&page=$_GET[page]");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}

// Рекурсивная функция удаления каталога с первичным ключом $id_catalog
function del_catalog($id_catalog,
                    $tbl_cat_catalog,
                    $tbl_cat_position)
{
    echo $id_catalog."<br/>";
    echo "$tbl_cat_catalog<br/>";
    echo "$tbl_cat_position<br/>";
    // Преобразуем параметр id_catalog к целому значению
    $id_catalog = intval($id_catalog);
    // Осуществляем рекурсивный спуск, для того,
    // чтобы удалить все вложенные подкаталоги
    $query = "SELECT * FROM $tbl_cat_catalog
                WHERE id_parent = $id_catalog";
    $cat = mysql_query($query);
    if(!$cat)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка удаления
                                подкаталога");
    }
    if(mysql_num_rows($cat))
    {
        while($catalog = mysql_fetch_array($cat))
        {
            if(file_exists("../../".$catalog['urlpict'])){
                echo "ey<br/>";
                @unlink("../../".$catalog['urlpict']);
            }
            if(file_exists("../../".$catalog['rounded_flag'])){
                echo "kkk<br/>";
                @unlink("../../".$catalog['rounded_flag']);
            }
            del_catalog($catalog['id_catalog'],
                        $tbl_cat_catalog,
                        $tbl_cat_position);

        }

    }
    $querys = "SELECT * FROM $tbl_cat_catalog
                WHERE id_catalog = $id_catalog";
    $cats = mysql_query($querys);
    if(!$cats){
        throw new ExceptionMySQL(mysql_error(),
                                $querys,
                                "Ошибка удаления
                                каталога");
    }
    $catalogs = mysql_fetch_array($cats);
    if(file_exists("../../".$catalogs['urlpict'])){
        @unlink("../../".$catalogs['urlpict']);
    }
    if(file_exists("../../".$catalogs['rounded_flag'])){
        @unlink("../../".$catalogs['rounded_flag']);
    }

    // Удаляем товарные позиции, принадлежащие каталогу
    $query = "DELETE FROM $tbl_cat_position
                WHERE id_catalog = $id_catalog";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при удалении
                                 подкаталога");
    }
    // Удаляем каталог с первичным ключом $id_catalog
    $query = "DELETE FROM $tbl_cat_catalog
                WHERE id_catalog = $id_catalog";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при удалении
                                подкаталога");
    }
}
ob_get_flush();
?>