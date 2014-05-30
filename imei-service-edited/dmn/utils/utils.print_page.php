<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 04.04.12
 * Time: 23:06
 * To change this template use File | Settings | File Templates.
 */
 
////////////////////////////////////////////////
// Функция обработки bbCode
///////////////////////////////////////////////

function print_page($postbody)
{
    // Разрезаем слишком длинные слова
    $postbody = preg_replace_callback(
                "|([a-zа-я\d!]{35,})|i",
                "split_text",
                $postbody);
    // Предотвращаем XSS-инъекции
    $postbody = htmlspecialchars($postbody, ENT_QUOTES);
    // Тэги
    // Жирное выделение
    $pattern = "#\[b\](.+)\[\/b\]#isU";
    $postbody = preg_replace($pattern,
                             '<b>\\1</b>',
                             $postbody);
    // Курсив
    $pattern = "#\[i\](.+)\[\/i\]#isU";
    $postbody = preg_replace($pattern,
                            '<i>\\1</i>',
                            $postbody);
    // Степень
    $pattern = "#\[sup\](.+)\[\/sup\]#isU";
    $postbody = preg_replace($pattern,
                            '<sup>\\1</sup>',
                            $postbody);
    // Индекс (нижний)
    $pattern = "#\[sub\](.+)\[\/sub\]#isU";
    $postbody = preg_replace($pattern,
                            '<sub>\\1</sub>',
                            $postbody);
    // Подчеркивание
    $pattern = "#\[ins\](.+)\[\/ins\]#isU";
    $postbody = preg_replace($pattern,
                            '<ins>\\1</ins>',
                                $postbody);
    // Ссылка без названия
//    $pattern = "#\[url\](.+?)\[\/url\]#isU";
//    $pattern = "#\[url\][\s]*([\S]*)[\s]*\[\/url\]#isU";
    $pattern = "#\[url\][\s]*(.*)[\s]*\[\/url\]#isU";
    $postbody = preg_replace_callback($pattern,
                            "url_replace",
                            $postbody);
    // Ссылка с названием
//    $pattern = "#\[url=(.*)\]([^\[]+?)\[\/url\]#isU";
//    $pattern = "#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[\/url\]#isU";
    $pattern = "#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*(.*)[\s]*\[\/url\]#is";
    $postbody = preg_replace_callback($pattern,
                                        "url_replace_name",
                                        $postbody);
    // Изображение
    $pattern = "#\[img\][\s]*([\S]+)[\s]*\[\/img\]#isU";
    $postbody = preg_replace_callback($pattern,
                                        "img_replace",
                                        $postbody);
    // Цвет
    $pattern = "#\[color=[\s]*([a-z]+|\#[0-9a-f]{3}[0-9a-f]{3})[\s]*\](.*)\[\/color\]#isU";
    $postbody = preg_replace_callback($pattern,
                             "font_replace",
                            $postbody);
    // Размер
    $pattern = "#\[size=([0-9]+(%|px|em)?)\](.*)\[\/size\]#isU";
    $postbody = preg_replace_callback($pattern,
                                      "size_replace",
                                        $postbody);
    // Почта
    $pattern = "#\[mail\](.*)\[\/mail\]#isU";
    $postbody = preg_replace_callback($pattern,
                                        "mail_replace",
                                        $postbody);


    return $postbody;
}

function url_replace($matches)
{
    if(substr($matches[1], 0, 7) != "http://" && substr($matches[1],0,8) != "https://")
    {
        $matches[1] = "http://".$matches[1];
    }
    return "<a href=$matches[1] class=news_txt_lnk>$matches[1]</a>";
}

function url_replace_name($matches)
{
    if(substr($matches[1],0,7) != "http://" && substr($matches[1],0,8) != "https://")
    {
        $matches[1] = "http://".$matches[1];
    }
    return "<a href=\"$matches[1]\" class=\"news_txt_lnk\">$matches[2]</a>";
}

function split_text($matches)
{
    return wordwrap($matches[1],35,' ',1);
}
function img_replace($matches){

    if(substr($matches[1],0,7) != "http://"){
        $matches[1] = "http://".$matches[1];
    }
    //return "<img src=\"$matches[1]\" class=\"news_txt_lnk\"/>";
    return "<p  class=\"news_txt_lnk\"><img src=\"$matches[1]\"/></p>";
}
function font_replace($matches){
    return "<span style=\"color: $matches[1]\">$matches[2]</span>";
}
function size_replace($matches){
    return "<span style=\"font-size:$matches[1]%\">$matches[3]</span>";
}
function mail_replace($matches){
    return "<a href=\"mailto:$matches[1]\">$matches[1]</a>";
}
?>