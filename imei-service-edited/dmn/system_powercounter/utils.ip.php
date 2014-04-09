<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 06.06.12
 * Time: 23:23
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);

// Функция суточной архивации
function archive_ip($tbl_ip, $tbl_arch_ip)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_ip, $tbl_arch_ip);
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
        // Число архивируемых IP-адресов из таблицы
        $ip_number = IP_NUMBER;
        for($i = $days - 1; $i >= 0; $i--)
        {
            // Подсчитываем количество обращений за сутки
            $query = "SELECT ip, COUNT(ip) AS total FROM $tbl_ip
                      WHERE putdate LIKE '".date("Y-m-d", $last_day - $i*24*3600)."%' 
                      GROUP BY ip
                      ORDER BY total DESC
                      LIMIT $ip_number";
            $ipc = mysql_query($query);
            if(!$ipc)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка суточной архивации - archive_ip()");
            }
            if(mysql_num_rows($ipc))
            {
                while($ip = mysql_fetch_array($ipc))
                {
                    $sql_ip[] = "(NULL,
                                '".date("Y-m-d", $last_day - $i*24*3600)."',
                                '$ip[ip]',
                                $ip[total])";
                }
            }
        }
        if(!empty($sql_ip))
        {
            $query = "INSERT INTO $tbl_arch_ip VALUES".implode(",",$sql_ip);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                    "Ошибка суточной архивации - archive_ip()");
            }
        }
    }
}
// Функция архивации за неделю
function archive_ip_week($tbl_arch_ip, $tbl_arch_ip_week)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_ip, $tbl_arch_ip_week, 'putdate_begin');
    // Вычисляем сколько недель прошло с даты последней архивации
    $week = floor(($last_day - $begin_day)/24/60/60/7);
    // Если прошло больше недели - архивируем данные
    if($week > 0)
    {
        // $begin_day - дата последней архивации... - смотрим далеко ли до
        // конца недели(воскресенье). Интервал включает данные с Понедельника(1)
        // до воскресенья(0).
        $weekday = date('w', $begin_day);
        $ip_number = IP_NUMBER;
        // Текущему времени приравниваем начальную точку
        $current_date = $begin_day;
        while(floor(($last_day - $current_date)/24/60/60/7))
        {
            $where = "WHERE putdate > '".date("Y-m-d", $current_date)."' AND
                            putdate <= '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."'";
            // Извлекаем $ip_number самых активных IP-адресов за неделю
            $query = "SELECT ip, SUM(total) AS total FROM $tbl_arch_ip
                        $where
                        GROUP BY ip
                        ORDER BY total DESC
                        LIMIT $ip_number";
            $ipc = mysql_query($query);
            if(!$ipc)
            {
                throw new ExceptionMySQL(mysql_error(),
                                         $query,
                                        "Ошибка недельной архивации - archive_refferer_week()");
            }
            if(mysql_num_rows($ipc))
            {
                while($ip = mysql_fetch_array($ipc))
                {
                    $sql_ip[] = "(NULL,
                                '".date("Y-m-d", $current_date)."',
                                '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."',
                                $ip[ip],
                                $ip[total])";
                }
            }
            // Увеличиваем текущее время до следующей недели
            $current_date += (7 - $weekday)*24*3600;
            $weekday = 0;
            // Далее идут циклы по целой неделе
        }
        if(!empty($sql_ip))
        {
            $query = "INSERT INTO $tbl_arch_ip_week VALUES".implode(",",$sql_ip);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка недельной архивации - archive_ip_week()");
            }
        }
    }
}
// Функция месячной архивации
function archive_ip_month($tbl_arch_ip, $tbl_arch_ip_month)
{
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y")) + 2;
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_ip, $tbl_arch_ip_month);
    // Вычисляем сколько недель прошло с даты последней архивации
    $month = (floor(date("Y",$last_day) - date("Y",$begin_day)))*12 +
            floor(date("m",$last_day) - date("m",$begin_day));
    // Если прошло больше месяца - архивируем данные
    if($month > 0)
    {
        $ip_number = IP_NUMBER;
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
            $where = "WHERE YEAR(putdate) = $year AND
                            MONTH(putdate) = '".sprintf("%02d",$month)."'";
            // Извлекаем $ip_number самых активных IP-адресов за месяц
            $query = "SELECT ip, SUM(total) AS total FROM $tbl_arch_ip
                        $where
                        GROUP BY ip
                        ORDER BY total DESC
                        LIMIT $ip_number";
            $ipc = mysql_query($query);
            if(!$ipc)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка месячной архивации - archive_ip_month()");
            }
            if(mysql_num_rows($ipc))
            {
                while($ip = mysql_fetch_array($ipc))
                {
                    $sql_ip[] = "(NULL,
                                '$year-".sprintf("%02d",$month)."-01',
                                $ip[ip],
                                $ip[total])";
                }
            }
        }
        if(!empty($sql_ip))
        {
            $query = "INSERT INTO $tbl_arch_ip_month VALUES".implode(",",$sql_ip);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка месячной архивации - archive_ip_month()");
            }
        }
    }
}

?>