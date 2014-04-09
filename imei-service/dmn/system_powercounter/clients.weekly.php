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
$title = 'Понедельный отчет';
try
{
    // Заголовок страницы
    require_once("../utils/topcounter.php");

    // Постраничная навигация
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];

    // Извлекаем количество страниц
    $query = "SELECT COUNT(*) FROM $tbl_arch_clients_week";
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
    $query = "SELECT UNIX_TIMESTAMP(putdate_begin) AS putdate_begin,
                    UNIX_TIMESTAMP(putdate_end) AS putdate_end,
                    browsers_msie,
                    browsers_opera,
                    browsers_netscape,
                    browsers_firefox,
                    browsers_myie,
                    browsers_mozilla,
                    browsers_none,
                    systems_windows,
                    systems_unix,
                    systems_macintosh,
                    systems_none
               FROM $tbl_arch_clients_week
               ORDER BY putdate_begin DESC
               LIMIT $first, $pnumber";
    $arh = mysql_query($query);
    if(!$arh)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка извлечения недельной статистики");
    }
    if(mysql_num_rows($arh))
    {
        echo "<table class=table width=100% cellpadding=0 cellspacing=0>
                <tr class=header align=center>
                    <td align=center width=".(100/12)."%>Дата</td>
                    <td align=center width=".(100/12)."%>IE</td>
                    <td align=center width=".(100/12)."%>Opera</td>
                    <td align=center width=".(100/12)."%>Netscape</td>
                    <td align=center width=".(100/12)."%>Firefox</td>
                    <td align=center width=".(100/12)."%>MyIE</td>
                    <td align=center width=".(100/12)."%>Mozilla</td>
                    <td align=center width=".(100/12)."%>Неопред.</td>
                    <td align=center width=".(100/12)."%>Windows</td>
                    <td align=center width=".(100/12)."%>UNIX</td>
                    <td align=center width=".(100/12)."%>Macintosh</td>
                    <td align=center width=".(100/12)."%>Неопред.</td>
                </tr> ";
        while($hits = mysql_fetch_array($arh))
        {
            // Формируем дату
           $date_table = date("d.m",$hits['putdate_begin'])." - ".date("d.m",$hits['putdate_end']);
            echo "<tr>
                     <td align=center>$date_table</td>
                     <td align=center>$hits[browsers_msie]</td>
                     <td align=center>$hits[browsers_opera]</td>
                     <td align=center>$hits[browsers_netscape]</td>
                     <td align=center>$hits[browsers_firefox]</td>
                     <td align=center>$hits[browsers_myie]</td>
                     <td align=center>$hits[browsers_mozilla]</td>
                     <td align=center>$hits[browsers_none]</td>
                     <td align=center>$hits[systems_windows]</td>
                     <td align=center>$hits[systems_unix]</td>
                     <td align=center>$hits[systems_macintosh]</td>
                     <td align=center>$hits[systems_none]</td>
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
