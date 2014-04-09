<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.06.12
 * Time: 22:00
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
// Постраничная навигация
require_once("../utils/utils.pager.php");
// Формирование WHERE-условий
require_once("utils.where.php");
// Выполнение запроса
require_once("utils.query_result.php");

// Данные переменные определяют название страницы и подсказку
$title = 'Помесячный отчет';
try
{
    // Заголовок страницы
    require_once("../utils/topcounter.php");

    // Постраничная навигация
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];

    // Извлекаем количество страниц
    $query = "SELECT COUNT(DISTINCT putdate)
              FROM $tbl_arch_ip_month";
    $total = query_result($query);

    $page_link = 3;
    $first = ($page - 1)*$pnumber;

    // Выводим ссылки на другие страницы
    pager($page,
          $total,
          $pnumber,
          $page_link,
          "");
    echo "<br><br>";

    // Извлекаем данные для текущей страницы
    $query = "SELECT UNIX_TIMESTAMP(putdate) AS putdate
               FROM $tbl_arch_ip_month
               GROUP BY putdate
               ORDER BY putdate DESC";
    $arh = mysql_query($query);
    if(!$arh)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка извлечения месячной статистики");
    }
    if(mysql_num_rows($arh))
    {
        echo "<table class=table width=100% cellpadding=0 cellspacing=0>
                <tr class=header align=center>
                    <td align=center width=50%>Дата</td>
                    <td align=center width=50%>Ссылка</td>
                </tr> ";
        while($hits = mysql_fetch_array($arh))
        {
            // Формируем дату
           $date_table = date("Y.m",$hits['putdate']);
            echo "<tr>
                     <td align=center>$date_table</td>
                     <td align=center><a href=$_SERVER[PHP_SELF]?date=$hits[putdate]>смотреть</a></td>
                   </tr>";
        }
        echo "</table><br><br>";
    }
    // Если параметр $_GET['date'] не пуст, запрашиваем IP-адрес
    // за неделю
    if(!empty($_GET['date']))
    {
        $_GET['date'] = intval($_GET['date']);
        $query = "SELECT INET_NTOA(ip) AS ip, total
                    FROM $tbl_arch_ip_month
                    WHERE putdate LIKE '".date("Y-m",$_GET['date'])."%'";
        $ipt = mysql_query($query);
        if(!$ipt)
        {
            throw new ExceptionMySQL(mysql_error(),
                                     $query,
                                     "Ошибка извлечения недельной статистики");
        }
        if(mysql_num_rows($ipt))
        {
            echo "<table class=table
                         width=100%
                         border=0
                         cellpadding=0
                         cellspacing=0>
                     <tr class=header align=center>
                        <td>IP-адрес</td>
                        <td>Хост</td>
                        <td>Число обращений</td>
                     </tr>";
            while($ip = mysql_fetch_array($ipt))
            {
                echo "<tr><td>$ip[ip]</td>";
                if(HOST_BY_ADDR) echo "<td align=center>".(@gethostbyaddr($ip['ip']))."</td>";
                else echo "<td align=center>-</td>";
                echo "<td align=center>$ip[total]</td></tr>";
            }
            echo "</table>";
        }
    }

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
?>
