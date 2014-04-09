<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 10:00 AM
 * To change this template use File | Settings | File Templates.
 */


ob_start();
error_reporting(E_ALL & ~E_NOTICE);
?>

<table cellpadding="0" cellspacing="0">
    <tr valign="top">
        <td>
            <?php
            echo "<a class=menu
                href=urladd.php?id_parent=$_GET[id_parent]&".
                "page=$_GET[page]
                title=\"Добавить ссылку на страницу текущего
                        или любого другого сайта\">
                Добавить ссылку
          </a>&nbsp;&nbsp;&nbsp;
          <a class=menu
                href=artadd.php?id_parent=$_GET[id_parent]&".
                "page=$_GET[page]
                title=\"Добавить статью в данный раздел\">
                Добавить статью</a>";
            ?>
        </td>
    </tr>
</table><br>

<?php
try
{
    // Число ссылок в постраничной навигации
    $page_link = 3;
    // Число позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new PagerMysql($tbl_position,
        " WHERE id_catalog=$_GET[id_parent]",
        " ORDER BY pos",
        $pnumber,
        $page_link,
        "&id_parent=$_GET[id_parent]");
    // Получаем содержимое текущей страницы
    $position = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим
    if(!empty($position))
    {
        // Выводим заголовок таблицы
        echo '<table width="100%"
                    class="table"
                     border="0"
                      cellpadding="0"
                       cellspacing="0">
                <tr  class=header align="center">
                    <td align="center">Название</td>
                    <td align="center">URL</td>
                    <td width="20" align="center">Поз.</td>
                    <td width="50">Действия</td>
                </tr>';
        for($i = 0; $i < count($position); $i++)
        {
            $url = "id_position={$position[$i][id_position]}&".
                "id_catalog=$_GET[id_parent]&page=$_GET[page]";
            // Выясняем скрыта позиция или нет
            if($position[$i]['hide'] == 'hide')
            {
                $strhide = "<a href=urlshow.php?$url>Отобразить</a>";
                $style = " class=hiddenrow";
            }
            else
            {
                $strhide = "<a href=urlhide.php?$url>Скрыть</a> ";
                $style = "";
            }
            // Выясняем является ли позиция статьей или ссылкой
            if($position[$i]['url'] == 'article')
            {
                $edit = "artedit.php";
                // $url нельзя использовать из-за параметра page
                $name = "<td>
                            <p class=small>
                                <a href=paragraph.php?".
                    "id_position={$position[$i][id_position]}&".
                    "id_catalog=$_GET[id_parent]>".
                    print_page($position[$i]['name'])."</a>
                            </p>
                        </td>";


            }
            else
            {
                $edit = "urledit.php";
                $name = "<td><p class=small>".
                    print_page($position[$i]['name']).
                    "</p></td>";
            }

            // Выводим позиции
            echo "<tr $style>
                    $name
                    <td>".print_page($position[$i]['url'])."</td>
                    <td align=center>".print_page($position[$i]['pos'])."</td>
                    <td>
                        <a href=urlup.php?$url>Вверх</a><br>
                        $strhide<br>
                        <a href=$edit?$url>Редактировать</a><br>
                        <a href=# onclick=\"delete_position('urldel.php?$url',".
                "'Вы действительно хотите удалить позицию?');\">Удалить</a><br>
                        <a href=urldown.php?$url>Вниз</a>
                    </td>
                 </tr>";
        }
        echo "</table><br><br>";
    }
    // Выводим ссылки на другие страницы
    echo $obj;
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
ob_get_flush();
?>
