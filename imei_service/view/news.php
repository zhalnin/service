<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 19:45
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );
//require_once( "imei_service/add/class.PagerMysql.php" );
require_once( "imei_service/view/ViewHelper.php" );
require_once( "imei_service/view/utils/utils.printPage.php" );

$request = \imei_service\view\VH::getRequest();
$news = $request->getObject('news');
$count = 0;
//echo "<tt><pre>".print_r($news, true)."</pre></tt>";
//echo "<tt><pre>".print_r($_REQUEST, true)."</pre></tt>";
    $title = "Главная";
    $keywords = "udid,unlock,blacklist,carrier,iPhone,iPod,iPad,iTunes";
    $description = "Официальный анлок iPhone позволит вам обновлять ваш аппарат в iTunes. Регистрация UDID в аккаунте разработчика нужен для безопасной установки iOS 7.1 бета 3. iPhone.
    Проверка iPhone по IMEI/серийному номеру даст вам самую полную информацию о вашем iPhone.
    Проверка iPhone на blacklist даст вам информацию о статусе вашего аппарата (потерян/украден/задолженность по контракту)";
    require_once("imei_service/view/templates/top.php");
    try {
    //    $url = "?id=1";

    //mail("zhalninpal@me.com","test","test");
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
        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>
        <!--        End of main-slogan-->

        <div id="addNav" class="">
            <a href="<?php echo $_SERVER['PHP_SELF']."?cmd=Guestbook" ?>"><div id="nav-guestbook" class="addNav-body rounded main-content"><h3 class="h3">Гостевая</h3></div></a>
            <a href="<?php echo $_SERVER['PHP_SELF']."?cmd=Contacts" ?>"><div id="nav-contact" class="addNav-body rounded main-content"><h3 class="h3">Контакты</h3></div></a>
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

    if($new->getUrl() != '' && $new->getUrl() != '-')
    {
        $href = "href='".$new->getUrl()."'";
        $val_href = $new->getUrltext();
    }
    $detail = "";
    $url = "?cmd=News&id=".$new->getId();

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

echo "
                    </div><!-- End of news-container -->
                 <div class=\"news-footer\"></div><!-- End of news-footer -->
            </div><!-- End of news-content -->
        </div><!-- End of news-main -->
        <div id=\"main-guestbook\"></div>";

require_once("imei_service/view/templates/bottom.php");


} catch(\imei_service\base\AppException $exc){
    require_once( "imei_service/base/Exceptions.php" );
} catch(\imei_service\base\DBException $exc) {
    require_once( "imei_service/base/Exceptions.php" );
}



?>