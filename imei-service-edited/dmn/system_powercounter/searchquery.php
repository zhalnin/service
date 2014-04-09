<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 14.06.12
 * Time: 20:48
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
// Постраничная навигация
require_once("../utils/utils.pager.php");
// Формирование WHERE-условий
require_once("utils.where.php");
// Выполнение запроса
require_once("utils.query_result.php");

// Заголовок страницы
$title = 'Поисковые&nbsp;запросы';
$pageinfo = 'На этой странице вы можете видеть по каким
запросам с поисковых систем приходят посетители на ваш сайт.';

try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Включаем массив временных интревалов
    require_once("time_interval.php");

    // Формируем массив с названиями поисковых систем
    $sch['rambler'] = "Rambler";
    $sch['google'] = "Google";
    $sch['yandex'] = "Yandex";
    $sch['aport'] = "Aport";
    $sch['msn'] = "MSN";
    $sch['mail'] = "Mail.ru";
    $sch['total'] = "Все";

    for($i = 0; $i < 5; $i++)
    {
        list($hit['rambler'][$i],
             $hit['yandex'][$i],
             $hit['aport'][$i],
             $hit['google'][$i],
             $hit['msn'][$i],
             $hit['mail'][$i],
             $hit['total'][$i]) = search($time[$i]['begin'],
                                    $time[$i]['end'],
                                    $id_page);
    }
?>
    <table width=100%
           class="table"
           border="0"
           cellpadding="0"
           cellspacing="0">
        <tr class="header" align="center">
            <td>Поисковые&nbsp;системы</td>
            <td width=<?= 100/5 ?>% >Сегодня</td>
            <td width=<?= 100/5 ?>% >Вчера</td>
            <td width=<?= 100/5 ?>% >За 7 дней</td>
            <td width=<?= 100/5 ?>% >За 30 дней</td>
            <td width=<?= 100/5 ?>% >За все время</td>
        </tr>
<?php
            // Формируем таблицу с числом обращений с операционных систем
            foreach($sch as $key => $name)
            {
                echo "<tr align=right>";
                echo "<td class=field>$name</td>";
                for($i = 0; $i < 4; $i++)
                {
                    echo "<td><a href=searchquery.php?".
                            "begin={$time[$i][begin]}&".
                            "end={$time[$i][end]}&".
                            "srch=$key&".
                            "id_page=$id_page>{$hit[$key][$i]}</a></td>";
                }
                echo "<td>".$hit[$key][4]."</td>";
                echo "</tr>";
            }
            echo "</table><br>";

            if(empty($_GET['begin']) ||
               !isset($_GET['end']) ||
                empty($_GET['srch']))
            {
                $_GET['begin']  = 1;
                $_GET['end']    = 0;
                $_GET['srch']   = "total";
            }

            // Элемент постраничной навигации
            if(empty($_GET['page'])) $page = 1;
            else $page = intval($_GET['page']);

            searchquery($_GET['begin'], $_GET['end'], $_GET['srch'], $page, $pnumber);

    // Завершение страницы
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
function searchquery($begin, $end, $srch,  $page, $pnumber)
{
    // Объявляем имена таблиц глобальными
    global $tbl_searchquerys, $tbl_pages;
    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);

    $page_link = 3;
    $start = ($page -1)*$pnumber;
    // Общее количество записей
    if($srch != "total")
    {
        $query = "SELECT COUNT(*)
              FROM $tbl_searchquerys, $tbl_pages
              $where AND $tbl_searchquerys.id_page = $tbl_pages.id_page AND
              $tbl_searchquerys.searches = '$srch'";
    }
    else
    {
        // Все поисковые системы
        $query = "SELECT COUNT(*)
              FROM $tbl_searchquerys, $tbl_pages
              $where AND $tbl_searchquerys.id_page = $tbl_pages.id_page";
    }
    $total = query_result($query);

    // Выводим ссылки на другие страницы
    pager($page,
            $total,
            $pnumber,
            $page_link,
            "&begin=$begin&end=$end&srch=$srch&order=$order");
    echo "<br>";
    // Извлекаем позиции для текущей страницы
    if($srch != 'total')
    {
        // Ключевые слова для конкретной поисковой системы
        $query = "SELECT $tbl_pages.title AS title,
                      $tbl_pages.name AS url,
                      $tbl_searchquerys.query AS name,
                      putdate,
                      INET_NTOA(ip) AS ip,
                      searches
                FROM $tbl_searchquerys, $tbl_pages
                $where AND $tbl_searchquerys.id_page = $tbl_pages.id_page AND
                $tbl_searchquerys.searches = '$srch'
                ORDER BY putdate DESC
                LIMIT $start, $pnumber";
    }
    else
    {
        // Все поисковые системы
        $query = "SELECT $tbl_pages.title AS title,
                      $tbl_pages.name AS url,
                      $tbl_searchquerys.query AS name,
                      putdate,
                      INET_NTOA(ip) AS ip,
                      searches
                FROM $tbl_searchquerys, $tbl_pages
                $where AND $tbl_searchquerys.id_page = $tbl_pages.id_page
                ORDER BY putdate DESC
                LIMIT $start, $pnumber";
    }

    $qwr = mysql_query($query);
    $i = $start + 1;
    if(!$qwr)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при выполнении запроса");
    }
    if(mysql_num_rows($qwr))
    {
        echo "<br><table class=\"table\"
                     width=100%
                     border=\"0\"
                     cellpadding=\"0\"
                     cellspacing=\"0\">

                     <tr class=header
                         align=center>
                         <td width=50 align=center>Номер</td>
                         <td>Реферер</td>
                         <td>Число обращений</td>
                         <td>Страница</td>
                         <td>iP-адрес</td>
                         <td>Запрос</td>
                     </tr>";
        while($ip = mysql_fetch_array($qwr))
        {
            if(empty($ip['name'])) continue;
            
            echo "<tr>
                    <td>$i</td>
                    <td>".htmlspecialchars($ip['name'])."</td>
                    <td>$ip[searches]</td>
                    <td>$ip[putdate]</td>
                    <td><a href=pages.php?ip=$ip[ip]&begin=$begin&end=$end>$ip[ip]</a></td>
                    <td><a href=http://{$_SERVER[SERVER_NAME]}{$ip[url]}>$ip[title]</a></td>
                  </tr>";
            $i++;
        }
        echo "</table>";
    }
}

function search($begin, $end, $id_page)
{
    // Объявляем имена таблиц глобальными
    global $tbl_arch_num_searchquery, $tbl_arch_num_searchquery_month, $tbl_searchquerys;
    
    // Эта переменная определяет осуществляестя ли запрос к конкретной
    // странице или ко всему сайту.
    if($id_page == "") $tmp = "";
    else $tmp = " AND id_page = $id_page";
    // Обнуляем хиты и хосты
    $hits = array();
    
    ///////////////////////////////////////////////////////////////////////////
    // Исходим из таблицы соответствия
    //            begin end
    // сегодня      1    0  - это извлекаем из $tbl_searchquerys
    // вчера        2    1  - это извлекаем из $tbl_arch_num_searchquery
    // неделя       7    0  - это извлекаем из $tbl_arch_num_searchquery
    // месяц       30    0  - это извлекаем из $tbl_arch_num_searchquery
    // всё время    0    0  - это извлекаем из $tbl_arch_num_searchquery_month
    ///////////////////////////////////////////////////////////////////////////

    // Эта переменная определяет осуществляется ли запрос к конкретной
    // странице или ко всему сайту

    // Сегодня
    if($begin == 1 && $end == 0)
    {
        // Формируем WHERE-условие для временного интервала
        $where = where_interval($begin, $end);
        
        // Формируем SQL-запросы
        $begin = "SELECT COUNT(*) FROM $tbl_searchquerys
                    $where $tmp AND searches = ";
        $query['ynd']  = "$begin 'yandex'";
        $query['ram']  = "$begin 'rambler'";
        $query['gog']  = "$begin 'google'";
        $query['apt']  = "$begin 'aport'";
        $query['msn']  = "$begin 'msn'";
        $query['mil']  = "$begin 'mail'";;
        // Выполняем SQL-запросы
        foreach($query as $search => $value)
        {
            $hits[$search] = query_result($value);
        }
        $total = array_sum($hits);
//        // Во избежание деления на ноль проверяем общее число хитов $total
//        if($total == 0) $total = 1;
        // Возвращаем массив значений
        return array($hits['ram'],
                     $hits['gog'],
                     $hits['ynd'],
                     $hits['apt'],
                     $hits['msn'],
                     $hits['mil'],
                     $total);
    }
    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);
    // Все время
    if($begin == 0 && $end == 0)
    {
        $end = "FROM $tbl_arch_num_searchquery $where";
        // Формируем SQL-запросы
        $query['ynd'] = "SELECT SUM(number_yandex)     $end";
        $query['ram'] = "SELECT SUM(number_rambler)    $end";
        $query['gog'] = "SELECT SUM(number_google)     $end";
        $query['apt'] = "SELECT SUM(number_aport)      $end";
        $query['msn'] = "SELECT SUM(number_msn)        $end";
        $query['mil'] = "SELECT SUM(number_mail)       $end";

        // Выполняем SQL-запросы
        foreach($query as $search => $value)
        {
            $hits[$search] += query_result($value);
        }

        // Формируем SQL-запросы
        $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data FROM $tbl_arch_num_searchquery";
        $last_day = query_result($query);
        if($last_day)
        {
            // Формируем WHERE-условие
//            $where = "WHERE putdate < '".date("Y-m-01", $last_date)."'";
            // Формируем SQL-запросы
            $end = " FROM $tbl_arch_num_searchquery_month WHERE putdate < '".date("Y-m-01", $last_date)."'";
            unset($query);
            $query['ynd'] = "SELECT SUM(number_yandex)     $end";
            $query['ram'] = "SELECT SUM(number_rambler)    $end";
            $query['gog'] = "SELECT SUM(number_google)     $end";
            $query['apt'] = "SELECT SUM(number_aport)      $end";
            $query['msn'] = "SELECT SUM(number_msn)        $end";
            $query['mil'] = "SELECT SUM(number_mail)       $end";

            // Выполняем SQL-запросы
            foreach($query as $search => $value)
            {
                $hits[$search] += query_result($value);
            }
        }
    }
    // Общий случай
    else
    {
        // Формируем SQL-запросы
        $end = " FROM $tbl_arch_num_searchquery $where";
        $query['ynd'] = "SELECT SUM(number_yandex)     $end";
        $query['ram'] = "SELECT SUM(number_rambler)    $end";
        $query['gog'] = "SELECT SUM(number_google)     $end";
        $query['apt'] = "SELECT SUM(number_aport)      $end";
        $query['msn'] = "SELECT SUM(number_msn)        $end";
        $query['mil'] = "SELECT SUM(number_mail)       $end";

        // Выполняем SQL-запросы
        foreach($query as $search => $value)
        {
            $hits[$search] += query_result($value);
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
                 $hits['mil'],
                 $total);
}

?>