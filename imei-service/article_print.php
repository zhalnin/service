<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 9:59 PM
 * To change this template use File | Settings | File Templates.
 */
require_once("class/class.Database.php");
require_once("config/class.config.php");
Database::getInstance();
error_reporting(E_ALL & ~E_NOTICE);

//echo($_GET['id_position']);
//echo "<br/>";
//echo $_GET['id_catalog'];
//echo "<br/>";
//echo "ARTICLE";
if(!defined("ARTICLE")) return;
if(!preg_match("|^[\d]+$|", $_GET['id_position'])) return;
if(!preg_match("|^[\d]+$|", $_GET['id_catalog'])) return;
// Обработка текста пред выводом
require_once("dmn/utils/utils.print_page.php");


// Выводим список разделов
$query = "SELECT * FROM $tbl_paragraph
          WHERE id_position = $_GET[id_position] AND
                    id_catalog = $_GET[id_catalog] AND
                    hide = 'show'
          ORDER BY pos";

$par = mysql_query($query);
if(!$par)
{
    throw new ExceptionMySQL(mysql_error(),
        $query,
        "Ошибка при обращении
        к параграфам позиции");
}
$type_catalog = "";


//require_once("templates/top.php");


print_page($par[name]);




//echo "<div class='faq-title'>
//    <h1 class=h2>".$par[name]."</h1>
//</div>
//<div class='faq-image'>
//
//</div>";
//echo "<div class='faq-all-info'>";




if(mysql_num_rows($par) > 0)
{
    while($paragraph = mysql_fetch_array($par))
    {
        // Выясняем тип выравнивания параграфа
        $align = "";
        switch($paragraph['align'])
        {
            case 'left':
                // $type .=" (влево)";
                $align = "left";
                break;
            case 'center':
                // $type .=" (по центру)";
                $align = "center";
                break;
            case 'right':
                // $type .=" (справа)";
                $align = 'right';
                break;
        }
        // Изобажение элемента
        $image_print = "";

        $query = "SELECT * FROM $tbl_paragraph_image
                    WHERE id_paragraph = $paragraph[id_paragraph] AND
                            id_position = $_GET[id_position] AND
                            id_catalog = $_GET[id_catalog] AND
                            hide = 'show'";
        $img = mysql_query($query);
        if(!$img) exit("Ошибка при извлечении изображений");
        if(mysql_num_rows($img))
        {


            // Извлекаем изображения
            unset($img_arr);
            while($image = mysql_fetch_array($img))
            {

                // ALT-тэг
                if(!empty($image['alt'])) $alt = "alt='$image[alt]'";
                else $alt = "";

                // Размер малого изображения
                $size_small = @getimagesize($image['small']);

                // Название изображения
                if(!empty($image['name']))
                {
                    $name = "<br/><br/><br/>".$image['name']."</b>";
                }
                else $name = "";
                // Большое изображение
                if(empty($image['big']))
                {
                    $img_arr[] = "<img $alt src='$image[small]'
                                        width=$size_small[0]
                                        height=$size_small[1]>$name";
                }
                else
                {
                    $size = @getimagesize($image['big']);
                    $img_arr[] = "<a href=#
                                        onclick=\"show_img('$image[id_image]',".
                        $size[0].",".$size[1]."); return false \">
                                        <img $alt src='$image[small]'
                                                border=0
                                                width=$size_small[0]
                                                height=$size_small[1]></a>$name";
                }

            }
            for($i = 0; $i < count($img_arr)%3; $i++) $img_arr[] = "";
            // Выводим изображение
            for($i = 0, $k = 0; $i < count($img_arr); $i++,$k++)
            {
                if($k == 0)
                {
//                    $image_print .= "</td><table cellpadding=5>";
//                      $image_print .= "<tr valign=top>";
                    $image_print .= "<td class=\"main_txt \">".$img_arr[$i]."</td>";
                    if($k == 2)
                    {
                        $k = -1;
                        $image_print .=  "</tr></table>";
                    }
                }
            }
        }


        // Выясняем тип параграфа
        $class = "rightpanel_txt ";
        switch($paragraph['type'])
        {
            case 'text':
                $class = "main_txt ";
                echo "<div align=$align class=\"$class\">".
                    nl2br(print_page($paragraph['name'])).
                    "<br/>$image_print</div>";
                break;
            case 'title_h1':
                $class = "main_ttl ";
                echo "<h1 align=$align class=\"$class h1\">".
                    print_page($paragraph['name']).
                    "<br/>$image_print</h1>";
                break;
            case 'title_h2':
                $class = "main_ttl ";
                echo "<h2 align=$align class=\"$class h2\">".
                    print_page($paragraph['name']).
                    "<br/>$image_print</h2>";
                break;
            case 'title_h3':
                $class = "main_ttl ";
                echo "<h3 align=$align class=\"$class h3\">".
                    print_page($paragraph['name']).
                    "<br/>$image_print</h3>";
                break;
            case 'title_h4':
                $class = "main_ttl ";
                echo "<h4 align=$align class=\"$class h4\">".
                    print_page($paragraph['name']).
                    "<br/>$image_print</h4>";
                break;
            case 'title_h5':
                $class = "main_ttl ";
                echo "<h5 align=$align class=\"$class h5\">".
                    print_page($paragraph['name']).
                    "<br/>$image_print</h5>";
                break;
            case 'title_h6':
                $class = "main_ttl ";
                echo "<h6 align=$align class=\"$class h6\">".
                    print_page($paragraph['name']).
                    "<br/>$image_print</h6>";
                break;
            case 'list':
                $arr = explode("\r\n", $paragraph['name']);
                $class = "main_txt ";
                if(!empty($arr))
                {
                    echo "<div align=$align class=\"$class\"><ul>";
                    for($i = 0; $i < count($arr); $i++)
                    {
                        echo "<li>".print_page($arr[$i])."<br/>$image_print</li>";
                    }
                    echo "</ul></div><br/>";
                }
                break;
        }

    }
//    echo "</div> ";
//    echo "</div> ";
}
echo "<div class=\"gs grid4 gs-last r-align\" style=\"\" onclick=window.history.back(); >
                                <div id=\"button_back\" class=\"button rect transactional blues\" title=\"Сбросить\" type=\"button\" style=\"\">
                                        <span style=\"\">
                                            <span class=\"effect\"></span>
                                            <span class=\"label\"> Назад </span>
                                        </span>
                                </div><!-- shipping-button -->
                            </div>";
?>

<script type="text/javascript" language="JavaScript1.1">
    <!--
            function show_img(id_image,width,height,adm)
            {
                var a;
                var b;
                var url;
                vidWindowWidth=width;
                vidWindowHeight=height;
                a=(screen.height-vidWindowHeight)/5;
                b=(screen.width-vidWindowWidth)/2;
                features = "top="+ a +
                            ",left=" + b +
                            ",height=" + vidWindowHeight +
                            ",width=" + vidWindowWidth +
                            ",toolbar=no," +
                            "menubar=no," +
                            "location=no," +
                            "directories=no," +
                            "scrollbars=no," +
                            "resizable=no";
                url="show.php?id_image=" + id_image;
                window.open(url,'',features,true);
            }
    //-->
</script>





<!--echo "<div class='faq-title'>-->
<!--    <h1 class=h2>$subcatalog[name]</h1>-->
<!--</div>-->
<!--<div class='faq-image'>-->
<!--    <img alt='IMEI-service - Вопросы' src='images/Apple_logo_black_shadow.png'/>-->
<!--</div>";-->
<!--echo "<div class='faq-all-info'>";-->
<!--    require_once("article_print.php");-->
<!--    echo "</div> ";-->