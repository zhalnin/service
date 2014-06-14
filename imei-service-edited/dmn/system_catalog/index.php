<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 26.04.12
 * Time: 21:19
 * To change this template use File | Settings | File Templates.
 */
 ob_start();
// Выставляем уровень обработки ошибок
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Навигационное меню
require_once("../utils/utils.navigation.php");
// Подключаем блог отображения текста в окне браузера
require_once("../utils/utils.print_page.php");

$title = $titlepage = "Администрирование перечня услуг по анлоку";
$pageinfo = '<p class=help>Здесь осуществляется администрирование
                            перечня услу по анлоку, добавление новых услуг
                            и позиций</p>';
// Включаем заголовок страницы
require_once("../utils/top.php");

$_GET['id_parent'] = intval($_GET['id_parent']);

try
{
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellpadding="0" cellspacing="0" border="0">
            <tr valign="top"><td height="25"><p>';
    echo "<a class=menu
                href=index.php?id_parent=0&page=$_GET[page]>
                    Корневой каталог</a>-&gt;".
                     menu_navigation($_GET['id_parent'], "", $tbl_cat_catalog).
           "<a class=menu href=catadd.php?".
           "id_catalog=$_GET[id_parent]&".
           "id_parent=$_GET[id_parent]&".
           "page=$_GET[page]>Добавить подкаталог</a>";
    echo "</td></tr></table>";

    // Число ссылок в постраничной навигации
    $pagelink = 3;
    // Число позиций на странице
    $pnumber = 8;
    // Объявляем объект постраничной навигации
    $obj = new PagerMysql($tbl_cat_catalog,
                            "WHERE id_parent=$_GET[id_parent]",
                            "ORDER BY pos",
                            $pnumber,
                            $pagelink,
                            "&id_parent=$_GET[id_parent]");
    // Получаем содержимое текущей страницы
    $catalog = $obj->get_page();

    // Если имеется хотя бы одна запись - выводим
    if(!empty($catalog))
    {
        // Выводим заголовок таблцы
        echo '<table width="100%"
                     class="table"
                     border="0"
                     cellpadding="0"
                     cellspacing="0">
                <tr class="header" align="center">
                    <td align="center">Название</td>
                    <td align="center">Позиции</td>
                    <td align="center">Описание</td>
                    <td width="20" align="center">Поз.</td>
                    <td width="50">Действия</td>
                </tr>';
        for($i = 0; $i < count($catalog); $i++)
        {
            $url = "id_catalog={$catalog[$i][id_catalog]}&".
                   "id_parent={$catalog[$i][id_parent]}&".
                   "page=$_GET[page]";
            // Выясняем скрыт каталог или нет
            if($catalog[$i]['hide'] == 'hide')
            {
                $strhide = "<a href=catshow.php?$url>Отобразить</a>";
                $style = " class=hiddenrow";
            }
            else
            {
                $strhide = "<a href=cathide.php?$url>Скрыть</a>";
                $style = "";
            }

            // Подсчитываем количество позиций в каждом из подкаталогов
            $query = "SELECT COUNT(*)
                        FROM $tbl_cat_position
                        WHERE id_catalog={$catalog[$i][id_catalog]}";
            $pos = mysql_query($query);
            if(!$pos)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при подсчете
                                        количества позиций");
            }
            $total = mysql_result($pos, 0);
            if($total > 0) $total = "&nbsp;($total)";
            else $total = "";

            // Выводим список каталогов
            echo "<tr $style>
                    <td><a href=index.php?".
                        "id_parent={$catalog[$i]['id_catalog']}&page=$_GET[page]>".
                        htmlspecialchars($catalog[$i]['name'])."</a></td>
                    <td align=center>
                        <a href=position.php?id_catalog={$catalog[$i]['id_catalog']}>".
                        "Позиции$total</a>
                    </td>
                    <td>".
                        nl2br(print_page($catalog[$i]['description'])).
                        "&nbsp;</td>
                    <td align=center>{$catalog[$i]['pos']}</td>
                    <td>
                        <a href=catup.php?$url>Вверх</a><br>
                        $strhide<br>
                    <a href=catedit.php?$url>Редактировать</a><br>
                    <a href=# onclick=\"delete_position('catdel.php?$url',".
                    "'Вы действительно хотите удалить каталог?');\">Удалить</a><br>
                    <a href=catdown.php?$url>Вниз</a><br></td>
                 </tr>";
        }
        echo "</table><br/>";
        // Выводим ссылки на другие страницы
        echo $obj;
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
// Включаем завершение страницы
require_once("../utils/bottom.php");
ob_get_flush();
?>
