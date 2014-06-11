<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 15:10
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/view/ViewHelper.php" );

try {

    $request = \imei_service\view\VH::getRequest();
    print $request->getFeedbackString('/n');
//    echo "<tt><pre>".print_r( $request->getFeedbackString('/n'), true) ."</pre></tt>";

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


//                        Если id_catalog не передан
//                        значит просматриваем список доступных статей



                        echo "<div class='faq-title'>
                            <h1 class=h2>$subcatalog[name]</h1>
                        </div>
                        <div class='faq-image'>
                            <img alt='IMEI-service - Вопросы' src='images/Apple_logo_black_shadow.png'/>
                        </div>";









                        echo "<div class='faq-title'>
                                    <h1 class=h2>".$position['name']."</h1>
                                </div>
                                <div class='faq-image'>

                                </div>";
                        echo "<div class='faq-all-info'>";
                        require_once("article_print.php");
                        echo "</div> ";









                        echo "<div class='faq-info'>";

                        while($position = mysql_fetch_array($pos))  {
                            if($position['url'] != 'article') {
                                echo "<p><a href=\"".htmlspecialchars($position['url'])."\"
                                                    class=\"main_txt_lnk\">
                                                    ".htmlspecialchars( stripslashes( $position['name'] ) )."</a></p>";
                            } else {
                                echo "<p><a href=\"$_SERVER[PHP_SELF]?id_catalog=$_GET[id_catalog]&".
                                    "id_position=$position[id_position]\"
                                                    class=\"main_txt_lnk\">".
                                    htmlspecialchars($position['name'])."</a></p>";
                            }
                        }
                        echo "</div> "; // faq-info













//                        Если передан id_position
//                        значит переходим для детального просмотра статьи или ссылки



                        echo "<div class='faq-title'>
                                                        <h1 class=h2>".$position['name']."</h1>
                                                    </div>
                                                    <div class='faq-image'>

                                                    </div>";
                        echo "<div class='faq-all-info'>";
                        require_once("article_print.php");
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