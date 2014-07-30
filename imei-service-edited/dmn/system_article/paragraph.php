<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 21.04.12
 * Time: 22:19
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Навигационное меню
require_once("../utils/utils.navigation.php");
// Обработка текста перед выводом
require_once("../utils/utils.print_page.php");

// Защита от SQL-инъекции
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_catalog'] = intval($_GET['id_catalog']);
//echo "<tt><pre>".print_r($_REQUEST, true)."</pre></tt>";
try
{
    // Извлекаем информацию о разделе
    $query = "SELECT * FROM $tbl_catalog
                WHERE id_catalog = $_GET[id_catalog]
                LIMIT 1";
    $cat = mysql_query($query);
    if(!$cat)
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при обращении
            к каталогу");
    }
    $catalog = mysql_fetch_array($cat);
    // Извлекаем информацию об элементе
    $query = "SELECT * FROM $tbl_position
            WHERE id_position=$_GET[id_position]";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка при обращении
            к позиции");
    }
    $position = mysql_fetch_array($pos);

    $title = $titlepage = 'Позиция ('.$catalog['name'].
        ' - '.$position['name'].')';
    $pageinfo = '<p class=help>Здесь осуществляется
                администрирование позиции ('.$catalog['name'].
        ' - '.$position['name'].').
                Параграф может представлять собой как обычный
                текстовый абзац, так и заголовок. Возможно
                использование шести уроней заголовков
                (H1, H2, H3, H4, H5, H6), H1 - самый крупный
                заголовок, применяемый обычно для названия
                страниц; далее заголовки уменьшаются в
                размере, то есть H6 - это самый мелкий заголовок.</p>';

    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество элементов на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new PagerMysql($tbl_paragraph,
        "WHERE id_position = $_GET[id_position] AND
                                id_catalog = $_GET[id_catalog]",
        "ORDER BY pos",
        $pnumber,
        $page_link,
        "&id_position=$_GET[id_position]&".
            "id_catalog=$_GET[id_catalog]");

    // Получаем содержимое текущей страницы
    $paragraph = $obj->get_page();
//echo $_GET['id_position'];
//    echo $_GET['id_catalog'];

    // Включаем заголовок страницы
    require_once("../utils/top.php");

    // Если это не корневой раздел, выводим ссылки для возврата
    // и для добавления подраздела
    if($_GET['id_catalog'] != 0)
    {
        echo '<table cellpadding="0" cellspacing="0" border="0">

                  <tr valign="top">
                    <td height="25"><p>';
        echo "<a class=menu href=index.php?id_parent=0>
                Корневой каталог</a>-&gt;".
            menu_navigation($_GET['id_catalog'],
                "",
                $tbl_catalog) .$position['name'];
        echo "</td>
                </tr>
              </table>";
    }

    // Добавить параграф
    echo "<form action=paradd.php>";
    echo "<input class='button'
                type='submit'
                value='Добавить параграф'><br/><br/>";
    echo "<input type='hidden' name='page' value='$_GET[page]'><br/><br/>";
    // Выводим заголовок таблицы разделов
    echo "<input type='hidden'
                 name='id_catalog'
                 value=$_GET[id_catalog]>";
    echo "<input type='hidden'
                 name='id_position'
                 value=$_GET[id_position]>";
    echo "<table width='100%'
                 class='table'
                 border='0'
                 cellpadding='0'
                 cellspacing='0'>
             <tr class='header' align='center'>
                <td width='20' align='center'>
                    <input type='radio' name='pos' value='-1' checked>
                </td>
                <td align='center'>Содержимое</td>
                <td width='100' align='center'>Изображение<br/> и файлы</td>
                <td width='100' align='center'>Тип</td>
                <td width='20' align='center'>Поз.</td>
                <td width='50'>Действия</td>
             </tr>";

    if(!empty($paragraph))
    {
        for($i = 0; $i < count($paragraph); $i++)
        {
            $url = "id_paragraph={$paragraph[$i][id_paragraph]}".
                "&id_position=$_GET[id_position]&".
                "id_catalog=$_GET[id_catalog]".
                "&page=$_GET[page]";
            // Выясняем тип параграфа
            $type = "Параграф";
            switch($paragraph[$i]['type'])
            {
                case 'text':
                    $type = 'Параграф';
                    break;
                case 'title_h1':
                    $type = "Заголовок H1";
                    break;
                case 'title_h2':
                    $type = "Заголовок H2";
                    break;
                case 'title_h3':
                    $type = "Заголовок H3";
                    break;
                case 'title_h4':
                    $type = "Заголовок H4";
                    break;
                case 'title_h5':
                    $type = "Заголовок H5";
                    break;
                case 'title_h6':
                    $type = "Заголовок H6";
                    break;
                case 'list':
                    $type = "Список";
                    break;
            }
            // Выясняем тип выравнивания параграфа
            $aligh = "";
            switch($paragraph[$i]['align'])
            {
                case 'left':
                    $align = "align=left";
                    break;
                case 'center':
                    $align = "align=center";
                    break;
                case 'right':
                    $align = "align=right";
                    break;
            }
            // Выясняем скрыт раздел или нет
            if($paragraph[$i]['hide'] == 'hide')
            {
                $strhide = "<a href=parshow.php?$url>Отобразить</a>";
                $style="class=hidden";
            }
            else
            {
                $strhide = "<a href=parhide.php?$url>Скрыть</a>";
                $style = "";
            }
            // Вычисляем, сколько изображений у данного элемента
            $query = "SELECT COUNT(*) FROM $tbl_paragraph_image
                        WHERE id_paragraph = {$paragraph[$i][id_paragraph]} AND
                              id_position = $_GET[id_position] AND
                              id_catalog = $_GET[id_catalog]";
            $tot = mysql_query($query);

            if(!$tot)
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при подсчете
                    количества изображений");
            }
            $total_image = mysql_result($tot, 0);
            if($total_image) $print_image = " ($total_image)";
            else $print_image = "";
//            echo "<tt><pre>".print_r($paragraph[$i]['name'], true)."</pre></tt>";
            echo "<tr $style $class>
                    <td align=center>
                        <input type=radio name=pos value=".$paragraph[$i]['pos']." />
                    </td>
                    <td><p $align>".
                nl2br(print_page($paragraph[$i]['name'])).
                "</p></td>";
            if( $total_image > 0 ) {
              echo "<td align=center>
                        <a href=\"show.php?$url\">Изображения$print_image</a>
                    </td>";
            } else {
                echo "<td align=center>
                        <p>Изображений нет</p>
                    </td>";
            }



            echo "<td align=center>".print_page($type)."</td>
                    <td align=center>".$paragraph[$i]['pos']."</td>
                    <td>
                        <a href=parup.php?$url>Вверх</a><br>
                    $strhide<br>
                    <a href=paredit.php?$url>Редактировать</a><br>
                    <a href=# onClick=\"delete_position('pardel.php?$url',".
                "'Вы действительно хотите удалить параграф');\">Удалить</a><br>
                    <a href=pardown.php?$url>Вниз</a></td>
                </tr>";
         }
    }
    echo "</table><br><br>";
    echo "</form>";
    // Выводим ссылки на другие страницы
    echo $obj;
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}

// Включаем завершение страницы
require_once("../utils/bottom.php");
?>
<!--<script type="text/javascript" language="javascript">-->
<!--    <!---->
<!--            function delete_par(url)-->
<!--            {-->
<!--                if(confirm("Вы действительно хотите удалить параграф?"))-->
<!--                {-->
<!--                    location.href=url;-->
<!--                }-->
<!--                return false;-->
<!--            }-->
<!--    //-->
<!--</script>-->
<script type="text/javascript">
    <!--
    function show_img(id_position, width, height)
    {
        var a;
        var b;
        var url;
        vidWindowWidth = width;
        vidWindowHeight = height;
        a = (screen.height-vidWindowHeight)/5;
        b = (screen.width-vidWindowWidth)/2;
        features = "top="+a +",left="+b+
            ",width="+vidWindowWidth+
            ",height="+vidWindowHeight+
            ",toolbar=no,menubar=no,location=no,"+
            "directories=no,scrollbars=no,resizable=no";
        url = "../../show.php?id_position="+id_position;
        window.open(url,'',features,true);
    }
    //-->
</script>