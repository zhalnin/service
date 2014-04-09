<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 05.06.12
 * Time: 21:46
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

$title = 'Управление&nbsp;базой&nbsp;данных';
$pageinfo = 'На этой странице вы можете управлять объемом базы данных.
            Довести до нуля объем таблицы невозможно,
            так как мета-данные (структура таблиц) также занимают
            определенный объем на жестком диске.';
try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    $array[] = array("name"         => $tbl_ip,
                     "description"  =>"Количество всех посещений",
                     "value"        => get_value_table($tbl_ip));
    $array[] = array("name"         => $tbl_pages,
                     "description"  =>"Страницы, участвующие в статистике",
                     "value"        => get_value_table($tbl_pages));
    $array[] = array("name"         => $tbl_thits,
                     "description"  =>"Временная таблица, для внутренних операций",
                     "value"        => get_value_table($tbl_thits));
    $array[] = array("name"         => $tbl_refferer,
                     "description"  =>"Рефереры - адреса страниц сторонних сайтов, с которых был осуществлен переход на ваш сайт",
                     "value"        => get_value_table($tbl_refferer));
    $array[] = array("name"         => $tbl_searchquerys,
                     "description"  =>"Ключевые слова, по которым ваш сайт был обнаружен в поисковых системах",
                     "value"        => get_value_table($tbl_searchquerys));
    $array[] = array("name"         => $tbl_cities,
                     "description"  =>"Города (для определения местоположения по IP-адресу)",
                     "value"        => get_value_table($tbl_cities));
    $array[] = array("name"         => $tbl_ip_compact,
                     "description"  =>"Соответствие IP-адресов городам (для определения местоположения по IP-адресу)",
                     "value"        => get_value_table($tbl_ip_compact));
    $array[] = array("name"         => $tbl_regions,
                     "description"  =>"Регион (для определения местоположения по IP-адресу)",
                     "value"        => get_value_table($tbl_regions));
    $array[] = array("name"         => $tbl_arch_hits,
                     "description"  =>"Суточная архивная таблица для хитов и хостов",
                     "value"        => get_value_table($tbl_arch_hits));
    $array[] = array("name"         => $tbl_arch_ip,
                     "description"  =>"Суточная архивная таблица для IP-адресов",
                     "value"        => get_value_table($tbl_arch_ip));
    $array[] = array("name"         => $tbl_arch_clients,
                     "description"  =>"Суточная архивная таблица для браузеров и операционных систем",
                     "value"        => get_value_table($tbl_arch_clients));
    $array[] = array("name"         => $tbl_arch_robots,
                     "description"  =>"Суточная архивная таблица для роботов поисковых систем",
                     "value"        => get_value_table($tbl_arch_robots));
    $array[] = array("name"         => $tbl_arch_refferer,
                     "description"  =>"Суточная архивная таблица для рефереров - адресов страниц сторонних сайтов, с которых был осуществлен переход на ваш сайт",
                     "value"        => get_value_table($tbl_arch_refferer));
    $array[] = array("name"         => $tbl_arch_searchquery,
                     "description"  =>"Суточная архивная таблица для поисковых запросов",
                     "value"        => get_value_table($tbl_arch_searchquery));
    $array[] = array("name"         => $tbl_arch_num_searchquery,
                     "description"  =>"Суточная архивная таблица для количества поисковых запросов",
                     "value"        => get_value_table($tbl_arch_num_searchquery));
    $array[] = array("name"         => $tbl_arch_enterpoint,
                     "description"  =>"Суточная архивная таблица для точек входа",
                     "value"        => get_value_table($tbl_arch_enterpoint));
    $array[] = array("name"         => $tbl_arch_deep,
                     "description"  =>"Суточная архивная таблица для глубины просмотра",
                     "value"        => get_value_table($tbl_arch_deep));
    $array[] = array("name"         => $tbl_arch_time,
                     "description"  =>"Суточная архивная таблица для времени сеанса",
                     "value"        => get_value_table($tbl_arch_time));
    $array[] = array("name"         => $tbl_arch_time_temp,
                     "description"  =>"Временная таблица для архивации времени сеанса",
                     "value"        => get_value_table($tbl_arch_time_temp));
    $array[] = array("name"         => $tbl_arch_hits_week,
                     "description"  =>"Недельная архивная таблица для хитов и хостов",
                     "value"        => get_value_table($tbl_arch_hits_week));
    $array[] = array("name"         => $tbl_arch_ip_week,
                     "description"  =>"Недельная архивная таблица для IP-адресов",
                     "value"        => get_value_table($tbl_arch_ip_week));
    $array[] = array("name"         => $tbl_arch_clients_week,
                     "description"  =>"Недельная архивная таблица для для браузеров и операционных систем",
                     "value"        => get_value_table($tbl_arch_clients_week));
    $array[] = array("name"         => $tbl_arch_robots_week,
                     "description"  =>"Недельная архивная таблица для роботов поисковы систем",
                     "value"        => get_value_table($tbl_arch_robots_week));
    $array[] = array("name"         => $tbl_arch_refferer_week,
                     "description"  =>"Недельная архивная таблица для рефереров - адресов страниц сторонних сайтов, с которых был осуществлен переход на ваш сайт",
                     "value"        => get_value_table($tbl_arch_refferer_week));
    $array[] = array("name"         => $tbl_arch_searchquery_week,
                     "description"  =>"Недельная архивная таблица для поисковых запросов",
                     "value"        => get_value_table($tbl_arch_searchquery_week));
    $array[] = array("name"         => $tbl_arch_num_searchquery_week,
                     "description"  =>"Недельная архивная таблица для количества поисковых запросов",
                     "value"        => get_value_table($tbl_arch_num_searchquery_week));
    $array[] = array("name"         => $tbl_arch_enterpoint_week,
                     "description"  =>"Недельная архивная таблица для точек входа",
                     "value"        => get_value_table($tbl_arch_enterpoint_week));
    $array[] = array("name"         => $tbl_arch_deep_week,
                     "description"  =>"Недельная архивная таблица для глубины просмотра",
                     "value"        => get_value_table($tbl_arch_deep_week));
    $array[] = array("name"         => $tbl_arch_time_week,
                     "description"  =>"Недельная архивная таблица для времени сеанса",
                     "value"        => get_value_table($tbl_arch_deep_week));
    $array[] = array("name"         => $tbl_arch_hits_month,
                     "description"  =>"Месячная архивная таблица для хитов и хостов",
                     "value"        => get_value_table($tbl_arch_hits_month));
    $array[] = array("name"         => $tbl_arch_ip_month,
                     "description"  =>"Месячная архивная таблица для IP-адресов",
                     "value"        => get_value_table($tbl_arch_ip_month));
    $array[] = array("name"         => $tbl_arch_clients_month,
                     "description"  =>"Месячная архивная таблица для браузеров и операционных систем",
                     "value"        => get_value_table($tbl_arch_clients_month));
    $array[] = array("name"         => $tbl_arch_robots_month,
                     "description"  =>"Месячная архивная таблица для роботов",
                     "value"        => get_value_table($tbl_arch_robots_month));
    $array[] = array("name"         => $tbl_arch_refferer_month,
                     "description"  =>"Месячная архивная таблица для рефереров - адресов страниц сторонних сайтов, с которых был осуществлен переход на ваш сайт",
                     "value"        => get_value_table($tbl_arch_refferer_month));
    $array[] = array("name"         => $tbl_arch_searchquery_month,
                     "description"  =>"Месячная архивная таблица для поисковых запросов",
                     "value"        => get_value_table($tbl_arch_refferer_month));
    $array[] = array("name"         => $tbl_arch_num_searchquery_month,
                     "description"  =>"Месячная архивная таблица для количества поисковых запросов",
                     "value"        => get_value_table($tbl_arch_num_searchquery_month));
    $array[] = array("name"         => $tbl_arch_enterpoint_month,
                     "description"  =>"Месячная архивная таблица для точек входа",
                     "value"        => get_value_table($tbl_arch_enterpoint_month));
    $array[] = array("name"         => $tbl_arch_deep_month,
                     "description"  =>"Месячная архивная таблица для глубины просмотра",
                     "value"        => get_value_table($tbl_arch_deep_month));
    $array[] = array("name"         => $tbl_arch_time_month,
                     "description"  =>"Месячная архивная таблица для времени сеанса",
                     "value"        => get_value_table($tbl_arch_time_month));
?>
    <p class=help>Актуальные таблицы (<a href=database.clear.php?part=actual>очистить</a>) </p>
    <table class="table"
           width="100%"
           border="0"
           cellpadding="0"
           cellspacing="0">
        <tr class="header" align="center">
            <td>База данных</td>
            <td>Описание</td>
            <td>Объем</td>
        </tr>
<?php
    for($i = 0; $i < 5; $i++)
    {
        $position = $array[$i];
        echo "<tr>";
            echo "<td><p>$position[name]</p></td>";
            echo "<td><p>$position[description]</p></td>";
            echo "<td align=center><p>".valuesize($position['value'])."</p></td>";
        echo "</tr>";
    }
    echo "</table>";
?>
   <p class=help>Вспомогательные таблицы</p>
   <table class="table"
          width="100%"
          border="0"
          cellpadding="0"
          cellspacing="0">
       <tr class="header" align="center">
           <td>База данных</td>
           <td>Описание</td>
           <td>Объем</td>
       </tr>
<?php
    for($i = 5; $i < 8; $i++)
   {
       $position = $array[$i];
       echo "<tr>";
        echo "<td><p>$position[name]</p></td>";
        echo "<td><p>$position[description]</p></td>";
        echo "<td align=center><p>".valuesize($position['value'])."</p></td>";
       echo "</tr>";
   }
   echo "</table>";
?>
<p class=help>Архивные таблицы (<a href=database.clear.php?par=archive>очистить</a>)</p>
<table class="table"
       width="100%"
       border="0"
       cellpadding="0"
       cellspacing="0">
    <tr class="header" align="center">
        <td>База данных</td>
        <td>Описание</td>
        <td>Объемы</td>
    </tr>
<?php
for($i = 8; $i < count($array); $i++)
{
    $position = $array[$i];
    echo "<tr>";
        echo "<td><p>$position[name]</p></td>";
        echo "<td><p>$position[description]</p></td>";
        echo "<td align=center><p>".valuesize($position['value'])."</p></td> ";
    echo "</tr>";
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