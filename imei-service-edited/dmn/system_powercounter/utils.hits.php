<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.05.12
 * Time: 19:22
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);

// Функция суточной архивации
function archive_hit_hosts($tbl_ip, $tbl_arch_hits)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_ip, $tbl_arch_hits);
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
        for($i = $days - 1; $i >= 0; $i--)
        {

            $end = " putdate LIKE '".date("Y-m-d", $last_day - $i*24*3600)."%'";
            // Общее количество хитов за сутки
            $query_total_hit = "SELECT COUNT(*) FROM $tbl_ip WHERE $end";
            // Засчитанные хиты за сутки
            $query_hit = "SELECT COUNT(*) FROM $tbl_ip WHERE systems != 'none' AND $end";
            // Подсчитывем число IP-адресов (хостов) за сутки
            $query_total_host = "SELECT COUNT(DISTINCT ip) FROM $tbl_ip WHERE $end";
            // Подсчитывем число чистых IP-адресов (хостов) за сутки
            $query_host = "SELECT COUNT(DISTINCT ip) FROM $tbl_ip WHERE
                            systems != 'none' AND
                            systems != 'robot_yandex' AND
                            systems != 'robot_google' AND
                            systems != 'robot_rambler' AND
                            systems != 'robot_aport' AND
                            systems != 'robot_msnbot' AND $end";
            // Формируем запрос для архивации хитов и хостов в таблицу $tbl_arch_hits
            $totalhists = query_result($query_total_hit);
            $hists      = query_result($query_hit);
            $totalhosts = query_result($query_total_host);
            $hosts      = query_result($query_host);
            $sql_hits[] = "(NULL,
                            '".date("Y-m-d", $last_day - $i*24*3600)."',
                            $totalhosts,
                            $hosts,
                            $totalhists,
                            $hists)";
        }
        if(!empty($sql_hits))
        {
            $query = "INSERT INTO $tbl_arch_hits VALUES".implode(",",$sql_hits);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                    "Ошибка суточной архивации - archive_hit_hosts()");
            }
        }
    }
}
// Функция недельной архивации
function archive_hit_hosts_week($tbl_arch_hits, $tbl_arch_hits_week)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_hits, $tbl_arch_hits_week, 'putdate_begin');
    // Вычисляем сколько недель прошло с даты последней архивации
     $week = floor(($last_day - $begin_day)/24/60/60/7);
    // Если прошло больше недели - архивируем данные
    if($week > 0)
    {
        // $begin_day - дата последней архивации... - смотрим далеко ли до
        // конца недели (воскресенье). Интервал включаем данные с Понедельника (1)
        // до воскресенья (0).
        $weekday = date('w', $begin_day);

        // Текущему времени приравниваем начальную точку
        $current_date = $begin_day;
        while(floor(($last_day - $current_date)/24/60/60/7))
        {
            $end = "FROM $tbl_arch_hits
                    WHERE putdate > '".date("Y-m-d", $current_date)."' AND
                          putdate <= '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."'";
            // Подсчитываем количество обращений за неделю
            $total_hit      = query_result("SELECT SUM(hits_total) $end");
            $hit            = query_result("SELECT SUM(hits) $end");
            $total_host     = query_result("SELECT SUM(hosts_total) $end");
            $host           = query_result("SELECT SUM(host) $end");
            $sql_hits[]     = "(NULL,
                                '".date("Y-m-d", $current_date)."',
                                '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."',
                                $total_host, $host, $total_hit, $hit)";
            // Увеличиваем текущее время до следующей недели
            $current_date += (7 - $weekday)*24*3600;
            // Далее идут циклы по целой неделе
            $weekday = 0;
        }

        // Формируем окончательные запросы и выполняем их
        if(!empty($sql_hits))
        {
            $query = "INSERT INTO $tbl_arch_hits_week VALUES".implode(",",$sql_hits);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка недельной архивации - archive_hit_hosts_week()");
            }
        }
    }
}

// Функция архивации хостов, хитов в недельные таблицы
function archive_hit_hosts_month($tbl_arch_hits, $tbl_arch_hits_month)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y")) + 2;
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_hits, $tbl_arch_hits_month);
    // Вычисляем сколько недель прошло с даты последней архивации
    $month = (floor(date("Y", $last_day) - date("Y", $begin_day)))*12 +
            floor(date("m", $last_day) - date("m", $begin_day));
    // Если прошло больше месяца - архивируем данные
    if($month > 0)
    {
        // Архивировать данные по всем месяцам, по которым архивация
        // не проводилась
        for($i = date("Y", $begin_day)*12 + date("m", $begin_day); $i < date("Y", $last_day)*12 + date("m", $last_day); $i++)
        {
            $year = (int)($i/12);
            $month = ($i%12);
            if($month == 0)
            {
                $year--;
                $month = 12;
            }
            $end = "FROM $tbl_arch_hits
                    WHERE YEAR(putdate) = $year AND
                          MONTH(putdate) = '".sprintf("%02d", $month)."'";
            // Подсчитываем количество обращений за месяц
            $total_hit  = query_result("SELECT SUM(hits_total) $end");
            $hit        = query_result("SELECT SUM(hits) $end");
            $total_host = query_result("SELECT SUM(hosts_total) $end");
            $host       = query_result("SELECT SUM(host) $end");
            // Формируем запрос для архивной таблицы
            $sql_hits[] = "(NULL,
                            '$year-".sprintf("%02d",$month)."-01',
                            $total_host, $host, $total_hit, $hit)";
        }
        // Формируем окончательные запросы и выполняем их
        if(!empty($sql_hits))
        {
            $query = "INSERT INTO $tbl_arch_hits_month VALUES".implode(",", $sql_hits);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка месячной архивации - archive_hit_hosts_month()");
            }
        }
    }
}
?>
