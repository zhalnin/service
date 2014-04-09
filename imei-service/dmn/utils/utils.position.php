<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 15.04.12
 * Time: 21:35
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);
// Функции для операций сокрытия, отображения, перемещения на одну позицию вверх
// и одну позицию вниз

// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Отображение позиции
function show($id_position,
                $tbl_name,
                $where = "",
                $fld_name = "id_position")
{
    // Проверяем GET-параметр, предотвращая SQL-инъекцию
    $id_position = intval($id_position);

    // Отображаем позицию
    $query = "UPDATE $tbl_name SET hide='show'
                WHERE $fld_name=$id_position $where";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при отображении
                                позиции");
    }
}

// Сокрытие позиции
function hide($id_position,
                $tbl_name,
                $where = "",
                $fld_name = "id_position")
{
    // Проверяем GET-параметр, предотвращая SQL-инъекцию
    $id_position = intval($id_position);

    // Скрываем позицию
    $query = "UPDATE $tbl_name SET hide='hide'
                WHERE $fld_name=$id_position $where";
    if(!mysql_query($query))
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при сокрытии
                                позиции");
    }
}

// Подъем блока на одну позицию вверх
function up($id_position,
            $tbl_name,
            $where = "",
            $fld_name = 'id_position')
{
    // Извлекаем текущую позицию
    $query = "SELECT pos FROM $tbl_name
                WHERE $fld_name=$id_position
                LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при извлечении
                                текущей позиции");
    }
    if(mysql_num_rows($pos))
    {
        $pos_current = mysql_result($pos, 0);
    }
    // Извлекаем предыдущую позицию
    $query = "SELECT pos FROM $tbl_name
            WHERE pos < $pos_current $where
            ORDER BY pos DESC
            LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(myslq_error(),
                                $query,
                                "Ошибка при извлечении
                                предыдущей позиции");
    }
    if(mysql_num_rows($pos))
    {
        $pos_preview = mysql_result($pos, 0);

        // Меняем местами текущую и предыдущую позиции
        $query = "UPDATE $tbl_name
                    SET pos = $pos_current + $pos_preview - pos
                    WHERE pos IN($pos_current, $pos_preview) $where";
        if(!mysql_query($query))
        {
            throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                    "Ошибка изменения
                                    позиции");
        }
    }
}

// Подъем блока на самую верхнюю позицию
function uppest($id_position,
            $tbl_name,
            $where = "",
            $fld_name = 'id_position')
{
    // Извлекаем текущую позицию
    $query = "SELECT pos FROM $tbl_name
                WHERE $fld_name=$id_position
                LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при извлечении
            текущей позиции");
    }
    if(mysql_num_rows($pos))
    {
        $pos_current = mysql_result($pos, 0);
    }
    // Извлекаем самую низкую позицию, которая не отображается
    $query = "SELECT MIN(pos) FROM $tbl_name
            WHERE pos < $pos_current $where
            AND hide='hide'
            ORDER BY pos DESC
            LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(myslq_error(),
            $query,
            "Ошибка при извлечении
            предыдущей позиции");
    }
    if(mysql_num_rows($pos))
    {
        $pos_preview = mysql_result($pos, 0);

        if( !empty( $pos_preview ) ) {
            // Меняем местами текущую и самую низкую позиции
            $query = "UPDATE $tbl_name
                        SET pos = $pos_current + $pos_preview - pos
                        WHERE pos IN($pos_current, $pos_preview) $where";
            if(!mysql_query($query))
            {

                throw new ExceptionMySQL(mysql_error(),
                    $query,

                    "Ошибка изменения
                    позиции");
            }

        }

    }
}

 // Опускание блока на одну позицию вниз
function down($id_position,
                $tbl_name,
                $where = "",
                $fld_name = "id_position")
{
    // Извлекаем текущую позицию
    $query = "SELECT pos FROM $tbl_name
                WHERE $fld_name=$id_position
                LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при извлечении
                                текущей позиции");
    }
    if(mysql_num_rows($pos))
    {

        $pos_current = mysql_result($pos, 0);
    }
    $query = "SELECT pos FROM  $tbl_name
                WHERE pos > $pos_current $where
                ORDER BY pos
                LIMIT 1";
    $pos = mysql_query($query);

    if(!$pos)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при излечении
                                следующей позиции");
    }
    if(mysql_num_rows($pos))
    {
        $pos_next = mysql_result($pos, 0);
        // Меняем местами текущую и следующую позиции
        $query = "UPDATE $tbl_name
                SET pos = $pos_next + $pos_current - pos
                WHERE pos IN ($pos_next, $pos_current) $where";
        if(!mysql_query($query))
        {
            throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                    "Ошибка изменения
                                    позиции");
        }
    }
}
?>