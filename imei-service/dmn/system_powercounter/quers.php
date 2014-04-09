<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17.06.12
 * Time: 20:26
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
$title = 'Статистика поисковых запросов';
$pageinfo = 'На этой странице вы можете видеть статистику
по количеству поисковых запросов за различные периоды
времени. По умолчанию выводится только 20 наиболее
распространенных запросов, чтобы открывать все перейдите по
ссылке: <a href=quers.php?limit=full>Полный отчет</a>';

try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    if(empty($_GET['limit'])) $limit = "LIMIT 20";
    else $limit = "";

    // Эта переменная определяет осуществляется ли запрос к конкретной
    // странице или ко всему сайту
    $_GET['id_page'] = intval($_GET['id_page']);
    if(empty($_GET['id_page'])) $tmp = "";
    else $tmp = " AND id_page = $_GET[id_page]";

    // Формируем WHERE-условие для временного интервала
    $where = where_interval();

    // Запрос статистики поисковых слов
    $query = "SELECT query, COUNT(query) AS hits
              FROM $tbl_searchquerys
              $where $tmp
              GROUP BY query
              ORDER BY hits DESC
              $limit";
    $sch = mysql_query($query);
    if(!$sch)
    {
        throw new ExceptionMySQL(mysql_error(),
                                 $query,
                                 "Ошибка при обращении к
                                 запросам из поисковых систем");
    }
    echo '<table class="table"
                 width="60%"
                 border="0"
                 cellpadding="0"
                 cellspacing="0">';
    echo '<tr class="header"><td>Запрос</td><td>К-во переходов</td></tr>';
    // Если имеется хоть одна запись - выводим таблицу
    if(mysql_num_rows($sch) > 0)
    {
        $sum = 0;
        while($search = mysql_fetch_array($sch))
        {
            if(trim($search['query']) == "") continue;
            echo "<tr>
                <td><p>".htmlspecialchars($search['query'])."</p></td>
                <td><p>".$search['hits']."</p></td></tr>";
            $sum += $search['hits'];
        }
    }
    echo "<tr>
            <td><b>Всего переходов</b></td>
            <td><b>$sum</b></td>
          </tr></table>";
    
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