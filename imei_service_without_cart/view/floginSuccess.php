<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 14:27
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );
try {
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
//    $request = \imei_service\view\VH::getRequest();

    // выполняем переадресацию на страницу с входом на сайт
    print "<html><head>\n";
    print "<meta http-equiv='Refresh' content='7; url=?cmd=Login'>\n";
    print "</head></html>\n";

    // содержимое тега title
    $title          = "Вы успешно восстановили ваш пароль на сайте imei-service.ru";
    // содержимое тега meta
    $keywords       = "непривязанный джейлбрейк,кастомная прошивка,Evasi0n,udid,redsn0w,sn0wbreeze,absinthe";
    // содержимое тега meta
    $description    = "Оповещение о успешной отправки заявки на официальный анлок iPhone";

    // подключаем верхний шаблон
    require_once("imei_service/view/templates/top.php");

    ?>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="?cmd=News"><span>Главная</span></a></li>
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
                    <h2  class="h2">Вы успешно восстановили ваш пароль на сайте imei-service.ru</h2>
                </div>
                <div class='news-container'>
                    <div class='success-body'>
                        <div class='success-title'>
                            <h1 class=h2>Спасибо, что присоединились к нам!</h1>
                        </div>
                        <div class='success-image'>
                            <img alt='IMEI-service - Заявка на проверку iPhone по IMEI' src='imei_service/view/images/Apple_logo_black_shadow.png'/>
                        </div>

                        <!--                    <div class='success-all-info'>-->
                        <!--                   </div>-->

                        <div class='success-info'>
                            <p>
                                Мы рады вас приветствовать!<br/><br/>
                                Ваш пароль был успешно восстановлен<br/><br/>
                                Через несколько минут вы должны получить письмо с вашим логином и паролем<br/>
                                Используя ваши учетные данные, вы можете войти на сайт<br/><br/>
                                На сайте вы можете:<br/>
                                ‣ Заказать официальную или программную отвязку iPhone <br/>
                                ‣ Проверить iPhone по IMEI<br/>
                                ‣ Проверить iPhone на blacklist<br/>
                                ‣ Зарегистрировать UDID для установки бета-версии iOS<br/><br/>
                                ‣ Оставить отзыв или задать вопрос в Гостевой книге<br/><br/>
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
    // подключаем нижний шаблон
    require_once( "imei_service/view/templates/bottom.php" );
// ловим сообщения об ошибках
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>