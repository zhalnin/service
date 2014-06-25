<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 19:45
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );
    // подключаем обработчик bbcode
    require_once( "imei_service/view/utils/utils.printPage.php" );
    // получаем объект request
    $request = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию news
    $news = $request->getObject('news');
    $count = 0; // счетчик для добавления выравнивания
    $k = 0; // временная переменная для получения значения только для тега title
    foreach( $news as $new ) {
        if( $k == 0 ) {
            // содержимое тега title
            $title = $new->getName();
        }
        break;
    }
    // содержимое тега title
//    $title = "Регистрация UDID iOS 8, официальный анлок iPhone, проверка по IMEI, Blacklist";
    // содержимое тега meta
    $keywords = "udid,unlock,blacklist,carrier,iPhone,iPod,iPad,iTunes,iOS";
    // содержимое тега meta
    $description = "Официальный анлок iPhone позволит вам обновлять ваш аппарат в iTunes. Регистрация UDID в аккаунте разработчика нужен для безопасной установки iOS 7.1 бета 3. iPhone.
    Проверка iPhone по IMEI/серийному номеру даст вам самую полную информацию о вашем iPhone.
    Проверка iPhone на blacklist даст вам информацию о статусе вашего аппарата (потерян/украден/задолженность по контракту)";

    // подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );
    ?>

    <script type="text/javascript">
        /**
              * For detail-button
              * To view whole news
              * @param url
              */
        function detail_button(url) {
            location.href=url;
        }

    </script>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a  class="selected" href="?cmd=News"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="?cmd=CarrierCheck"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="?cmd=FastCheck"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="?cmd=BlacklistCheck"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a href="?cmd=Faq"><span>Вопросы</span></a></li>
        </ul>
    </div>
    <div id="main" class="">

    <!--        подключаем верхний шаблон-->
    <?php require_once( "imei_service/view/templates/top.php" ); ?>

<!--        <div id="globalsearch">-->
<!--            <form id="g-search" class="search empty" method="get" action="http://www.apple.com/search/">-->
<!--                <div class="sp-label">-->
<!--                    <label for="sp-searchtext">Search</label>-->
<!--                    <input id="sp-searchtext" type="text" name="q" autocomplete="off" />-->
<!--                    <div class="reset"></div>-->
<!--                    <div class="spinner hide"></div>-->
<!--                </div>-->
<!--                <input id="search-section" type="hidden" name="sec" value="global">-->
<!--            </form>-->
<!--            <div id="sp-magnify">-->
<!--                <div class="magnify-searchmode"></div>-->
<!--                <div class="magnify"></div>-->
<!--            </div>-->
<!--            <div id="sp-results"></div>-->
<!--        </div>-->

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>
        <!--        End of main-slogan-->

        <div id="addNav" class="">
            <a href="?cmd=Guestbook"><div id="nav-guestbook" class="addNav-body rounded main-content"><h3 class="h3">Гостевая</h3></div></a>
            <a href="?cmd=Contacts"><div id="nav-contact" class="addNav-body rounded main-content"><h3 class="h3">Контакты</h3></div></a>
        </div>


        <div id="news-main" class="main-content">
            <div id="" class="news-content clear-fix">
                <div id='' class="news-header">
                    <h2  class="h2">Новости</h2>
                </div>
                <div class='news-container'>

<?php


    foreach( $news as $new ) {
         $new->getName();
    //    print $new->getPutdate();
        if( $new->getUrlpict_s() != '' && $new->getHidepict() != 'hide' ){
            $img = "<img src='imei_service/view/{$new->getUrlpict_s()}' alt='{$new->getAlt()}' />";
            if( $count % 2 == 1 ) {
                        $align = "float: right;";
                        $textAlign = "text-align: right";
                        $reverseAlign = "float: left";
                        $reverseTextAlign = "text-align: left";
                    } else if( $count % 2 == 0 ) {
                        $align = "float: left;";
                        $textAlign = "text-align: left";
                        $reverseAlign = "float: right";
                        $reverseTextAlign = "text-align: right";
                    }
        } else {
            $img = '';
        }

        if( $new->getUrl() != '' && $new->getUrl() != '-') {
            $href = "href='".$new->getUrl()."'";
            $val_href = $new->getUrltext();
        }
        $detail = "";
        $url = "?cmd=News&idn=".$new->getId();

                echo "<div class='news-string-body superlink main-content ' onclick=\"detail_button('$url')\">
                                <div class='news-title'>
                                    <h1 class=\"h2\">".nl2br(\imei_service\view\utils\printPage($new->getName()))."</h1>
                                </div>
                                <div class='news-image'  style=\"$align\">
                                  $img
                                </div>
                                <div class='news-info'  style=\"$reverseAlign\" >
                                    <p>".nl2br(\imei_service\view\utils\printPage($new->getPreview() ) )."</p>
                                </div>
                            </div>";
        $count++;
    }

?>
                    </div><!-- End of news-container -->
                 <div class=\"news-footer\"></div><!-- End of news-footer -->
            </div><!-- End of news-content -->
        </div><!-- End of news-main -->
        <div id=\"main-guestbook\"></div>
<?php
    // подключаем нижний шаблон
    require_once( "imei_service/view/templates/bottom.php" );
// ловим сообщения об ошибках
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>