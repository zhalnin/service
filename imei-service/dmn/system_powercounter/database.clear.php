<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 05.06.12
 * Time: 23:09
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
// Постраничная навигация
require_once("../utils/utils.pager.php");
// Формирование WHERE-условий
require_once("utils.where.php");
// Выполнение запроса
require_once("utils.query_result.php");

try
{
    if($_GET['part'] == actual)
    {
        $array = array($tbl_ip,
                      $tbl_pages,
                      $tbl_thits,
                      $tbl_refferer,
                      $tbl_searchquerys);
        foreach($array as $table)
        {
            // Удаляем записи из таблицы
            $query = "TRUNCATE $table";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при очистке
                                        таблицы");
            }
        }
    }
    if($_GET['part'] == archive)
    {
        $array = array($tbl_arch_hits,
                       $tbl_arch_ip,
                       $tbl_arch_clients,
                       $tbl_arch_robots,
                       $tbl_arch_refferer,
                       $tbl_arch_searchquery,
                       $tbl_arch_num_searchquery,
                       $tbl_arch_enterpoint,
                       $tbl_arch_deep,
                       $tbl_arch_time,
                       $tbl_arch_time_temp,
                       $tbl_arch_hits_week,
                       $tbl_arch_robots_week,
                       $tbl_arch_ip_week,
                       $tbl_arch_clients_week,
                       $tbl_arch_refferer_week,
                       $tbl_arch_searchquery_week,
                       $tbl_arch_num_searchquery_week,
                       $tbl_arch_enterpoint_week,
                       $tbl_arch_deep_week,
                       $tbl_arch_time_week,
                       $tbl_arch_hits_month,
                       $tbl_arch_robots_month,
                       $tbl_arch_ip_month,
                       $tbl_arch_clients_month,
                       $tbl_arch_refferer_month,
                       $tbl_arch_searchquery_month,
                       $tbl_arch_num_searchquery_month,
                       $tbl_arch_enterpoint_month,
                       $tbl_arch_deep_month,
                       $tbl_arch_time_month);
        foreach($array as $table)
        {
            // Удаляем записи из таблицы
            $query = "TRUNCATE $table";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при очистке
                                        таблицы");
            }
        }
    }
    header("Location: database.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionObject $exc)
{
    require("../utils/exception_object.php");
}
ob_get_flush();
?>