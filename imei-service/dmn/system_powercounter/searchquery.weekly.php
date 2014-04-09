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
    $query = "SELECT COUNT(*) FROM $tbl_arch_num_searchquery_week";
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
                   number_yandex,
                   number_rambler,
                   number_google,
                   number_aport,
                   number_msn,
                   number_mail
               FROM $tbl_arch_num_searchquery_week
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
                    <td align=center width=".(100/7)."%>Дата</td>
                    <td align=center width=".(100/7)."%>Yandex</td>
                    <td align=center width=".(100/7)."%>Rambler</td>
                    <td align=center width=".(100/7)."%>Google</td>
                    <td align=center width=".(100/7)."%>Aport</td>
                    <td align=center width=".(100/7)."%>MSN</td>
                    <td align=center width=".(100/7)."%>Mail.ru</td>
                </tr> ";
        while($hits = mysql_fetch_array($arh))
        {
            // Формируем дату
           $date_table = date("d.m",$hits['putdate_begin'])." - ".date("d.m",$hits['putdate_end']);
            echo "<tr>
                     <td align=center>$date_table</td>
                     <td align=center>$hits[number_yandex]</td>
                     <td align=center>$hits[number_rambler]</td>
                     <td align=center>$hits[number_google]</td>
                     <td align=center>$hits[number_aport]</td>
                     <td align=center>$hits[number_msn]</td>
                     <td align=center>$hits[number_mail]</td>
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
