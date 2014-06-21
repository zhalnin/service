<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/11/13
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );
try {


    require_once( "imei_service/view/ViewHelper.php" );

    $request    = \imei_service\view\VH::getRequest();
    $page       = $request->getProperty('page');



    print "<html><head>\n";
    print "<meta http-equiv='Refresh' content='5; url=?cmd=Guestbook&page=1'>\n";
    print "</head></html>\n";



    $keywords = "непривязанный джейлбрейк,кастомная прошивка,Evasi0n,udid,redsn0w,sn0wbreeze,absinthe";
    $title = "Заявка отправлена";
    $description = "Оповещение о успешной отправки заявки на проверку iPhone по IMEI";
    require_once("imei_service/view/templates/top.php");

    ?>

        <div id="header">
            <ul id="navigation" role="navigation">
                <li id="nav-home"><a class="selected" href="?cmd=News"><span>Главная</span></a></li>
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

            <div id="news-main" class="main-content">
                <div id="" class="news-content clear-fix">
                    <div id='' class="news-header">
                        <h2  class="h2">Успешное размещение сообщения в гостевой книге</h2>
                    </div>
                    <div class='news-container'>
                        <div class='success-body'>
                            <div class='success-title'>
                                <h1 class=h2>Спасибо, что воспользовались нашим сервисом!</h1>
                            </div>
                            <div class='success-image'>
                                <img alt='IMEI-service - Заявка на проверку iPhone по IMEI' src='imei_service/view/images/Apple_logo_black_shadow.png'/>
                            </div>

                            <!--                    <div class='success-all-info'>-->
                            <!--                   </div>-->

                            <div class='success-info'>
                                <p>
                                    Ваше сообщение успешно размещено в гостевой книге!<br/><br/>
                                    В гостевой книге вы можете оставить отзыв о предоставленной вам услуге, <br/><br/>
                                    либо задать интересующий вас вопрос, в контексте нашего сайта.<br/><br/>
                                    <span class="nowrap">Гарантируем </span>

                                    <span class="more">качество услуг и максимально короткие сроки</span>
                                </p>
                            </div>
                        </div>  <!-- End of success-body -->
                    </div>  <!--   End of news-container -->
                </div> <!-- End of news-content clear-fix -->
                <div class="news-footer"></div>
            </div><!--     End of news-content -->
        </div><!--        End of news-main-->


        <!--    <div id="main-guestbook"></div>-->

<?php
    require_once("imei_service/view/templates/bottom.php");
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>