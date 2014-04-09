<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/11/13
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
require_once("class/class.Database.php");
require_once("config/class.config.php");
require_once("count.php");
Database::getInstance();

$title = "Вопросы по анлоку iPhone, проверке по IMEI, s/n, blacklist или регистрации UDID + сертификаты и провижен профиль";
$keywords = "непривязанный джейлбрейк,кастомная прошивка,Evasi0n,udid,redsn0w,sn0wbreeze,absinthe";
$description = "Часто задаваемые вопросы помогут вам найти ответ на интересующий вас вопрос относительно прошивки iPhone/iPod/iPad, непривязанного или привязанного джейлбрейка, официального анлока, регистрации UDID в аккаунте разработчика.";

require_once("templates/top.php");
?>

    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="index.php"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="unlock.php"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="udid.php"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="carrier_check.php"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="fast_check.php"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="blacklist_check.php"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a  class="selected" href="faq.php"><span>Вопросы</span></a></li>
        </ul>
    </div>

<div id="main" class="main-content">
    <div id="slogan">Быстро - Качественно - Надежно</div>
<div id="showcase" class="content">
<div id="design">
<div class="row block grid2col row block border">
    <img class="hero-image flushleft logo" alt="IMEI-service - Вопросы" src="images/Apple_logo_black_shadow.png"/>
    <!--                <div class="column last">-->
    <!--                    <h1>-->

<?php
// Определяем параметр для статей
define("ARTICLE", 1);
try{

    // Если не передан параметр id_position - выводим список статей
    if(empty($_GET['id_position']))
    {
        // Проверяем GET-параметры, предотвращая SQL-инъекцию
        $_GET['page'] = intval($_GET['page']);
        $_GET['id_catalog'] = intval($_GET['id_catalog']);

        if(empty($_GET['id_catalog']))
        {
//            echo "EMPTY_ID_CATALOG";
            // Запрашиваем параметр текущего раздела
            $query = "SELECT * FROM $tbl_catalog
                        WHERE id_catalog=$_GET[id_catalog]";
            $cat = mysql_query($query);
            if(!$cat)
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при извлечении
                    параметров текущего раздела");
            }
            $catalog = mysql_fetch_array($cat);
        }

//        echo "NEXT_ID_CATALOG";
        // Подключаем верхний шаблон
//        require_once("templates/top.php");
        if(!empty($catalog['name'])) $pagename = $catalog['name'];
        else $pagename = "Статьи";
        if(!empty($catalog['keywords'])) $keywords = $catalog['keywords'];
        else $keywords = "Ключевые слова";
        // Запрашиваем подразделы текущего раздела
        $query = "SELECT * FROM $tbl_catalog
                    WHERE hide  = 'show' AND id_parent = $_GET[id_catalog]
                    AND modrewrite = 'faq'
                    ORDER BY pos";
        $sub = mysql_query($query);
        if(!$sub)
        {
            throw new ExceptionMySQL(mysql_error(),
                $query,
                "Ошибка при обращении к
                блоку статей");
        }
        if(mysql_num_rows($sub))
        {
            $subcatalog = mysql_fetch_array($sub);
            // Верхний шаблон
//            require_once("templates/top.php");
            // Название
//            echo title($pagename);
            echo "<div class=\"main_txt column last\">";
            echo "<h1>$subcatalog[name]</h1>";
//            while($subcatalog = mysql_fetch_array($sub))
//            {
//                echo "<a href=\"".$_SERVER['PHP_SELF'].
//                    "?id_catalog=".$subcatalog['id_catalog']."\"
//                        class=\"menu_lnk\"><h3>".
//                    htmlspecialchars($subcatalog['name'])."</a></h3>";
//            }
            echo "</div>";
        }
        // Запрашиваем статьи текущего раздела
        $query = "SELECT * FROM $tbl_position
                  WHERE hide = 'show' AND
                    id_catalog=$subcatalog[id_catalog]
                  ORDER BY pos";
        $pos = mysql_query($query);
        if(!$pos)
        {
            throw new ExceptionMySQL(mysql_error(),
                $query,
                "Ошибка при обращении к
                блоку статей");
        }
        if(mysql_num_rows($pos) > 0)
        {
            // Статья одна и подразделов нет
            if(mysql_num_rows($pos) == 1 && !mysql_num_rows($sub))
            {
//                echo "POS == 1";
                // Получаем параметры текущей ствтьи
                $position = mysql_fetch_array($pos);
                // Если статья на самом деле является
                // ссылкой - осуществляем редирект
                if($position['url'] != 'article')
                {
                    echo "<HTML><HEAD>
                        <META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'>
                        </HEAD></HTML>";
                    exit();
                }
                // Статья одна и нет подразделов - выводим содержимое статьи
                $_GET['id_position'] = $position['id_position'];
                // Название и ключевые слова
                $pagename = $position['name'];
                if(empty($pagename)) $pagename = "Статья";
                $_GET['id_catalog'] = $position['id_catalog'];
                $keywords = $position['keywords'];
                // Верхний шаблон
//                require_once("templates/top.php");
                // Название
//                echo title($pagename);
                require_once("article_print.php");
            }
            // Статей несколько или имеются также подразделы
            else
            {
//                echo "POS != 1";
                echo "<div class=\"main_txt column last\">";
                while($position = mysql_fetch_array($pos))
                {
                    if($position['url'] != 'article')
                    {
                        echo "<a href=\"".htmlspecialchars($position['url'])."\"
                                class=\"main_txt_lnk\">
                                ".htmlspecialchars($position['name'])."</a><br/>";
                    }
                    else
                    {
                        echo "<a href=\"$_SERVER[PHP_SELF]?id_catalog=$_GET[id_catalog]&".
                            "id_position=$position[id_position]\"
                                class=\"main_txt_lnk\">".
                            htmlspecialchars($position['name'])."</a><br/>";
                    }
                }
                echo "</div> ";
            }
        }
    }
    else
    {
//        echo "ID_POSITION";
        // Проверяем GET-параметры, предотвращая SQL-инъекцию
        $_GET['id_position'] = intval($_GET['id_position']);
        // Получаем параметры текущей статьи
        $query = "SELECT * FROM $tbl_position
                    WHERE hide = 'show' AND
                        id_position = $_GET[id_position]";
        $pos = mysql_query($query);
        if(!$pos)
        {
            throw new ExceptionMySQL(mysql_error(),
                $query,
                "Ошибка при обращении к
                блоку статей");
        }
        if(mysql_num_rows($pos))
        {
            $position = mysql_fetch_array($pos);
            // Если статья на самом деле является
            // ссылкой - осуществляем редирект
            if($position['url'] != 'article')
            {
                echo "<HTML><HEAD>
                    <META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'>
                    </HEAD></HTML>";
                exit();
            }

            // Подключаем верхний шаблон
            $pagename = $position['name'];
            if(empty($pagename)) $pagename = 'Каталог';
            $_GET['id_catalog'] = $position['id_catalog'];
            $keywords = $position['keywords'];
//            require_once("templates/top.php");

            // Название
//            echo title($pagename);
            // Выводим статью
            require_once("article_print.php");
        }
    }
    ?>
    </div>
    </div>
    <div id="design">
        <div class="row block grid2col row block border">
            <div class="column last">
                <h1>
                    Для связи с нами можете использовать:
                </h1>
                <p>
                    ‣ Skype - <a href="skype:zhalnin78?add">Добавить в друзья</a>
                    <br/><br/>
                    ‣ Email - imei_service@icloud.com<br/><br/>
                    <span class="nowrap">Вопрос по теме</span>
                    без внимания
                    <span class="more">не оставим!</span>
                </p>
            </div>
        </div>
    </div>
    </div>
    </div>

    <?php
    require_once("templates/bottom.php");
}
catch(ExceptionMySQL $exc){
    require_once("exception_mysql_debug.php");
}
?>