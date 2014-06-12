<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 15:10
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/view/ViewHelper.php" );
require_once( "imei_service/view/utils/utils.printPage.php" );
require_once( 'imei_service/domain/FaqParagraph.php' );

try {

    $request = \imei_service\view\VH::getRequest();
    $position = $request->getObject( 'faqPosition' );
    $paragraphs = $request->getObject( 'faqParagraphCollection' );

//    foreach ($paragraphs as $paragraph ) {
//        foreach ($paragraph->getFaqParagraphImage() as $paragraphImage ) {
//            echo "<tt><pre>".print_r( $paragraphImage, true) ."</pre></tt>";
//
//        }
//
//    }


    $paragraphImages =
//    echo "<tt><pre>".print_r( $paragraphs , true) ."</pre></tt>";
    $title = $position->getName();
//    $keywords = $catalog->getKeywords();
//    $description = $catalog->getDescription();
    $keywords = "непривязанный джейлбрейк,кастомная прошивка,Evasi0n,udid,redsn0w,sn0wbreeze,absinthe";
    $description = "Часто задаваемые вопросы помогут вам найти ответ на интересующий вас вопрос относительно прошивки iPhone/iPod/iPad, непривязанного или привязанного джейлбрейка, официального анлока, регистрации UDID в аккаунте разработчика.";
    require_once("templates/top.php");
    ?>

    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="?cmd=News"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="?cmd=CarrierCheck"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="?cmd=FastCheck"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="?cmd=BlacklistCheck"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a  class="selected" href="?cmd=Faq"><span>Вопросы</span></a></li>
        </ul>
    </div>
    <div id="main" class="">
        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>
        <!--        End of main-slogan-->

        <div id="news-main" class="main-content">
            <div id="" class="news-content clear-fix">
                <div id='' class="news-header">
                    <h2  class="h2">FAQ</h2>
                </div>
                <div class='news-container'>
                    <div class='faq-body'>

                        <?php

//                    Если передан id_position
//                    значит переходим для детального просмотра статьи или ссылки
                        echo "<div class='faq-title'>
                                    <h1 class=h2>".$position->getName()."</h1>
                                </div>
                                <div class='faq-image'>

                                </div>";
                        echo "<div class='faq-all-info'>";









                        foreach($paragraphs as $paragraph ) {
                            // Выясняем тип выравнивания параграфа
                            $align = "";
                            switch($paragraph->getAlign() )
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



                            foreach ($paragraph->getFaqParagraphImage() as $paragraphImage ) {

    //                            $query = "SELECT * FROM $tbl_paragraph_image
    //                    WHERE id_paragraph = $paragraph[id_paragraph] AND
    //                            id_position = $_GET[id_position] AND
    //                            id_catalog = $_GET[id_catalog] AND
    //                            hide = 'show'";
    //                            $img = mysql_query($query);
    //                            if(!$img) exit("Ошибка при извлечении изображений");

                                // Изобажение элемента
                                $image_print = "";
                                $image_big = 'imei_service/view/'.$paragraphImage->getBig();
                                $image_small = 'imei_service/view/'.$paragraphImage->getSmall();
                                $image_alt = $paragraphImage->getAlt();
                                $image_name = $paragraphImage->getName();


                                if( ! empty( $image_big ) )
                                {

                                    // Извлекаем изображения
                                    unset($img_arr);
//                                    while($image = mysql_fetch_array($img))
//                                    {

                                        // ALT-тэг
                                        if( ! empty( $image_alt ) ) $alt = "alt='$image_alt'";
                                        else $alt = "";
                                        // Размер малого изображения
                                        $size_small = @getimagesize( $image_small );

                                        // Название изображения
                                        if( ! empty( $image_name ) ) {
                                            $name = "<br/><br/><br/>".$paragraphImage->getName()."</b>";
                                        }
                                        else $name = "";

                                        // Большое изображение
                                        if(empty(  $image_big ) )
                                        {
                                            $img_arr[] = "<img $alt src='$image_small'
                                            width=$size_small[0]
                                            height=$size_small[1]>$name";
                                        }
                                        else
                                        {
                                            $size = @getimagesize( $image_big );
                                            $img_arr[] = "<a href=#
                                            onclick=\"show_img({$paragraphImage->getId()},".
                                                $size[0].",".$size[1]."); return false \">
                                            <img $alt src='$image_small''
                                                    border=0
                                                    width=$size_small[0]
                                                    height=$size_small[1]></a>$name";
                                        }
//                                    echo "<tt><pre>".print_r( $paragraphImage->getId(), true) ."</pre></tt>";

//                                    }
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

                            }













                            // Выясняем тип параграфа
                            $class = "rightpanel_txt ";
                            switch($paragraph->getType())
                            {
                                case 'text':
                                    $class = "main_txt ";
                                    echo "<div align=$align class=\"$class\">".
                                        nl2br(\imei_service\view\utils\printPage($paragraph->getName())).
                                        "<br/>$image_print</div>";
                                    break;
                                case 'title_h1':
                                    $class = "main_ttl ";
                                    echo "<h1 align=$align class=\"$class h1\">".
                                        \imei_service\view\utils\printPage($paragraph->getName()).
                                        "<br/>$image_print</h1>";
                                    break;
                                case 'title_h2':
                                    $class = "main_ttl ";
                                    echo "<h2 align=$align class=\"$class h2\">".
                                        \imei_service\view\utils\printPage($paragraph->getName()).
                                        "<br/>$image_print</h2>";
                                    break;
                                case 'title_h3':
                                    $class = "main_ttl ";
                                    echo "<h3 align=$align class=\"$class h3\">".
                                        \imei_service\view\utils\printPage($paragraph->getName()).
                                        "<br/>$image_print</h3>";
                                    break;
                                case 'title_h4':
                                    $class = "main_ttl ";
                                    echo "<h4 align=$align class=\"$class h4\">".
                                        \imei_service\view\utils\printPage($paragraph->getName()).
                                        "<br/>$image_print</h4>";
                                    break;
                                case 'title_h5':
                                    $class = "main_ttl ";
                                    echo "<h5 align=$align class=\"$class h5\">".
                                        \imei_service\view\utils\printPage($paragraph->getName()).
                                        "<br/>$image_print</h5>";
                                    break;
                                case 'title_h6':
                                    $class = "main_ttl ";
                                    echo "<h6 align=$align class=\"$class h6\">".
                                        \imei_service\view\utils\printPage($paragraph->getName()).
                                        "<br/>$image_print</h6>";
                                    break;
                                case 'list':
                                    $arr = explode("\r\n", $paragraph->getName());
                                    $class = "main_txt ";
                                    if(!empty($arr))
                                    {
                                        echo "<div align=$align class=\"$class\"><ul>";
                                        for($i = 0; $i < count($arr); $i++)
                                        {
                                            echo "<li>".\imei_service\view\utils\printPage($arr[$i])."<br/>$image_print</li>";
                                        }
                                        echo "</ul></div><br/>";
                                    }
                                    break;
                            }

                        }



                        echo "<div class=\"gs grid4 gs-last r-align\" style=\"\" onclick=window.history.back(); >
                                <div id=\"button_back\" class=\"button rect transactional blues\" title=\"Сбросить\" type=\"button\" style=\"\">
                                        <span style=\"\">
                                            <span class=\"effect\"></span>
                                            <span class=\"label\"> Назад </span>
                                        </span>
                                </div><!-- shipping-button -->
                            </div>";

                        echo "</div> ";

                        ?>

                    </div>  <!-- End of faq-body -->
                </div>  <!--   End of news-container -->
            </div> <!-- End of news-content clear-fix -->
            <div class="news-footer"></div>
        </div><!--     End of news-content -->
    </div><!--        End of news-main-->


    <!--    <div id="main-guestbook"></div>-->
    <?php
    require_once("templates/bottom.php");

} catch( \imei_service\base\AppException $exc ) {
    require_once( "imei_service/base/Exceptions.php" );
} catch( \imei_service\base\DBException $exc ) {
    require_once( "imei_service/base/Exceptions.php" );
}
?>