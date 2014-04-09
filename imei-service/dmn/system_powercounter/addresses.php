<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 05.06.12
 * Time: 18:54
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


$title = 'IP&nbsp;адреса';
$pageinfo = 'На этой странице вы можете видеть IP-адреса
посетителей, соответствующие этим адресам доменные имена
хостов, количество обращений с данного IP-адреса, процент
обращений с этого IP-адреса от общего количества обращений
и последнее время обращения с этого IP-адреса. Нажав на
подсвеченный IP-адрес можно получить информацию о том,
на кого он зарегистрирован. ';

try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Запрашиваем уникальные IP-адреса из базы данных
    // отсортированные по времени
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];
    // Вычисляем начало вывода
    $begin = ($page - 1) * $pnumber;

    $tmp = "";
    if(!empty($_GET['id_page']))
        $tmp = " AND id_page=$_GET[id_page]";

    $page_link = 3;
    // Определяем число посетителей с уникальными IP-адресами
    // за последние сутки
    $query = "SELECT COUNT(distinct ip) FROM $tbl_ip
                WHERE systems != 'none' AND
                      systems != 'robot_yandex' AND
                      systems != 'robot_google' AND
                      systems != 'robot_rambler' AND
                      systems != 'robot_aport' AND
                      systems != 'robot_msnbot'";

//    $query = "SELECT COUNT(distinct ip) FROM $tbl_ip
//                WHERE systems != 'none' AND
//                      systems != 'robot_yandex' AND
//                      systems != 'robot_google' AND
//                      systems != 'robot_rambler' AND
//                      systems != 'robot_aport' AND
//                      systems != 'robot_msnbot' AND
//                      putdate LIKE CONCAT(DATE_FORMAT(NOW(), '%Y-%m-%d'), '%') $tmp";
    $total = query_result($query);
    // Выводим ссылки на другие страницы
    pager($page,
          $total,
          $pnumber,
          $page_link,
          "&id_page=$_GET[id_page]");
    echo "<br>";

    // Выводим сами IP-адреса
    ?>
        <br><br><table class="table" border="0" cellpadding="0" cellspacing="0">
                    <tr class="header" align="center">
                        <td>№</td>
                        <td>IP-адрес</td>
                        <td>Хост</td>
                        <td>Регион</td>
                        <td>Город</td>
                        <td>Всего посещений</td>
                        <td>Последнее&nbsp;обращений</td>
                    </tr>
<?php

// Формируем и выполняем SQL-запрос, извлекающий
$query = "SELECT INET_NTOA(ip) AS ip,
                max(putdate) AS putdate,
                count(id_ip) AS hits FROM $tbl_ip
          WHERE
          systems != 'none' AND
          systems != 'robot_yandex' AND
          systems != 'robot_google' AND
          systems != 'robot_rambler' AND
          systems != 'robot_asport' AND
          systems != 'robot_msnbot'
          GROUP BY ip
          ORDER BY  putdate DESC
          LIMIT $begin, $pnumber";

//    $query = "SELECT INET_NTOA(ip) AS ip,
//                max(putdate) AS putdate,
//                count(id_ip) AS hits FROM $tbl_ip
//          WHERE
//          systems != 'none' AND
//          systems != 'robot_yandex' AND
//          systems != 'robot_google' AND
//          systems != 'robot_rambler' AND
//          systems != 'robot_asport' AND
//          systems != 'robot_msnbot' AND
//          putdate LIKE CONCAT(DATE_FORMAT(NOW(),'%Y-%m-%d'), '%') $tmp
//          GROUP BY ip
//          ORDER BY  putdate DESC
//          LIMIT $begin, $pnumber";

    $ips = mysql_query($query);

    if(!$ips)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении
                                к таблице IP-адресов");
    }
    if(mysql_num_rows($ips) > 0)
    {
        $i=1;
        while($ip = mysql_fetch_array($ips))
        {
            $query = "SELECT city_name, region_name
                        FROM $tbl_ip_compact, $tbl_cities, $tbl_regions
                        WHERE INET_ATON('$ip[ip]') BETWEEN init_ip AND end_ip AND
                              $tbl_cities.city_id = $tbl_ip_compact.city_id AND
                              $tbl_cities.region_id = $tbl_regions.region_id";

            $reg = mysql_query($query);
            if(!$reg)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при определении
                                        местоположения IP-адреса");
            }
            $region = mysql_fetch_array($reg);
            echo "<tr>
                    <td>$i</td>
                    <td><a href='pages.php?nav=1&ip=$ip[ip]'>$ip[ip]</a></td>";
            if(HOST_BY_ADDR) echo "<td>".(@gethostbyaddr($ip['ip']))."</td>";
            else echo "<td align=center>-</td>";
            if($region['city_name'])
                echo "<td>$region[city_name]</td>";
            else echo "<td>нет данных</td>";
            if($region['region_name'])
                echo "<td>$region[region_name]</td>";
            else echo "<td>нет данных</td>";
            echo "<td>$ip[hits]</td><td>$ip[putdate]</td>";
            $i++;
        }
    }
            echo "</table>";
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