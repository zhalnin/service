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

$title = $titlepage = "Администрирование 'Гостевой книги'";
$pageinfo = "<p class=\"help\">Здесь осуществляется администрирование
                            контента 'Гостевой книги'</p>";

// Включаем заголовок страницы
require_once("../utils/top.php");

$_GET['id_parent'] = intval($_GET['id_parent']);

try {
    if( isset( $_GET['page'] ) ) {
        $page = htmlspecialchars( stripslashes( $_GET['page'] ), ENT_QUOTES );
    } else {
        $page = '';
    }
    if( isset( $_GET['order'] ) ) {
        $orderBy = htmlspecialchars( stripslashes( $_GET['order'] ), ENT_QUOTES );
    } else {
        $orderBy = '';
    }
    if( isset( $_GET['sort'] ) ) {
        $sort = htmlspecialchars( stripslashes( $_GET['sort'] ), ENT_QUOTES );
    } else {
        $sort = '';
    }
    if( isset( $_GET['start'] ) ) {
        $start = htmlspecialchars( stripslashes( $_GET['start'] ), ENT_QUOTES );
    } else {
        $start = 0;
    }

    if( $start < 0 ) $start = 0;

//    $pnumber = 5;

    if( $sort == 'desc' ) {
        $sort = 'asc';
    } else {
        $sort = 'desc';
    }

    switch( $orderBy ) {
        case 'name':
            $orderBy = 'name';
            break;
        case 'city':
            $orderBy = 'city';
            break;
        case 'email':
            $orderBy = 'email';
            break;
        case 'url':
            $orderBy = 'url';
            break;
        default:
            $orderBy = 'putdate';
            break;
    }

//            $pagerMysql = new \guestbook_test_on_mysql\add\PagerMysql('guest', "", "ORDER BY putdate", 10, 3, "");

    $PDO = new PDO("mysql:host=localhost;dbname=imei-service",'root', 'zhalnin5334', array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
    ));

    // Число ссылок в постраничной навигации
    $pagelink = 3;
    // Число позиций на странице
    $pnumber = 8;
    // Объявляем объект постраничной навигации
    $obj = new PagerMysql('system_guestbook',
        "",
        "ORDER BY $orderBy $sort",
        $pnumber,
        $pagelink,
        "");
    // Получаем содержимое текущей страницы
    $catalog = $obj->get_page();

    // Если имеется хотя бы одна запись - выводим
    if( !empty($catalog ) ) {
        // Выводим ссылки на другие страницы
        echo $obj;
        echo "<br /><br />";
        // Выводим заголовок таблцы
        echo "<table width=\"100%\"
                     class=\"table\"
                     border=\"0\"
                     cellpadding=\"0\"
                     cellspacing=\"0\">
                <tr class=\"header\" align=\"center\">
                    <td align=\"center\"><a href=".$_SERVER['PHP_SELF']."?page=".$page."&order=name&sort=".$sort .">Автор</a></td>
                    <td align=\"center\"><a href=".$_SERVER['PHP_SELF']."?page=".$page."&order=putdate&sort=".$sort .">Дата</a></td>
                    <td align=\"center\"><a href=".$_SERVER['PHP_SELF']."?page=".$page."&order=city&sort=".$sort .">Город</td>
                    <td align=\"center\"><a href=".$_SERVER['PHP_SELF']."?page=".$page."&order=email&sort=".$sort .">Email</td>
                    <td align=\"center\"><a href=".$_SERVER['PHP_SELF']."?page=".$page."&order=url&sort=".$sort .">Url</td>
                    <td align=\"center\">Ответ</td>
                    <td width=\"50\">Действия</td>
                </tr>";
        for($i = 0; $i < count($catalog); $i++) {
            $url = "id={$catalog[$i]['id']}&".
                "page=$_GET[page]";

            if($catalog[$i]['hide'] == 'hide') {
                $strhide = "<a href=guestbookshow.php?$url>Отобразить</a>";
                $style = " class=hiddenrow";
            } else {
                $strhide = "<a href=guestbookhide.php?$url>Скрыть</a>";
                $style = "";
            }
            if( ! empty( $catalog[$i]['answer'] ) && ( $catalog[$i]['answer'] != '-' ) ) {
                $answer = 'есть';
            } else {
                $answer = 'нет';
            }


            // Выводим список каталогов
            echo "<tr $style>
                    <td>".htmlspecialchars($catalog[$i]['name'])."</td>
                    <td align=center>".$catalog[$i]['putdate']."</td>
                    <td>".$catalog[$i]['city']."&nbsp;</td>
                    <td align=center>{$catalog[$i]['email']}</td>
                    <td align=center>{$catalog[$i]['url']}&nbsp;</td>
                    <td align=center>$answer</td>
                    <td>$strhide<br>
                        <a href=guestbookedit.php?$url>Редактировать</a><br>
                        <a href=# onclick=\"delete_position('guestbookdel.php?$url',".
                "'Вы действительно хотите удалить каталог?');\">Удалить</a><br>
            </td>
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
