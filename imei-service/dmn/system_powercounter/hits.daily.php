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
$title = 'Посуточный отчет';
try
{
    // Заголовок страницы
    require_once("../utils/topcounter.php");

    // Постраничная навигация
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];

    // Извлекаем количество страниц
    $query = "SELECT COUNT(*) FROM $tbl_arch_hits";
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
    $query = "SELECT UNIX_TIMESTAMP(putdate) AS putdate,
                    hosts_total,
                    host,
                    hits_total,
                    hits
               FROM $tbl_arch_hits
               ORDER BY putdate DESC
               LIMIT $first, $pnumber";
    $arh = mysql_query($query);
    if(!$arh)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка извлечения суточной статистики");
    }
    if(mysql_num_rows($arh))
    {
        echo "<table class=table width=100% cellpadding=0 cellspacing=0>
                <tr class=header align=center>
                    <td align=center width=20%>Дата</td>
                    <td align=center width=20%>Засчитанные хосты</td>
                    <td align=center width=20%>Хосты</td>
                    <td align=center width=20%>Засчитанные хиты</td>
                    <td align=center width=20%>Хиты</td>
                </tr> ";
        $eng = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
        $rus = array("Пн","Вт","Ср","Чт","Пт","Сб","Вс");
        while($hits = mysql_fetch_array($arh))
        {
            // Формируем дату
            if(date("w",$hits['putdate']) == 0 || date("w",$hits['putdate']) == 6)
            {
                // Суббота и воскресенье
                $date_table = "<div style='color:red'>".date("d.m D",$hits['putdate'])."</div> ";
            }
            else
            {
                // Будни
                $date_table = date("d.m D", $hits['putdate']);
            }
            // Меняем английские названия дней недели на русские
            $date_table = str_replace($eng,$rus,$date_table);
            echo "<tr>
                     <td align=center>$date_table</td>
                     <td align=center>$hits[host]</td>
                     <td align=center>$hits[hosts_total]</td>
                     <td align=center>$hits[hits]</td>
                     <td align=center>$hits[hits_total]</td>
                   </tr>";
        }
        echo "</table>";
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
