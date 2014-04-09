<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 05.06.12
 * Time: 19:43
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

$title = 'Страницы просмотренные с IP адреса';
$pageinfo = 'На этой странице вы можете видеть
                страницы просмотренные с IP-адреса.';
try {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Включаем массив временных интервалов
    require_once("time_interval.php");

    $ip = $_GET['ip'];
    if(!isset($_GET['nav'])) $nav=1;
    else $nav=$_GET['nav'];
    if($nav == 0) {
        $hit = " COUNT($tbl_ip.id_ip) AS hits,";
        $groupby = " GROUP BY $tbl_pages.name ";
    } else {
        $hit = "";
        $groupby = "";
    }
    echo "<a href='pages.php";
    if($nav == 0) echo "?nav=1&ip=$ip'>Навигация";
    else echo "?nav=0&ip=$ip'>Статистика";
    echo "</a><br><br>";

    // Формируем WHERE-условие для временного интервала
    $where = where_interval();

    if($ip == "robot_rambler" ||
       $ip == "robot_yandex" ||
       $ip == "robot_google" ||
       $ip == "robot_aport" ||
       $ip == "robot_msnbot" ||
       $ip == "robot_total" ||
       $ip == "none") {
        if($ip == "robot_total") {
            $wherehit = " (systems LIKE 'robot_%' OR systems='none') ";
        } else {
            $wherehit = " systems = '$ip' ";
        }
    } else {
        $wherehit = " $tbl_ip.ip = INET_ATON('$ip') ";
    }

    // Элемент постраничной навигации
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];

    $page_link = 3;
    $start = ($page - 1)*$pnumber;
    if($nav) {
        $query = "SELECT COUNT(*)
                    FROM $tbl_ip, $tbl_pages
                    $where AND $tbl_ip.id_page = $tbl_pages.id_page AND $wherehit
                    ORDER BY putdate DESC";
    } else {
        $query = "SELECT COUNT(DISTINCT($tbl_pages.id_page))
                    FROM $tbl_ip, $tbl_pages
                    $where AND $tbl_ip.id_page = $tbl_pages.id_page AND $wherehit
                    ORDER BY putdate DESC";

    }
    $total = query_result($query);

    // Выводим ссылки на другие страницы
    pager($page,
            $total,
            $pnumber,
            $page_link,
            "&begin=$begin&end=$end&ip=$ip&id_page=$id_page&nav=$nav");
    echo "<br>";

    $query = "SELECT $tbl_pages.name,
                    $tbl_pages.title AS title,
                    $tbl_pages.id_page,
                    $hit
                    $tbl_ip.putdate AS putdate
               FROM $tbl_ip, $tbl_pages
               $where AND $tbl_ip.id_page = $tbl_pages.id_page AND $wherehit
               $groupby
               ORDER BY putdate DESC
               LIMIT $start, $pnumber";

    $pag = mysql_query($query);
    if(!$pag)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении
                                к таблице IP-адресов");
    }
    if(mysql_num_rows($pag))
    {
?>
        <table class="table"
               width="90%"
               border="0"
               cellpadding="0"
               cellspacing="0">
<?php
        echo "<tr class=header align=center>
                <td>№</td>
                <td>Страница</td>";
        if($nav == 0) echo "<td class=headtable>Просмотров</p></td> ";
        echo "<td>Последнее обращение</td></tr>";

        $i = $start + 1;
        while($page = mysql_fetch_array($pag))
        {
            echo "<tr>
                    <td>$i</td>
                    <td><a href=hits.php?id_page=$page[id_page]>$page[title]</td>";
            if($nav == 0)
            {
                echo "<td>$page[hits]</td>";
            }
            echo "<td align=center>$page[putdate]</td></tr>";
            $i++;
        }
        echo "</table>";
    }
    // Включаем завершение страницы
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
?>