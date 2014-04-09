<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17.06.12
 * Time: 21:20
 * To change this template use File | Settings | File Templates.
 */
 
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

// Проводим архивацию
require_once("archive.php");

// Тело письма
$body = "";

// Текущая дата
$year_month_day = time();
$year_month_day_1 = time() - 3600*24;
$year = date('Y');
$month = date('n');
$day = date('j');

try
{
    // Извлекаем число хитов и хостов за прошедшие сутки
    $query = "SELECT * FROM $tbl_arch_hits
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $arch = mysql_query($query);
    if(!$arch)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
    $arch_hits = mysql_fetch_array($arch);

    // Извлекаем число хитов и хостов за прошедшие сутки
    $query = "SELECT * FROM $tbl_arch_clients
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $cnt = mysql_query($query);
    if(!$cnt)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
    $arch_clients = mysql_fetch_array($cnt);

    // Извлекаем IP_NUMBER наиболее активных IP-адресов
    $query = "SELECT INET_NTOA(ip) AS ip, total  FROM $tbl_arch_ip
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $ipt = mysql_query($query);
    if(!$ipt)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }

//    $arch_ip = mysql_fetch_array($ipt);


    // Извлекаем число обращений из поисковых систем
    $query = "SELECT * FROM $tbl_arch_num_searchquery
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $qnm = mysql_query($query);
    if(!$qnm)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
    $arch_num_searchquery = mysql_fetch_array($qnm);

    // Извлекаем YANDEX_NUMBER ниболее распространенных запросов Yandex
    $query = "SELECT * FROM $tbl_arch_searchquery
              WHERE searches = 'yandex' AND
                    YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $ynd = mysql_query($query);
    if(!$ynd)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_yandex = mysql_fetch_array($ynd);

    // Извлекаем RAMBLER_NUMBER ниболее распространенных запросов Rambler
    $query = "SELECT * FROM $tbl_arch_searchquery
              WHERE searches = 'rambler' AND
                    YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $rbl = mysql_query($query);
    if(!$rbl)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_rambler = mysql_fetch_array($rbl);

    // Извлекаем APORT_NUMBER ниболее распространенных запросов Aport
    $query = "SELECT * FROM $tbl_arch_searchquery
              WHERE searches = 'aport' AND
                    YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $apt = mysql_query($query);
    if(!$apt)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_aport= mysql_fetch_array($apt);

    // Извлекаем GOOGLE_NUMBER ниболее распространенных запросов Google
    $query = "SELECT * FROM $tbl_arch_searchquery
              WHERE searches = 'google' AND
                    YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $gog = mysql_query($query);
    if(!$gog)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_google = mysql_fetch_array($gog);

    // Извлекаем MSN_NUMBER ниболее распространенных запросов MSN
    $query = "SELECT * FROM $tbl_arch_searchquery
              WHERE searches = 'msn' AND
                    YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $ms = mysql_query($query);
    if(!$ms)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_msn = mysql_fetch_array($ms);

    // Извлекаем REFFERER_NUMBER наиболее распространенных рефереров
    $query = "SELECT * FROM $tbl_arch_refferer
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $ref = mysql_query($query);
    if(!$ref)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_refferer = mysql_fetch_array($ref);

    // Извлекаем ENTERPOINT_NUMBER наиболее распространенных точек входа
    $query = "SELECT * FROM $tbl_arch_enterpoint
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $enp = mysql_query($query);
    if(!$enp)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
//    $arch_enpoint = mysql_fetch_array($enp);

    // Извлекаем "глубину посещения"
    $query = "SELECT * FROM $tbl_arch_deep
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $dep = mysql_query($query);
    if(!$dep)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
    $arch_deep = mysql_fetch_array($dep);

    // Извлекаем "время сеанса"
    $query = "SELECT * FROM $tbl_arch_time
              WHERE YEAR(putdate) = ".date('Y', $year_month_day_1)." AND
                    MONTH(putdate) = ".date('n', $year_month_day_1)." AND
                    DAYOFMONTH(putdate) = ".(date('j', $year_month_day_1));
    $tim = mysql_query($query);
    if(!$tim)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при формировании суточного почтового отчета");
    }
    $arch_time = mysql_fetch_array($tim);

    $header = "Content-Type: text/html; charset=utf-8\r\n\r\n";

    $body .=    "<html>\r\n".
                "<head>\r\n".
                "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n";
                "<title></title>\r\n".
                "<style>\r\n".
                " body, table {font-family: Arial, Helvetica, sans-serif; font-size: 12px}\r\n".
                " .namepage{text-transform: uppercase; font-size: 140%; color: #651B17; border-bottom-style: sloid; border-width: 5px; border-color: #C3C3C3; padding: 0px 0px 10px 0px}\r\n".
                " .title{font-size: 120%; color: #000000; margin: 20px 0px 10px 0px}\r\n".
                " table{width: 60%; margin: 10px 0px 10px 0px; background-color: #F9F9F9; border-top-style: solid; border-right-style: solid; border-width: 1px; border-top-width: 2px; border-color: #6C6C6C; border-top-color: #B9B9B9}\r\n".
                " table.long{width: 80%;}\r\n".
                " table.short{width: 40%;}\r\n".
                " table td{padding: 2px 5px 2px 5px; border-left-style: solid; border-bottom-style: solid; border-width: 1px; border-color: #969696}\r\n".
                " table tr.header td{padding: 2px 5px 2px 5px; background-color: #212B84; color: #FFFFFF; font-weight: bold; text-align: center}\r\n".
                " a {color: #173D76}\r\n".
                " a:hover{color: #2156A6}\r\n".
                "</style>\r\n".
                "<header>\r\n".
                "<body>";
    $body .= "<h2 class=namepage>Суточная статистика за ".(date('j') - 1).".".date('m').".".date('Y')."</h2>";

    $body .= "<h4 class=title>Общая статистика за сутки</h4>\r\n";
    $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
    $body .= "<tr class=header><td>Параметр</td><td>Зачение</td></tr>\r\n";
    $body .= "<tr><td>Засчитанные хосты</td><td>$arch_hits[host]</td></tr>\r\n";
    $body .= "<tr><td>Общее число хостов</td><td>$arch_hits[hosts_total]</td></tr>\r\n";
    $body .= "<tr><td>Засчитанные хиты</td><td>$arch_hits[hits]</td></tr>\r\n";
    $body .= "<tr><td>Общее число хитов</td><td>$arch_hits[hits_total]</td></tr>\r\n";
    $body .= "</table>\r\n";

    $body .= "<h4 class=title>Распределение по операционным системам</h4>\r\n";
    $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
    $body .= "<tr class=header><td>Операционная система</td><td>Обращений</td></tr>\r\n";
    $body .= "<tr><td>Windows</td><td>$arch_clients[systems_windows]</td></tr>\r\n";
    $body .= "<tr><td>Unix</td><td>$arch_clients[systems_unix]</td></tr>\r\n";
    $body .= "<tr><td>Macintosh</td><td>$arch_clients[systems_macintosh]</td></tr>\r\n";
    $body .= "<tr><td>Не определено</td><td>$arch_clients[systems_none]</td></tr>\r\n";
    $body .= "</table>\r\n";

    $body .= "<h4 class=title>Распределение по браузерам</h4>\r\n";
    $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
    $body .= "<tr class=header><td>Браузер</td><td>Обращений</td></tr>\r\n";
    $body .= "<tr><td>Internet Explorer</td><td>$arch_clients[browsers_msie]</td></tr>\r\n";
    $body .= "<tr><td>Opera</td><td>$arch_clients[browsers_opera]</td></tr>\r\n";
    $body .= "<tr><td>Netscape Navigator</td><td>$arch_clients[browsers_netscape]</td></tr>\r\n";
    $body .= "<tr><td>FireFox</td><td>$arch_clients[browsers_firefox]</td></tr>\r\n";
    $body .= "<tr><td>MyIE</td><td>$arch_clients[browsers_myie]</td></tr>\r\n";
    $body .= "<tr><td>Mozilla</td><td>$arch_clients[browsers_mozilla]</td></tr>\r\n";
    $body .= "<tr><td>Не определено</td><td>$arch_clients[browsers_none]</td></tr>\r\n";
    $body .= "</table>\r\n";

    if(mysql_num_rows($ipt) > 0)
    {
        $body .= "<h4 class=title>".IP_NUMBER." наиболее активных IP-адресов</h4>\r\n";
        $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>IP-адрес</td><td>Обращений</td></tr>\r\n";
        while($arch_ip = mysql_fetch_array($ipt))
        {
            $body .= "<tr><td>$arch_ip[ip]</td><td>$arch_ip[total]</td></tr>\r\n";
        }
        $body .= "</table>";
    }

    $body .= "<h4 class=title>Число обращений из поисковых систем</h4>\r\n";
    $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
    $body .= "<tr class=header><td>Поисковая система</td><td>Обращений</td></tr>\r\n";
    $body .= "<tr><td>Yandex</td><td>$arch_num_searchquery[number_yandex]</td></tr>\r\n";
    $body .= "<tr><td>Google</td><td>$arch_num_searchquery[number_google]</td></tr>\r\n";
    $body .= "<tr><td>Rambler</td><td>$arch_num_searchquery[number_rambler]</td></tr>\r\n";
    $body .= "<tr><td>Aport</td><td>$arch_num_searchquery[number_aport]</td></tr>\r\n";
    $body .= "<tr><td>MSN</td><td>$arch_num_searchquery[number_msn]</td></tr>\r\n";
    $body .= "<tr><td>Mail.ru</td><td>$arch_num_searchquery[number_mail]</td></tr>\r\n";
    $body .= "</table>\r\n";

    if(mysql_num_rows($ynd) > 0)
    {
        $body .= "<h4 class=title>Запросы Yandex: ".YANDEX_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Поисковый запрос</td><td>Обращений</td></tr>\r\n";
        while($yandex = mysql_fetch_array($ynd))
        {
            $body .= "<tr><td>".htmlspecialchars($yandex['name'])."</td><td>$yandex[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    if(mysql_num_rows($gog) > 0)
    {
        $body .= "<h4 class=title>Запросы Google: ".GOOGLE_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Поисковый запрос</td><td>Обращений</td></tr>\r\n";
        while($google = mysql_fetch_array($gog))
        {
            $body .= "<tr><td>".htmlspecialchars($google['name'])."</td><td>$google[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    if(mysql_num_rows($rbl) > 0)
    {
        $body .= "<h4 class=title>Запросы Rambler: ".RAMBLER_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Поисковый запрос</td><td>Обращений</td></tr>\r\n";
        while($rambler = mysql_fetch_array($rbl))
        {
            $body .= "<tr><td>".htmlspecialchars($rambler['name'])."</td><td>$rambler[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    if(mysql_num_rows($apt) > 0)
    {
        $body .= "<h4 class=title>Запросы Aport: ".APORT_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Поисковый запрос</td><td>Обращений</td></tr>\r\n";
        while($aport = mysql_fetch_array($apt))
        {
            $body .= "<tr><td>".htmlspecialchars($aport['name'])."</td><td>$aport[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    if(mysql_num_rows($ms) > 0)
    {
        $body .= "<h4 class=title>Запросы MSN: ".MSN_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Поисковый запрос</td><td>Обращений</td></tr>\r\n";
        while($msn = mysql_fetch_array($ms))
        {
            $body .= "<tr><td>".htmlspecialchars($msn['name'])."</td><td>$msn[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    if(mysql_num_rows($ref) > 0)
    {
        $body .= "<h4 class=title>Рефереры: ".REFFERER_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Реферер</td><td>Обращений</td></tr>\r\n";
        while($referer = mysql_fetch_array($ref))
        {
            $body .= "<tr><td>".htmlspecialchars($referer['name'])."</td><td>$referer[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    if(mysql_num_rows($enp) > 0)
    {
        $body .= "<h4 class=title>Точки входа: ".ENTERPOINT_NUMBER." наиболее распространенных</h4>\r\n";
        $body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
        $body .= "<tr class=header><td>Точка входа</td><td>Обращений</td></tr>\r\n";
        while($enterpoint = mysql_fetch_array($enp))
        {
            $body .= "<tr><td>".htmlspecialchars($enterpoint['page'])."</td><td>$enterpoint[total]</td></tr>\r\n";
        }
        $body .= "</table>\r\n";
    }

    $body .= "<h4 class=title>Глубина просмотра</h4>\r\n";
    $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
    $body .= "<tr class=header><td>Количество страниц</td><td>Число посетителей</td></tr>\r\n";
    $body .= "<tr><td>1 страница</td><td>".$arch_deep['visit1']."</td></tr>\r\n";
    $body .= "<tr><td>2 страницы</td><td>".$arch_deep['visit2']."</td></tr>\r\n";
    $body .= "<tr><td>3 страницы</td><td>".$arch_deep['visit3']."</td></tr>\r\n";
    $body .= "<tr><td>4 страницы</td><td>".$arch_deep['visit4']."</td></tr>\r\n";
    $body .= "<tr><td>5 страниц</td><td>".$arch_deep['visit5']."</td></tr>\r\n";
    $body .= "<tr><td>6 страниц</td><td>".$arch_deep['visit6']."</td></tr>\r\n";
    $body .= "<tr><td>7 страниц</td><td>".$arch_deep['visit7']."</td></tr>\r\n";
    $body .= "<tr><td>8 страниц</td><td>".$arch_deep['visit8']."</td></tr>\r\n";
    $body .= "<tr><td>9 страниц</td><td>".$arch_deep['visit9']."</td></tr>\r\n";
    $body .= "<tr><td>10 страниц</td><td>".$arch_deep['visit10']."</td></tr>\r\n";
    $body .= "<tr><td>от 10 до 20 страниц</td><td>".$arch_deep['visit20']."</td></tr>\r\n";
    $body .= "<tr><td>от 20 до 30 страниц</td><td>".$arch_deep['visit30']."</td></tr>\r\n";
    $body .= "<tr><td>от 30 до 40 страниц</td><td>".$arch_deep['visit40']."</td></tr>\r\n";
    $body .= "<tr><td>от 40 до 50 страниц</td><td>".$arch_deep['visit50']."</td></tr>\r\n";
    $body .= "<tr><td>от 50 до 60 страниц</td><td>".$arch_deep['visit60']."</td></tr>\r\n";
    $body .= "<tr><td>от 60 до 70 страниц</td><td>".$arch_deep['visit70']."</td></tr>\r\n";
    $body .= "<tr><td>от 70 до 80 страниц</td><td>".$arch_deep['visit80']."</td></tr>\r\n";
    $body .= "<tr><td>от 80 до 90 страниц</td><td>".$arch_deep['visit90']."</td></tr>\r\n";
    $body .= "<tr><td>от 90 до 100 страниц</td><td>".$arch_deep['visit100']."</td></tr>\r\n";
    $body .= "<tr><td>более 100 страниц</td><td>".$arch_deep['visitmore']."</td></tr>\r\n";
    $body .= "</table>\r\n";


    $body .= "<h4 class=title>Выбор сеанса</h4>\r\n";
    $body .= "<table class=short border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n";
    $body .= "<tr class=header><td>Время</td><td>Число посетителей</td></tr>\r\n";
    $body .= "<tr><td>1 минута</td><td>".$arch_time['visit1']."</td></tr>\r\n";
    $body .= "<tr><td>2 минуты</td><td>".$arch_time['visit2']."</td></tr>\r\n";
    $body .= "<tr><td>3 минуты</td><td>".$arch_time['visit3']."</td></tr>\r\n";
    $body .= "<tr><td>4 минуты</td><td>".$arch_time['visit4']."</td></tr>\r\n";
    $body .= "<tr><td>5 минут</td><td>".$arch_time['visit5']."</td></tr>\r\n";
    $body .= "<tr><td>6 минут</td><td>".$arch_time['visit6']."</td></tr>\r\n";
    $body .= "<tr><td>7 минут</td><td>".$arch_time['visit7']."</td></tr>\r\n";
    $body .= "<tr><td>8 минут</td><td>".$arch_time['visit8']."</td></tr>\r\n";
    $body .= "<tr><td>9 минут</td><td>".$arch_time['visit9']."</td></tr>\r\n";
    $body .= "<tr><td>10 минут</td><td>".$arch_time['visit10']."</td></tr>\r\n";
    $body .= "<tr><td>от 10 до 20 минут</td><td>".$arch_time['visit20']."</td></tr>\r\n";
    $body .= "<tr><td>от 20 до 30 минут</td><td>".$arch_time['visit30']."</td></tr>\r\n";
    $body .= "<tr><td>от 30 до 40 минут</td><td>".$arch_time['visit40']."</td></tr>\r\n";
    $body .= "<tr><td>от 40 до 50 минут</td><td>".$arch_time['visit50']."</td></tr>\r\n";
    $body .= "<tr><td>от 50 до 60 минут</td><td>".$arch_time['visit60']."</td></tr>\r\n";
    $body .= "<tr><td>от 1 до 2 часов</td><td>".$arch_time['visit2h']."</td></tr>\r\n";
    $body .= "<tr><td>от 2 до 3 часов</td><td>".$arch_time['visit3h']."</td></tr>\r\n";
    $body .= "<tr><td>от 3 до 4 часов</td><td>".$arch_time['visit4h']."</td></tr>\r\n";
    $body .= "<tr><td>от 4 до 5 часов</td><td>".$arch_time['visit5h']."</td></tr>\r\n";
    $body .= "<tr><td>от 5 до 6 часов</td><td>".$arch_time['visit6h']."</td></tr>\r\n";
    $body .= "<tr><td>от 6 до 7 часов</td><td>".$arch_time['visit7h']."</td></tr>\r\n";
    $body .= "<tr><td>от 7 до 8 часов</td><td>".$arch_time['visit8h']."</td></tr>\r\n";
    $body .= "<tr><td>от 8 до 9 часов</td><td>".$arch_time['visit9h']."</td></tr>\r\n";
    $body .= "<tr><td>от 9 до 10 часов</td><td>".$arch_time['visit10h']."</td></tr>\r\n";
    $arch_time['visit11h'] +=   $arch_time['visit12h'] +
                                $arch_time['visit13h'] +
                                $arch_time['visit14h'] +
                                $arch_time['visit15h'] +
                                $arch_time['visit16h'] +
                                $arch_time['visit17h'] +
                                $arch_time['visit18h'] +
                                $arch_time['visit19h'] +
                                $arch_time['visit20h'] +
                                $arch_time['visit21h'] +
                                $arch_time['visit22h'] +
                                $arch_time['visit23h'] +
                                $arch_time['visit24h'] +
    $body .= "<tr><td>от 10 до 24 часов</td><td>".$arch_time['visit11h']."</td></tr>\r\n";
    $body .= "</table>\r\n";
    $body .= "</body></html>\r\n";


    // Изменяем кодировку
//    $thm = convert_cyr_string("Суточная статистика сайта", 'k', 'w');
//    $body = convert_cyr_string($body, 'w', 'k');


    $thm = "Суточная статистика сайта";
    $t = "=?utf-8?B?".base64_encode($thm)."?=";
    $body = ($body);
    // Отправляем письмо

    // Отправляем письмо
    @mail(EMAIL_ADDRESS, $t, $body, $header);


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