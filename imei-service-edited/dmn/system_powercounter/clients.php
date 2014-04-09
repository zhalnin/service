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

$title = 'Системы&nbsp;и&nbsp;браузеры';
$pageinfo = 'На этой странице вы можете видеть
статистику по операционным системам и браузерам
из под которых посетители заходят на сайт.';
try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");
    // Включаем массив временных интервалов
    require_once("time_interval.php");

    // Формируем массив с названиями операционных систем
    $os['win']  = "Windows";
    $os['lin']  = "Linux & Unix";
    $os['mac']  = "Macintosh";
    $os['os']   = "Другие";
    // Формируем массив с названиями браузеров
    $br['ie']   = "Internet Explorer";
    $br['net']  = "Netscape";
    $br['opr']  = "Opera";
    $br['ffx']  = "FireFox";
    $br['mie']  = "MyIE";
    $br['moz']  = "Mozilla";
    $br['br']   = "Другие";

    // Запрашиваем данные за пять временных интервалов
    // определенных в файл time_interval.php
    for($i = 0; $i < 5; $i++)
    {
        list($hit['win'][$i],
             $hit['lin'][$i],
             $hit['mac'][$i],
             $hit['os'][$i],
             $hit['ie'][$i],
             $hit['net'][$i],
             $hit['opr'][$i],
             $hit['ffx'][$i],
             $hit['mie'][$i],
             $hit['moz'][$i],
             $hit['br'][$i],
             $totals[$i],
             $totalb[$i]) = system_info($time[$i]['begin'],
                                        $time[$i]['end']);
    }
?>
    <table class="table"
           width="100%"
           border="0"
           cellpadding="0"
           cellspacing="0">
        <tr class="header" align="center">
            <td>&nbsp;</td>
            <td>Сегодня</td>
            <td>Вчера</td>
            <td>За 7 дней</td>
            <td>За 30 дней</td>
            <td>За все время</td>
        </tr>

<?php
    echo "<tr class=subtitle><td colspan=6><b>Операционные системы</b></td></tr>";
    // Формируем блок "Операционные системы"
    foreach($os as $key => $name)
    {
        echo "<tr align=right>";
        echo "<td class=field>$name</td>";
        for($i=0; $i<5; $i++)
        {
            $total = sprintf("%d (%01.1f%s)",
                            $hit[$key][$i],
                            $hit[$key][$i]/$totals[$i]*100,
                            '%');
            echo "<td><p>$total</p></td>";
        }
        echo "</tr>";
    }
    echo "<tr class=subtitle><td colspan=6><b>Браузеры</b></td></tr>";
    // Формируем блок "Браузеры"
    foreach($br as $key => $name)
    {
        echo "<tr align=right>";
        echo "<td class=field>$name</td>";
        for($i=0; $i<5; $i++)
        {
            $total = sprintf("%d (%01.1f%s)",
                            $hit[$key][$i],
                            $hit[$key][$i]/$totalb[$i]*100,
                            '%');
            echo "<td><p>$total</p></td>";
        }
        echo "</tr> ";
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

// Функция возвращает массив из девяти переменных,
// с информацией о числе посетителей использующих
// ту или иную операционную систему или браузер.
// $begin - число дней, которое необходимо вычесть из текущей даты
// для того чтобы получить начальную точку временного интервала
// $end - число дней, которое необходимо вычесть из текущей даты,
// для того, чтобы получить конечную точку временного интервала
// $tbl_ip - название таблицы, в которой хранятся IP-адреса
// $tbl_arch_clients - название архивной таблицы
function system_info($begin = 1, $end = 0)
{
    // Объявляем имена таблиц глобальными
    global $tbl_arch_clients, $tbl_arch_clients_month, $tbl_ip;
    
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
    
    // Сегодня
    if($begin == 1 && $end == 0)
    {
        $where = where_interval();
        
        // Формируем SQL-запросы
        $begins = "SELECT COUNT(DISTINCT ip) FROM $tbl_ip
                    $where AND systems NOT LIKE 'robot_%' AND
                               systems = ";
        $beginb = "SELECT COUNT(DISTINCT ip) FROM $tbl_ip
                    $where AND systems NOT LIKE 'robot_%' AND
                                browsers = ";
        $query['win']  = $begins."'windows'";
        $query['lin']  = $begins."'unix'";
        $query['mac']  = $begins."'macintosh'";
        $query['snn']  = $begins."'none'";
        $query['ie']   = $beginb."'msie'";
        $query['opr']  = $beginb."'opera'";
        $query['net']  = $beginb."'netscape'";
        $query['ffx']  = $beginb."'firefox'";
        $query['mie']  = $beginb."'myie'";
        $query['moz']  = $beginb."'mozilla'";
        $query['bnn']  = $beginb."'none'";
        // Выполняем SQL-запросы
        foreach($query as $os => $value)
        {
            $hits[$os] = query_result($value);
        }
        $totals = $hits['win'] + $hits['lin'] + 
                  $hits['mac'] + $hits['snn'];
        $totalb = $hits['ie'] + $hits['opr'] +
                  $hits['net'] + $hits['ffx'] +
                  $hits['mie'] + $hits['moz'] + $hits['bnn'];
        // Во избежание деления на ноль проверяем общее число хитов
        if($totals == 0) $totals = 1;
        if($totalb == 0) $totalb = 1;
        // Возвращаем массив значений
        return array($hits['win'],
                     $hits['lin'],
                     $hits['mac'],
                     $hits['snn'],
                     $hits['ie'],
                     $hits['net'],
                     $hits['opr'],
                     $hits['ffx'],
                     $hits['mie'],
                     $hits['moz'],
                     $hits['bnn'],
                     $totals,
                     $totalb);
    }
    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);
    // Все время
    if($begin == 0 && $end == 0)
    {
        $end = "FROM $tbl_arch_clients $where";
        // Формируем SQL-запросы
        $query['win'] = "SELECT SUM(systems_windows) $end";
        $query['lin'] = "SELECT SUM(systems_unix) $end";
        $query['mac'] = "SELECT SUM(systems_macintosh) $end";
        $query['snn'] = "SELECT SUM(systems_none) $end";
        $query['ie']  = "SELECT SUM(browsers_msie) $end";
        $query['opr'] = "SELECT SUM(browsers_opera) $end";
        $query['net'] = "SELECT SUM(browsers_netscape) $end";
        $query['ffx'] = "SELECT SUM(browsers_firefox) $end";
        $query['mie'] = "SELECT SUM(browsers_myie) $end";
        $query['moz'] = "SELECT SUM(browsers_mozilla) $end";
        $query['bnn'] = "SELECT SUM(systems_none) $end";

        // Выполняем SQL-запросы
        foreach($query as $os => $value)
        {
            $hits[$os] += query_result($value);
        }

        // Получаем самое старое число из таблицы $tbl_arch_clients,
        // все, что позже берем из таблицы $tbl_arch_clients_month
        $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data FROM $tbl_arch_clients";
        $last_day = query_result($query);
        if($last_day)
        {
            $end = "FROM $tbl_arch_clients_month WHERE putdate < '".date("Y-m-01", $last_day)."'";
            // Формируем SQL-запросы
            unset($query);
            $query['win'] = "SELECT SUM(systems_windows) $end";
            $query['lin'] = "SELECT SUM(systems_unix) $end";
            $query['mac'] = "SELECT SUM(systems_macintosh) $end";
            $query['snn'] = "SELECT SUM(systems_none) $end";
            $query['ie']  = "SELECT SUM(browsers_msie) $end";
            $query['opr'] = "SELECT SUM(browsers_opera) $end";
            $query['net'] = "SELECT SUM(browsers_netscape) $end";
            $query['ffx'] = "SELECT SUM(browsers_firefox) $end";
            $query['mie'] = "SELECT SUM(browsers_myie) $end";
            $query['moz'] = "SELECT SUM(browsers_mozilla) $end";
            $query['bnn'] = "SELECT SUM(systems_none) $end";

            // Выполняем SQL-запросы
            foreach($query as $os => $value)
            {
                $hits[$os] += query_result($value);
            }
        }
    }
    // Общий случай
    else
    {
         $end = "FROM $tbl_arch_clients $where";
        // Формируем SQL-запросы
        $query['win'] = "SELECT SUM(systems_windows) $end";
        $query['lin'] = "SELECT SUM(systems_unix) $end";
        $query['mac'] = "SELECT SUM(systems_macintosh) $end";
        $query['snn'] = "SELECT SUM(systems_none) $end";
        $query['ie']  = "SELECT SUM(browsers_msie) $end";
        $query['opr'] = "SELECT SUM(browsers_opera) $end";
        $query['net'] = "SELECT SUM(browsers_netscape) $end";
        $query['ffx'] = "SELECT SUM(browsers_firefox) $end";
        $query['mie'] = "SELECT SUM(browsers_myie) $end";
        $query['moz'] = "SELECT SUM(browsers_mozilla) $end";
        $query['bnn'] = "SELECT SUM(systems_none) $end";

        // Выполняем SQL-запросы
        foreach($query as $os => $value)
        {
            $hits[$os] += query_result($value);
        }
    }
    $totals = $hits['win'] + $hits['lin'] +
              $hits['mac'] + $hits['snn'];
    $totalb = $hits['ie'] + $hits['opr'] +
              $hits['net'] + $hits['ffx'] +
              $hits['mie'] + $hits['moz'] + $hits['bnn'];
    // Во избежание деления на ноль проверяем общее число хитов
    if($totals == 0) $totals = 1;
    if($totalb == 0) $totalb = 1;
    // Возвращаем массив значений
    return array($hits['win'],
                 $hits['lin'],
                 $hits['mac'],
                 $hits['snn'],
                 $hits['ie'],
                 $hits['net'],
                 $hits['opr'],
                 $hits['ffx'],
                 $hits['mie'],
                 $hits['moz'],
                 $hits['bnn'],
                 $totals,
                 $totalb);
}
?>
