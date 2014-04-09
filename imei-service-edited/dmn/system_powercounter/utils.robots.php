<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07.06.12
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);
// Функция архивации клиентов в суточные таблицы
function archive_robots($tbl_ip, $tbl_arch_robots)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_ip, $tbl_arch_robots);
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
        for($i = $days - 1; $i >= 0; $i--)
        {
            $begin = "SELECT COUNT(*)
                      FROM $tbl_ip
                      WHERE putdate LIKE '".date("Y-m-d", $last_day - $i*24*3600)."%' AND
                      systems = ";
            // Подсчитываем количество обращений за сутки
            $systems_yandex     = query_result("$begin 'robot_yandex'");
            $systems_gogle      = query_result("$begin 'robot_google'");
            $systems_rambler    = query_result("$begin 'robot_rambler'");
            $systems_aport      = query_result("$begin 'robot_aport'");
            $systems_msn        = query_result("$begin 'robot_msnbot'");
            $systems_none       = query_result("$begin 'none'");

            // Формируем запрос для архивной таблицы
            $sql_robots[] = "(NULL,
                                '".date("Y-m-d", $last_day - $i*24*3600)."',
                                $systems_yandex,
                                $systems_rambler,
                                $systems_gogle,
                                $systems_aport,
                                $systems_msn,
                                $systems_none)";
        }
        if(!empty($sql_robots))
        {
            $query = "INSERT INTO $tbl_arch_robots VALUES ".implode(",", $sql_robots);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка суточной архивации - archive_robots()");
            }
        }
    }
}
// Функция архивации роботов в недельные таблицы
function archive_robots_week($tbl_arch_robots, $tbl_arch_robots_week)
{
// Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_robots, $tbl_arch_robots_week, 'putdate_begin');
    // Вычисляем сколько недель прошло с даты последней архивации
    $week = floor(($last_day - $begin_day)/24/60/60/7);
    // Если прошло больше недели - архивируем данные
    if($week > 0)
    {
        // $begin_day - дата последней архивации... - смотрим делеко ли до
        // конца недели(воскресенье). Интервал включаем данные с Понедельника(1)
        // до воскресенья(0).
        $weekday = date('w', $begin_day);
        // Текущему времени приравниваем начальную точку
        $current_date = $begin_day;
        while(floor(($last_day - $current_date)/24/60/60/7))
        {
            $end = "FROM $tbl_arch_robots
                    WHERE putdate > '".date("Y-m-d", $current_date)."' AND
                          putdate <= '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."'";

            // Подсчитываем количество обращений за неделю
            $systems_yandex     = query_result("SELECT SUM(yandex) $end");
            $systems_gogle      = query_result("SELECT SUM(google) $end");
            $systems_rambler    = query_result("SELECT SUM(rambler) $end");
            $systems_aport      = query_result("SELECT SUM(aport) $end");
            $systems_msn        = query_result("SELECT SUM(msn) $end");
            $systems_none       = query_result("SELECT SUM(none) $end");

            // Формируем запрос для архивной таблицы
            $sql_robots[] = "(NULL,
                                '".date("Y-m-d", $current_date)."',
                                '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."',
                                $systems_yandex,
                                $systems_gogle,
                                $systems_rambler,
                                $systems_aport,
                                $systems_msn,
                                $systems_none)";
            // Увеличиваем текущее время до следующей недели
            $current_date += (7 - $weekday)*24*3600;
            // Далее идут циклы по целой неделе
            $weekday = 0;
        }
        if(!empty($sql_robots))
        {
            $query = "INSERT INTO $tbl_arch_robots_week VALUES ".implode(",", $sql_robots);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка недельной архивации - archive_robots_week()");
            }
        }
    }
}

// Функция архивации роботов поисковых систем в месячные таблицы
function archive_robots_month($tbl_arch_robots, $tbl_arch_robots_month)
{
// Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y")) + 2;
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_robots, $tbl_arch_robots_month);
    // Вычисляем сколько недель прошло с даты последней архивации
    $month = (floor(date("Y",$last_day) - date("Y",$begin_day)))*12 +
            floor(date("m",$last_day) - date("m",$begin_day));
    // Если прошло больше месяца - архивируем данные
    if($month > 0)
    {
        // Архивируем данные по всем месяцам, по которым архивация
        // не проводилась
        for($i = date("Y",$begin_day)*12 + date("m",$begin_day); $i < date("Y",$last_day)*12 + date("m",$last_day); $i++)
        {
            $year = (int)($i/12);
            $month = ($i%12);
            if($month == 0)
            {
                $year--;
                $month = 12;
            }

            $end = "FROM $tbl_arch_robots
                    WHERE YEAR(putdate) = $year AND
                          MONTH(putdate) = '".sprintf("%02d",$month)."'";

            // Подсчитываем количество обращений за месяц
            $systems_yandex     = query_result("SELECT SUM(yandex) $end");
            $systems_gogle      = query_result("SELECT SUM(google) $end");
            $systems_rambler    = query_result("SELECT SUM(rambler) $end");
            $systems_aport      = query_result("SELECT SUM(aport) $end");
            $systems_msn        = query_result("SELECT SUM(msn) $end");
            $systems_none       = query_result("SELECT SUM(none) $end");

            // Формируем запрос для архивной таблицы
            $sql_robots[] = "(NULL,
                                '$year-".sprintf("%02d",$month)."-01',
                                $systems_yandex,
                                $systems_gogle,
                                $systems_rambler,
                                $systems_aport,
                                $systems_msn,
                                $systems_none)";
        }
        if(!empty($sql_robots))
        {
            $query = "INSERT INTO $tbl_arch_robots_month VALUES ".implode(",", $sql_robots);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка месячной архивации - archive_robots_month()");
            }
        }
    }
}
?>