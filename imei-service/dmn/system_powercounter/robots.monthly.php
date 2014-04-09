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
    $query = "SELECT COUNT(*) FROM $tbl_arch_robots_month";
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
    $query = "SELECT DATE_FORMAT(putdate, '%Y.%m') AS putdate,
                   yandex,
                   rambler,
                   google,
                   aport,
                   msn,
                   none
               FROM $tbl_arch_robots_month
               ORDER BY putdate DESC
               LIMIT $first, $pnumber";
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
                    <td align=center width=".(100/7)."%>Дата</td>
                    <td align=center width=".(100/7)."%>Робот Yandex</td>
                    <td align=center width=".(100/7)."%>Робот Rambler</td>
                    <td align=center width=".(100/7)."%>Робот Google</td>
                    <td align=center width=".(100/7)."%>Робот Aport</td>
                    <td align=center width=".(100/7)."%>Робот MSN</td>
                    <td align=center width=".(100/7)."%>Другие</td>
                </tr> ";
        while($hits = mysql_fetch_array($arh))
        {
            echo "<tr>
                     <td align=center>$hits[putdate]</td>
                     <td align=center>$hits[yandex]</td>
                     <td align=center>$hits[rambler]</td>
                     <td align=center>$hits[google]</td>
                     <td align=center>$hits[aport]</td>
                     <td align=center>$hits[msn]</td>
                     <td align=center>$hits[none]</td>
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
