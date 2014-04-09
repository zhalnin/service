<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.05.12
 * Time: 22:51
 * To change this template use File | Settings | File Templates.
 */
 
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

$title = 'Хосты&nbsp;и&nbsp;хиты';
$pageinfo = 'На этой странице вы видите общую статистику
по посетителям сайта. <br><b>Хосты</b> = это количество
уникальных посетителей Вашего сайта, <b>хиты</b> - это
общее количество показов сайта. <br>При переходе по
ссылкам "<b>Сегодня</b>","<b>Вчера</b>" отображается
детальная почасовая статистика посещений за выбранный день.
При переходе по ссылкам "<b>За 7 дней</b>" и "<b>За 30 дней
</b>" отображается детальная суточная статистика за эти
периоды времени.';

try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");
    // Включаем массив временных интервалов
    require_once("time_interval.php");

    // Запрашиваем данные за пять временных интервалов
    // определенных в файл time_interval.php
    for($i = 0; $i < 5; $i++)
    {
        list($hits_total[$i],
             $hits[$i],
             $hosts_total[$i],
             $hosts[$i]) = show_ip_host($time[$i]['begin'],
                                        $time[$i]['end']);
    }
?>
        <table class="table"
               width="100%"
               border="0"
               cellpadding="0"
               cellspacing="0">
            <tr class="header" align="center">
                <td width=<?= 100/6 ?>% align=center>&nbsp;</td>
                <td width=<?= 100/6 ?>% align=center>Сегодня</td>
                <td width=<?= 100/6 ?>% align=center>Вчера</td>
                <td width=<?= 100/6 ?>% align=center>За 7 дней</td>
                <td width=<?= 100/6 ?>% align=center>За 30 дней</td>
                <td width=<?= 100/6 ?>% align=center>За все время</td>
            </tr>
            <tr><td class=field>Засчитанные хосты</td>
                <?php
                    foreach($hosts as $value)
                        echo "<td align=center><p>$value</p></td>";
                ?>
            </tr>
            <tr><td class=field>Хосты</td>
                <?php
                    foreach($hosts_total as $value)
                        echo "<td align=center><p>$value</p></td>";
                ?>
            </tr>
            <tr><td class=field>Засчитанные хиты</td>
                <?php
                    foreach($hits as $value)
                        echo "<td align=center><p>$value</p></td>";
                ?>
            </tr>
            <tr><td class=field>Хиты</td>
                <?php
                    foreach($hits_total as $value)
                        echo "<td align=center><p>$value</p></td>";
                ?>
            </tr>
               </table>
<?php
// Включаем завершение страницы
  require_once("../utils/bottom.php");
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

// Функция возвращает массив из четырех переменных (для интервала):
// общее количество хитов,
// количество засчитанных хитов,
// количество хостов,
// количество засчитанных хостов,
//  $begin - число дней, которое необходимо вычесть из текущей даты,
// для того чтобы получить начальную точку временного интервала
// $end - число дней, которое необходимо вычесть из текущей даты,
// для того чтобы получить конечную точку временного интервала
function show_ip_host($begin = 1, $end = 0)
{
    // Объявляем имена таблиц глобальными
    global  $tbl_arch_hits, $tbl_arch_hits_month, $tbl_ip;
    // Обнуляем хиты и хосты
    $hosts_total    = 0;
    $hosts          = 0;
    $hits_total     = 0;
    $hits           = 0;

    // Исходим из таблицы соответствия
    //            begin end
    // сегодня      1   0   - это извлекаем из $tbl_ip
    // вчера        2   1   - это извлекаем из $tbl_arch_hits
    // неделя       7   0   - это извлекаем из $tbl_arch_hits
    // месяц        30  0   - это извлекаем из $tbl_arch_hits
    // все время    0   0   - это извлекаем из $tbl_arch_month

    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);
    // Сегодня
    if($begin == 1 && $end == 0)
    {
        // Общее количество хитов
        $query_hit_total    = "SELECT COUNT(*)
                              FROM $tbl_ip $where";
        // Засчитанные хиты
        $query_hit          = "SELECT COUNT(*) FROM $tbl_ip
                               $where AND systems!='none' AND
                                            systems NOT LIKE 'robot_%'";
        // Подсчитываем количество IP-адресов (хостов)
        $query_host_total   = "SELECT COUNT(DISTINCT ip)
                                FROM $tbl_ip
                                $where AND systems!='none' AND
                                            systems NOT LIKE 'robot_%'";
        // Подсчитываем количество уникальных посетителей за сутки
        $query_host         = "SELECT COUNT(DISTINCT ip)
                                FROM $tbl_ip
                                $where AND systems!='none' AND
                                        systems NOT LIKE 'robot_%'";
        return array(query_result($query_hit_total),
                    query_result($query_hit),
                    query_result($query_host_total),
                    query_result($query_host));
    }
    // Все время
    if($begin == 0 && $end == 0)
    {
        // Общее число хитов
        $query_hit_total    = "SELECT SUM(hits_total) FROM $tbl_arch_hits";
        // Засчитанные хиты
        $query_hit          = "SELECT SUM(hits) FROM $tbl_arch_hits";
        // Подсчитываем число IP-адресов (хостов)
        $query_host_total   = "SELECT SUM(hosts_total) FROM $tbl_arch_hits";
        // Подсчитываем число уникальных посетителей за сутки
        $query_host         = "SELECT SUM(host) FROM $tbl_arch_hits";

        // Если запросы выполнены удачно,
        // получаем результат
        $hits_total     += query_result($query_hit_total);
        $hits           += query_result($query_hit);
        $hosts_total    += query_result($query_host_total);
        $hosts          += query_result($query_host);

        // Получаем самое старое число из таблицы $tbl_arch_hits,
        // все, что позже берем из таблицы $tbl_arch_hits_month
        $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data FROM $tbl_arch_hits";
        $last_day = query_result($query);
        if($last_day)
        {
            $where = "WHERE putdate < '".date("Y-m-01", $last_day)."'";
            // Общее число хитов
            $query_hit_total    = "SELECT SUM(hits_total)
                                    FROM $tbl_arch_hits_month $where";
            // Засчитанные хиты
            $query_hit          = "SELECT SUM(hits)
                                    FROM $tbl_arch_hits_month $where";
            // Подсчитываем число IP-адресов (хостов)
            $query_host_total   = "SELECT SUM(hosts_total)
                                    FROM $tbl_arch_hits_month $where";
            // Подсчитываем число уникальных посетителей за сутки
            $query_host         = "SELECT SUM(host)
                                    FROM $tbl_arch_hits_month $where";

            // Если запросы выполнены удачно,
            // получаем результат
            $hits_total     += query_result($query_hit_total);
            $hits           += query_result($query_hit);
            $hosts_total    += query_result($query_host_total);
            $hosts          += query_result($query_host);
        }
    }
    // Общий случай
    else
    {
        // Общее число хитов
        $query_hit_total = "SELECT SUM(hits_total)
                                FROM $tbl_arch_hits $where";
        // Засчитанные хиты
        $query_hit          = "SELECT SUM(hits)
                              FROM $tbl_arch_hits $where";
        // Подсчитываем число IP-адресов (хостов)
        $query_host_total   = "SELECT SUM(hosts_total)
                                FROM $tbl_arch_hits $where";
        // Подсчитываем число уникальных посетителей за сутки
        $query_host         = "SELECT SUM(host)
                                FROM $tbl_arch_hits $where";

        // Если запросы выполнены удачно,
        // получаем результат
        $hits_total     += query_result($query_hit_total);
        $hits           += query_result($query_hit);
        $hosts_total    += query_result($query_host_total);
        $hosts          += query_result($query_host);
    }
    // Возвращаем результата
    return array($hits_total, $hits, $hosts_total, $hosts);
}
?>
