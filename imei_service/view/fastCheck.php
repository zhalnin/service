<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 13:58
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;
try {
    // содержимое тега title
    $title          = "Быстрая и бесплатная проверка по IMEI iPhone";
    // содержимое тега meta
    $keywords       = "imei,check,iPhone,instant check,check status,проверка по IMEI,бесплатно узнать оператора,быстрая проверка imei";
    // содержимое тега meta
    $description    = "Бесплатная проверка по IMEI. Быстрая проверка по IMEI iPhone поможет определить, к какому оператору привязан iPhone, дату активации, статус Find My iPhone.";

    // подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );

    ?>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="?cmd=News"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="?cmd=CarrierCheck"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a class="selected" href="?cmd=FastCheck"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="?cmd=BlacklistCheck"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a href="?cmd=Faq"><span>Вопросы</span></a></li>
        </ul>
    </div>

    <div id="main"  class="">

        <!--        подключаем обработчик авторизации-->
        <?php require_once( "utils/security_mod.php" ); ?>

        <div id="progressbar"></div>

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>

        <div id="showcase" class="main-content">
            <div class="hero selfclear" >
                <div id="shipping-box" class="box box-nomargin shipping-box ptn">
                    <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
                    <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;" ><a href="<?php echo $_SERVER[PHP_SELF]."?cmd=FastCheck" ?>">Быстрая и бесплатная проверка по IMEI iPhone</a></h2>
                    <div id="shipping" class="step edit" style="opacity: 1;">
                        <div class="step-content top-divided" style="">
                            <div id="shipping-contact-form" class="step-mode edit clearfix" style="">
                                <div class="gs grid-1of2" style="">
                                    <div id="shipping-user" class="user-form-block substep" style="">
                                        <form method="post" id="fastCheck-form">
                                            <fieldset style="">
                                                <legend style="">
                                                    <strong class="label"><b>Введите IMEI (15-ть цифр)</b></strong>
                                                </legend>
                                                <div id="shipping-user-address_section" class="user-address fieldset-content" style="">
                                                    <div class="mbs" style="">
                                                        <span class="daytimePhone-field field-with-placeholder" style="">
                                                            <label class="placeholder" for="shipping-user-daytimePhone" style="">
                                                                <span>IMEI (15 цифр)</span>
                                                            </label>
                                                            <input id="imei" class="imei" type="text" maxlength="" size="8" name="imei">
                                                        </span>
                                                    </div>
                                                </div>
                                                <input id="item" type="hidden" name="item" value="Быстрая проверка iPhone по IMEI">
                                                <input id="type" type="hidden" name="type" value="fast">
                                                <input id="mode" type="hidden" name="type" value="fast">
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                                <div class="gs grid-1of2 gs-last" style="">
                                    <div class="substep" style="">
                                        <div id="payment-form-astro" class="form-astro with-seperator">
                                            <p class="legend" style="">
                                                <strong id="coherent_id_103"><a href="?cmd=Unlock" class=" metrics-link">Сделать заказ на отвязку iPhone</a> </strong>
                                                <a href="?cmd=CarrierCheck" class="separated-link metrics-link">Проверить на SIM Lock</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-continue part-edit clear" style="">
                            <div class="continue-content clearfix">
                                <div class="gs grid-3of4" style="">
                                    <div class="chat chat-now cchat">
                                        <div id="shipping-step-defaults" style="">
                                            <div id="shipping-continue-button" class="button rect transactional" title="Отправить" value="click" type="submit" style="visibility: visible">
                                        <span style="">
                                            <span class="effect"></span>
                                            <span class="label"> Отправить </span>
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="gs grid-1of4 gs-last r-align" style="">
                                    <div id="shipping-button" class="button rect transactional blues" title="Сбросить" type="button" style="">
                                        <span style="">
                                            <span class="effect"></span>
                                            <span class="label"> Сбросить </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="footer">
        <div id="footer-content">
            <ol id="breadcrumbs">
                <li>
                    <p>Desined by alezhal-studio</p>
                </li>
                <li>
                    |
                </li>
                <li>
                    <p>Copyright © 2012 - 2014 All rights reserved.</p>
                </li>
            </ol>
        </div>

        <!-- begin of Top100 code -->

        <script id="top100Counter" type="text/javascript" src="http://counter.rambler.ru/top100.jcn?2943315"></script>
        <noscript>
            <a href="http://top100.rambler.ru/navi/2943315/">
                <img src="http://counter.rambler.ru/top100.cnt?2943315" alt="Rambler's Top100" border="0" />
            </a>

        </noscript>
        <!-- end of Top100 code -->



        <!-- Rating@Mail.ru logo -->
        <a href="http://top.mail.ru/jump?from=1838382">
            <img src="//top-fwz1.mail.ru/counter?id=1838382;t=442;l=1"
                 style="border:0;" height="31" width="88" alt="Рейтинг@Mail.ru" /></a>
        <!-- //Rating@Mail.ru logo -->


        <!--LiveInternet logo--><a href="http://www.liveinternet.ru/click"
                                   target="_blank"><img src="//counter.yadro.ru/logo?13.2"
                                                        title="LiveInternet: number of pageviews for 24 hours, of visitors for 24 hours and for today is shown"
                                                        alt="" border="0" width="88" height="31"/></a><!--/LiveInternet-->


        <!-- Yandex.Metrika informer -->
        <a href="http://metrika.yandex.ru/stat/?id=22310305&amp;from=informer"
           target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/22310305/3_0_868686FF_666666FF_1_pageviews"
                                               style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:22310305,lang:'ru'});return false}catch(e){}"/></a>
        <!-- /Yandex.Metrika informer -->


        <!-- hit.ua visible part -->
        <script language="javascript" type="text/javascript"><!--
            if (typeof(hitua) == 'object') document.write("<table cellpadding='0' cellspacing='0' border='0' style='display: inline; float:right; margin-right: 435px;'><tr><td><div style='width: 86px; height: 29px; padding: 0px; margin: 0px; border: solid #666 1px; background-color: #666'><a href='http://hit.ua/?x=" + hitua.site_id + "' target='_blank' style='float: left; padding: 3px; font: bold 8px tahoma; text-decoration: none; color: #fff' title='hit.ua - сервис интернет статистики'>HIT.UA</a><div style='padding: 3px; float: right; text-align: right; font: 6px tahoma; color: #fff' title='hit.ua: сейчас на сайте, посетителей и просмотров за сегодня'>" + hitua.online_count + "<br>" + hitua.uid_count + "<br>" + hitua.hit_count + "</div></div></td></tr></table>");
            //--></script>
        <!-- / hit.ua visible part -->


        <!--Openstat-->
        <span id="openstat2338271"></span>
        <script type="text/javascript">
            var openstat = { counter: 2338271, image: 5083, color: "828282", next: openstat };
            (function(d, t, p) {
                var j = d.createElement(t); j.async = true; j.type = "text/javascript";
                j.src = ("https:" == p ? "https:" : "http:") + "//openstat.net/cnt.js";
                var s = d.getElementsByTagName(t)[0]; s.parentNode.insertBefore(j, s);
            })(document, "script", document.location.protocol);
        </script>
        <!--/Openstat-->

    </div><!-- footer-->
    </body>
    </html>
    <?php

// ловим сообщения об ошибках
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>