<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/17/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */

require_once("class/class.Database.php");
require_once("config/class.config.php");
Database::getInstance();
error_reporting(E_ALL & ~E_NOTICE);


require_once("dmn/utils/utils.print_page.php");


//echo "<div id='design'>
//                <div class='row block grid2col row block border'>";


//echo "<div id=\"news-main\" class=\"main-content\">
//                <div id=\"\" class=\"news-content clear-fix\">
//                    <div id='' class=\"news-header\">
//                        <h2  class=\"h2\">Новости</h2>
//                    </div>
//                    <div class='news-container'>";


// Проверяем, является ли параметр id_news числом
$_GET['id_news'] = intval($_GET['id_news']);
// Выводим выбранное новостное сообщение
$query = "SELECT * FROM $tbl_news
                    WHERE  hide = 'show'
                    AND id_news = $_GET[id_news]";
$res = mysql_query($query);
if(!$res)
{
    throw new ExceptionMySQL(mysql_error(),
        $query,
        "Ошибка при извлечении
        текущей позиции");
}
$news = mysql_fetch_array($res);
// Если имеется хотя бы одна запись - выводим ее
if(!empty($news))
{
//            $patt = array("[b]","[/b]","[i]","[/i]");
//            $repl = array("","","","");
//            $pattern_url = "|\[url[^\]]*\]|";
//            $pattern_b_url = "|\[/url[^\]]*\]|";
//            $urlpict = "";

    if($news['hidedate'] != 'hide' ) {
        $pdate = date('d.m.Y H:i', strtotime($news['putdate']));
        $putdate =  "<span id=\"datetime\">".$pdate."</span>";
    } else {
        $putdate = "";
    }
    if($news['urlpict'] != '' && $news['hidepict'] != 'hide')
    {
        $photo_print = "src='{$news['urlpict_s']}' alt='$alt'";
        $img = "<img $photo_print>";

    } else {
        $img = "";
    }
    if($news['url'] != '' && $news['url'] != '-')
    {
        $href = "href='".$news['url']."'";
        $val_href = $news['urltext'];
    }
//    echo"$img<div class=\"column last\">
//                        ".$putdate."
//                        <h1><a href='http://imei-service.ru'>".nl2br(print_page($news['name']))."</a></h1>
//                        <p>".nl2br(print_page($news['body']))."</p>
//                    </div>";

    echo "<div class='news-all-body'>
                <div class='news-all-title'>
                    <h1 class=\"h2\">".nl2br(print_page($news['name']))."</h1>
                </div>
                <div class='news-all-image'>
                  $img
                  $putdate
                </div>
                <div class='news-all-info'>
                    <p>".nl2br(print_page($news['body']))."</p>
                </div>
                <div class=\"gs grid4 gs-last r-align\" style=\"\" onclick=window.history.back(); >
                    <div id=\"button_back\" class=\"button rect transactional blues\" title=\"Сбросить\" type=\"button\" style=\"\">
                            <span style=\"\">
                                <span class=\"effect\"></span>
                                <span class=\"label\"> Назад </span>
                            </span>
                    </div><!-- shipping-button -->
                </div>
          </div>";

}


//    echo "
//            <div class=\"gs grid4 gs-last r-align\" style=\"\" onclick=window.history.back(); >
//                <div id=\"button_back\" class=\"button rect transactional blues\" title=\"Сбросить\" type=\"button\" style=\"\">
//                        <span style=\"\">
//                            <span class=\"effect\"></span>
//                            <span class=\"label\"> Назад </span>
//                        </span>
//                </div><!-- shipping-button -->
//            </div>
//          </div>";

//  echo "</div>
//      </div>";
?>