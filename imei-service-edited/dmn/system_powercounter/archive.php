<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.05.12
 * Time: 18:35
 * To change this template use File | Settings | File Templates.
 */
 
// Пытаемся снять ограничение на время выполнения архивации
@set_time_limit(0);
// Выставляем уровень обработки ошибок
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
// Функция для получения последнего заархивированного дня
require_once("utils.begin_day_arch.php");

// Библиотека функций архивации
require_once("utils.hits.php");
require_once("utils.ip.php");
require_once("utils.client.php");
require_once("utils.robots.php");
require_once("utils.enterpoints.php");
require_once("utils.deep.php");
require_once("utils.time.php");
require_once("utils.refferer.php");
require_once("utils.num_search.php");
require_once("utils.search.php");

try
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_ip, $tbl_arch_clients);
    // Количество дней, принадлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
        // Архивируем информацию в ежедневные таблицы
        archive_client              ($tbl_ip, $tbl_arch_clients);
        archive_hit_hosts           ($tbl_ip, $tbl_arch_hits);
        archive_robots              ($tbl_ip, $tbl_arch_robots);
        archive_num_searchquery     ($tbl_searchquerys, $tbl_arch_num_searchquery);
        archive_searchquery         ($tbl_searchquerys, $tbl_arch_searchquery);
        archive_refferer            ($tbl_refferer, $tbl_arch_refferer);
        archive_ip                  ($tbl_ip, $tbl_arch_ip);
        archive_enterpoints         ($tbl_ip, $tbl_pages, $tbl_arch_enterpoint);
        archive_time                ($tbl_ip, $tbl_arch_time, $tbl_arch_time_temp);
        archive_deep                ($tbl_ip, $tbl_arch_deep);

        // Удаляем старые записи
        $query = "SELECT MAX(putdate) FROM $tbl_arch_hits";
        $arh = mysql_query($query);
        if(!$arh) exit("Сбой при удалении старых записей");
        if(mysql_num_rows($arh) > 0)
        {
            $last_date_arch = mysql_result($arh, 0);
            $arr[] = "DELETE FROM $tbl_ip WHERE putdate <= '$last_date_arch'";
            $arr[] = "DELETE FROM $tbl_refferer WHERE putdate <= '$last_date_arch'";
            $arr[] = "DELETE FROM $tbl_searchquerys WHERE putdate <= '$last_date_arch'";
            foreach($arr as $query)
            {
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                                            $query,
                                            "Ошибка выполнения запроса");
                }
            }
        }

        // Архивируем информацию в еженедельные таблицы
        archive_client_week             ($tbl_arch_clients, $tbl_arch_clients_week);
        archive_hit_hosts_week          ($tbl_arch_hits, $tbl_arch_hits_week);
        archive_robots_week             ($tbl_arch_robots, $tbl_arch_robots_week);
        archive_num_searchquery_week    ($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_week);
        archive_refferer_week           ($tbl_arch_refferer, $tbl_arch_refferer_week);
        archive_ip_week                 ($tbl_arch_ip, $tbl_arch_ip_week);
        archive_searchquery_week        ($tbl_arch_searchquery, $tbl_arch_searchquery_week);
        archive_enterpoints_week        ($tbl_arch_enterpoint, $tbl_arch_enterpoint_week);
        archive_time_week               ($tbl_arch_time, $tbl_arch_time_week);
        archive_deep_week               ($tbl_arch_deep, $tbl_arch_deep_week);
//
//        // Архивируем информацию в ежемесячные таблицы
        archive_client_month             ($tbl_arch_clients, $tbl_arch_clients_month);
        archive_hit_hosts_month          ($tbl_arch_hits, $tbl_arch_hits_month);
        archive_robots_month             ($tbl_arch_robots, $tbl_arch_robots_month);
        archive_num_searchquery_month    ($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_month);
        archive_refferer_month           ($tbl_arch_refferer, $tbl_arch_refferer_month);
        archive_ip_month                 ($tbl_arch_ip, $tbl_arch_ip_month);
        archive_searchquery_month        ($tbl_arch_searchquery, $tbl_arch_searchquery_month);
        archive_enterpoints_month        ($tbl_arch_enterpoint, $tbl_arch_enterpoint_month);
        archive_time_month               ($tbl_arch_time, $tbl_arch_time_month);
        archive_deep_month               ($tbl_arch_deep, $tbl_arch_deep_month);
    }
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
?>

