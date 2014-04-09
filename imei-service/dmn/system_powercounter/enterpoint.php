<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.06.12
 * Time: 23:57
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
// Формирование WHERE-условий
require_once("utils.where.php");
// Выполнение запроса
require_once("utils.query_result.php");

if(!isset($_GET['begin'])) $begin = 1;
else $begin = intval($_GET['begin']);
if(!isset($_GET['nav'])) $nav = 0;
else $nav = intval($_GET['nav']);

if($nav == 0)
{
    $dateofput = "min(putdate)";
    $title = 'Точки входа';
    $pageinfo = 'На этой странице вы можете видеть через
    какие страницы посетители входили на ваш ресурс.';
}
else
{
    $dateofput = "max(putdate)";
    $title = 'Точки входа';
    $pageinfo = 'На этой странице вы можете видеть на
    какой странице посетители покидали ваш ресурс.';
}

try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");
?>
<table border=0
       width=100%>
    <tr valin=top>
        <td width=30%>
<?php
    echo "<a href='enterpoint.php";
    if($nav == 0) echo "?nav=1'>Точки выхода";
            else echo "?nav=0'>Точки входа";
    echo "</a>";
?>
        </td>
        <td align=left>
            <table class="table"
                   width="60%"
                   border="0"
                   cellpadding="0"
                   cellspacing="0">
                <tr class="header" align="center">
                    <td>Страница</td>
                    <td>
                        <?php if($nav==0) print "Входов";
                              else print "Выходов";
                        ?>
                    </td></tr>
<?php
    $pages = array();
    for($i = $begin; $i > $end; $i--)
    {
        // Формируем WHERE-условие для временного интервала
        $where = where_interval($begin, $end);
        // Запрос вытаскивающий из базы все точки входа за сутки
        $query = "SELECT ip, $dateofput AS putdate
                   FROM $tbl_ip
                $where AND systems != 'none' AND
                           systems != 'robot_yandex' AND
                           systems != 'robot_google' AND
                           systems != 'robot_rambler' AND
                           systems != 'robot_aport' AND
                           systems != 'robot_msn'
                GROUP BY ip";
        $pag = mysql_query($query);
        if(!$pag)
        {
            throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                    "Ошибка при обращении
                                    к таблице страниц");
        }
        if(mysql_num_rows($pag))
        {
            // Перегоняем точки входа в отдельный массив,
            // этот массив будет прогоняться столько раз,
            // за сколько суток будет статистика.
            while($page = mysql_fetch_array($pag))
            {
                $query = "SELECT id_page FROM $tbl_ip
                            WHERE ip='$page[ip]' AND
                                  putdate='$page[putdate]'";
                $pages[] = query_result($query);
            }
        }
    }
    // Получаем массив с названиями, id и адресами страниц
    $query = "SELECT * FROM $tbl_pages";
    $pag = mysql_query($query);

    if(!$pag)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении
                                к таблице страниц");
    }
    $names = array();
    if(mysql_num_rows($pag))
    {
        while($page = mysql_fetch_array($pag))
        {
            $names[$page['id_page']]['name'] = $page['name'];
            $names[$page['id_page']]['title'] = $page['title'];
        }
    }
    // Считаем количество входов на каждую страницу
    if(isset($pages))
    {
        $points = array_count_values($pages);
        // Сортируем по количеству входов в обратном порядке
        arsort($points);
        // Выводим таблицу, где будет название стрницы и
        // количество заходов через нее
        if(count($points)>0)
            foreach($points as $id => $hits)
            {
                if(count($names) > 0)
                foreach($names as $id2 => $name)
                {
                    if($id == $id2)
                    {
                        $link = "<a href=http://{$_SEVER[SERVER_NAME]}".
                                "{$names[$id][name]} target=_blank ".
                                "title = 'Открыть страницу сайта'".
                                ">http://{$_SERVER[SERVER_NAME]}{$names[$id][name]}</a>";
                        print "<tr><td>$link</td><td>$hits</td></tr>";
                    }
                }

            }

//        // Выводим нулевые позиции
//        if(count($points) > 0)
//        {
//            $arr[] = "0";
//            foreach($points as $id => $hits) $arr[] = $id;
//        }
//        $query = "SELECT * FROM $tbl_pages
//                    WHERE id_page NOT IN (".implode(",",$arr)."0)";
//        $pag = mysql_query($query);
//        if(!$pag)
//        {
//            throw new ExceptionMySQL(mysql_error(),
//                                    $query,
//                                    "Ошибка при обращении
//                                    к таблице страниц");
//        }
//        if(mysql_num_rows($pag))
//        {
//            while($zero = mysql_fetch_array($query))
//            {
//                $link = "<a href=http://{$_SERVER[SERVER_NAME]}".
//                        "$zero[name] target=_blank ".
//                        "title='Открыть страницу сайта'".
//                        ">http://{$_SERVER[SERVER_NAME]}{$zero[name]}</a>";
//                print "<tr>$link<td></td><td>0</td></tr>";
//            }
//        }

    }


    echo "</table>";
    echo "</td></tr></table>";

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