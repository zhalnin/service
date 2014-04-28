<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08.06.12
 * Time: 20:07
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
// Формирование WHERE-условий
require_once("utils.where.php");
// Выполнение запроса
require_once("utils.query_result.php");

$title = 'Поисковые&nbsp;роботы';
$pageinfo = 'На этой странице вы можете видеть
статистику по посещению сайта роботами поисковых систем.
 Это достаточно важный параметр, позволяющий следить за тем, насколько
 успешно проходит индексация сайта поисковыми системами';
try {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");
    // Включаем массив временных интервалов
    require_once("time_interval.php");

    // Формируем массив с названиями поисковых роботов
    $rob['search']['ram']  = "robot_rambler";
    $rob['search']['gog']  = "robot_google";
    $rob['search']['ynd']  = "robot_yandex";
    $rob['search']['apt']  = "robot_aport";
    $rob['search']['msn']  = "robot_msnbot";
    $rob['search']['oth']  = "robot_none";
    $rob['search']['tot']  = "robot_total";
    $rob['name']['ram']    = "Rambler";
    $rob['name']['gog']    = "Google";
    $rob['name']['ynd']    = "Yandex";
    $rob['name']['apt']    = "Aport";
    $rob['name']['msn']    = "MSN";
    $rob['name']['oth']    = "Другие";
    $rob['name']['tot']    = "Всего";


    // Защита от SQL-инъекции
    $id_page = intval($_GET['id_page']);

    // Запрашиваем данные за пять временных интервалов
    // определенных в файл time_interval.php
    for($i = 0; $i < 5; $i++) {
        list($hit['ram'][$i],
             $hit['gog'][$i],
             $hit['ynd'][$i],
             $hit['apt'][$i],
             $hit['msn'][$i],
             $hit['oth'][$i],
             $hit['tot'][$i]) = robots($time[$i]['begin'],
                                        $time[$i]['end'],
                                        $id_page);
    }
?>
    <table class="table"
           width="100%"
           border="0"
           cellpadding="0"
           cellspacing="0">
        <tr class="header" align="center">
            <td>Название робота</td>
            <td>Сегодня</td>
            <td>Вчера</td>
            <td>За 7 дней</td>
            <td>За 30 дней</td>
            <td>За все время</td>
        </tr>
<?php
    // Формируем блок
    foreach($rob['search'] as $key=>$val) {
        echo "<tr align=right>";
        echo "<td class=field>{$rob['name'][$key]}</td>";
        for($i=0; $i<5; $i++)
        {
            $number = sprintf("%d (%01.1f%s)",
                            $hit[$key][$i],
                            $hit[$key][$i]/$hit['tot'][$i]*100,
                            '%');
            if($i != 4)
            {
                echo "<td><a href=pages.php?begin=".$time[$i]['begin']."&end=".$time[$i]['end']."&ip=$val>$number</a></td>";
            }
            else echo "<td>".sprintf("%d (%01.1f%s)",$hit["$key"][4],$hit["$key"][4]/$hit['tot'][4]*100,'%')."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    // Включаем заврешение страниы
    require_once("../utils/bottomcounter.php");
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

// Функция возвращает массив из шести переменных,
// Число роботов Rambler
// Число роботов Google
// Число роботов Yandex
// Число роботов Aport
// Другие роботы
// Общее число посещений
// $begin - число дней, которое необходимо вычесть из текущей даты
// для того чтобы получить начальную точку временного интервала
// $end - число дней, которое необходимо вычесть из текущей даты,
// для того, чтобы получить конечную точку временного интервала
// $id_page - первичный ключ записи таблицы pages, соответствующий странице сайта

function robots($begin = 1, $end = 0, $id_page = "")
{
    // Объявляем имена таблиц глобальными
    global $tbl_arch_robots, $tbl_arch_robots_month, $tbl_ip;
    
    // Обнуляем хиты и хосты
    $hits = array();
    
    //////////////////////////////////////////////////////////////////
    // Исходим из таблицы соответствия
    //            begin end
    // сегодня      1    0  - это извлекаем из $tbl_ip
    // вчера        2    1  - это извлекаем из $tbl_arch_clients
    // неделя       7    0  - это извлекаем из $tbl_arch_clients,
    // месяц       30    0  - это извлекаем из $tbl_arch_clients,
    // всё время    0    0  - это извлекаем из $tbl_arch_clients_month
    //////////////////////////////////////////////////////////////////

    // Эта переменная определяет осуществляется ли запрос к конкретной
    // странице или ко всему сайту
    if($id_page == "") $tmp = "";
    else $tmp = " AND id_page=$id_page";

    // Сегодня
    if($begin == 1 && $end == 0)
    {
        // Формируем WHERE-условие для временного интервала
        $where = where_interval($begin, $end);
        
        // Формируем SQL-запросы
        $begin = "SELECT COUNT(*) FROM $tbl_ip
                    $where $tmp AND systems = ";
        $query['ynd']  = "$begin 'robot_yandex'";
        $query['ram']  = "$begin 'robot_rambler'";
        $query['gog']  = "$begin 'robot_google'";
        $query['apt']  = "$begin 'robot_aport'";
        $query['msn']  = "$begin 'robot_msnbot'";
        $query['oth']  = "$begin 'none'";
        // Выполняем SQL-запросы
        foreach($query as $robot => $value)
        {
            $hits[$robot] = query_result($value);
        }
        $total = array_sum($hits);
        // Во избежание деления на ноль проверяем общее число хитов $total
        if($total == 0) $total = 1;
        // Возвращаем массив значений
        return array($hits['ram'],
                     $hits['gog'],
                     $hits['ynd'],
                     $hits['apt'],
                     $hits['msn'],
                     $hits['otn'],
                     $total);
    }
    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);
    // Все время
    if($begin == 0 && $end == 0)
    {
        $end = "FROM $tbl_arch_robots $where";
        // Формируем SQL-запросы
        $query['ynd'] = "SELECT SUM(yandex)     $end";
        $query['ram'] = "SELECT SUM(rambler)    $end";
        $query['gog'] = "SELECT SUM(google)     $end";
        $query['apt'] = "SELECT SUM(aport)      $end";
        $query['msn'] = "SELECT SUM(msn)        $end";
        $query['oth'] = "SELECT SUM(none)       $end";

        // Выполняем SQL-запросы
        foreach($query as $robot => $value)
        {
            $hits[$robot] += query_result($value);
        }

        // Получаем самое старое число из таблицы $tbl_arch_robots,
        // все, что позже берем из таблицы $tbl_arch_robots_month
        $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data FROM $tbl_arch_robots";
        $last_day = query_result($query);
        if($last_day)
        {
            // Формируем WHERE-условие
            $where = "WHERE putdate < '".date("Y-m-01", $last_day)."'";
            // Формируем SQL-запросы
            unset($query);
            $end = " FROM $tbl_arch_robots_month $where";
            $query['ynd'] = "SELECT SUM(yandex)     $end";
            $query['ram'] = "SELECT SUM(rambler)    $end";
            $query['gog'] = "SELECT SUM(google)     $end";
            $query['apt'] = "SELECT SUM(aport)      $end";
            $query['msn'] = "SELECT SUM(msn)        $end";
            $query['oth'] = "SELECT SUM(none)       $end";

            // Выполняем SQL-запросы
            foreach($query as $robot => $value)
            {
                $hits[$robot] += query_result($value);
            }
        }
    }
    // Общий случай
    else
    {
        // Формируем SQL-запросы
        $end = " FROM $tbl_arch_robots $where";
        $query['ynd'] = "SELECT SUM(yandex)     $end";
        $query['ram'] = "SELECT SUM(rambler)    $end";
        $query['gog'] = "SELECT SUM(google)     $end";
        $query['apt'] = "SELECT SUM(aport)      $end";
        $query['msn'] = "SELECT SUM(msn)        $end";
        $query['oth'] = "SELECT SUM(none)       $end";

        // Выполняем SQL-запросы
        foreach($query as $robot => $value)
        {
            $hits[$robot] += query_result($value);
        }
    }
    $total = array_sum($hits);
    // Во избежание деления на ноль проверяем общее число хитов
    if($total == 0) $total = 1;
    // Возвращаем массив значений
    return array($hits['ram'],
                 $hits['gog'],
                 $hits['ynd'],
                 $hits['apt'],
                 $hits['msn'],
                 $hits['oth'],
                 $total);
}
?>
