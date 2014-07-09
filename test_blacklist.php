<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 15:03
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view\templates;
try {
    session_start();
    $sid_add_message = session_id();
    error_reporting(E_ALL & ~E_NOTICE);
//require_once("count.php");
    if( ! isset( $_SESSION['cart'] ) ) {
        $_SESSION['cart'] = array();
        $_SESSION['total_items'] = 0;
        $_SESSION['total_price'] = '0.00';
    }


    // подключаем обработчик bbcode
//    require_once( "imei_service/view/utils/utils.printPage.php" );
//    // подключаем обработчик курса валют
    require_once( "imei_service/view/utils/utils.printPrice.php" );
//    // подключаем помощник для вьюшки
//    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
//    $request        = \imei_service\view\VH::getRequest();
//    // получаем объект-коллекцию unlockParent
//    $unlockParent   = $request->getObject('unlockParent');
//    // получаем объект-коллекцию unlockDetails
//    $unlockDetails  = $request->getObject('unlockDetails');
//    // получаем объект-коллекцию decorateUnlock
//    $decorateUnlock = $request->getObject('decorateUnlock');
//    // содержимое тега title
//    $title          = $unlockParent->getName();
    $title = "Проверка iPhone на blacklist";
    // содержимое тега meta
    $keywords       = "unlock iPhone,официальный анлок,AT&T,Orange,UK,USA,Bouygues,Telia,SFR,Vodafone,T-mobile,Verizon";
    // содержимое тега meta
    $description    = "Официальный анлок iPhone. Стоимость разлочки iPhone зависит от оператора, к которому он привязан.";

    // подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );


    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <title><?php echo htmlspecialchars($title, ENT_QUOTES); ?></title>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <meta content="width=1024" name="viewport">
        <meta content="<? echo htmlspecialchars($description, ENT_QUOTES); ?>" name="Description">
        <link rel="shortcut icon" href="/favicon.ico"/>
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link href="imei_service/view/css/animatedCSS.css" type="text/css" rel="stylesheet">
        <link href="imei_service/view/css/form.css" type="text/css" rel="stylesheet">
        <link href="imei_service/view/css/style.css" type="text/css" rel="stylesheet">
        <link href="imei_service/view/css/home-style.css" type="text/css" rel="stylesheet">
        <!--    <link href="css/wysiwyg.css" type="text/css" rel="stylesheet">-->
        <!--    <link href="css/style_enhanced.css" type="text/css" rel="stylesheet">-->
        <script type="text/javascript" src="imei_service/view/js/AlezhalModules.js"></script>
        <script type="text/javascript" src="imei_service/view/js/helperFunctions.js"></script>
        <script type="text/javascript" src="imei_service/view/js/wysiwyg.js"></script>
        <script type="text/javascript" src="imei_service/view/js/imei_form.js"></script>
        <script type="text/javascript" src="imei_service/view/js/utilities.js"></script>
        <script type="text/javascript" src="imei_service/view/js/lib.js"></script>
        <script type="text/javascript" src="imei_service/view/js/load.js"></script>
        <script type="text/javascript" src="imei_service/view/js/currency.js"></script>
        <script type="text/javascript" src="imei_service/view/js/dragMaster.js"></script>
    </head>
    <body>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="?cmd=News"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="?cmd=CarrierCheck"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="?cmd=FastCheck"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a class="selected" href="?cmd=BlacklistCheck"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a href="?cmd=Faq"><span>Вопросы</span></a></li>
        </ul>
    </div><!-- End of header -->

    <div id="main"  class="">

        <!--        подключаем обработчик авторизации-->
        <!--    --><?php //require_once( "imei_service/view/utils/security_mod.php" ); ?>

        <div id="main-slogan" class="main-content" style="overflow: hidden;">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div><!-- End of main-slogan -->
        <div id="news-main" class="main-content">
            <div id="slogan"><span class='currency' id='uah'></span><span class='currency' id='usd'></span><span class='currency' id='eur'></span><span class='currency' id='rub'></span></div>
            <div id="showcase" class="content">
                <div id="design">
                    <div class="row block grid2col row block border">
                        <img class="hero-image flushleft" alt="Проверка iPhone на blacklist" src="imei_service/view/images/blacklist/blacklist.png" width="256" height="192">
                        <div class="column last" style="z-index: 2;">
                            <h1><a href=<?php echo $_SERVER[PHP_SELF].'/'?>>Проверка iPhone на blacklist</a></h1>
                            <div class='column last dividerdownmidi'>
                                <div>
                                    <div style='width: 160px; float: left; margin: 10px 0 20px 40px;'><b>Наименование</b></div>
                                    <div style='width: 156px; float: left; margin: 10px 0 20px 30px;'><b>Совместимость</b></div>
                                    <div style='width: 140px; float: left; margin: 10px 0 20px 65px;'><b>Стоимость</b></div>
                                    <div style='width: 190px; float: left; margin: 10px 0 20px 60px;'><b>Сроки</b></div>
                                </div>
                                <?php

                                echo "<div style='clear:both' >
                                        <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b>UK/USA</b></ins></div>
                                        <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>iPad/iPhone/iPod Touch</div>
                                        <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'>".printPrice(70)."</div>
                                        <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>от 3 минут<span class='buynow'><a style='display:block;' href='#' title='Для выбора кликните на названии и лот будет добавлен в корзину' >купить</a></span></div>
                                    </div>
                                    <div style='clear:both' >
                                        <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b>T-Mobile USA</b></ins></div>
                                        <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>iPad/iPhone/iPod Touch</div>
                                        <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'>".printPrice(70)."</div>
                                        <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>от 3 минут<span class='buynow'><a style='display:block;' href='#' title='Для выбора кликните на названии и лот будет добавлен в корзину' >купить</a></span></div>
                                    </div>
                                    <div style='clear:both' >
                                        <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b>AT&T/Verizon</b></ins></div>
                                        <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>iPad/iPhone/iPod Touch</div>
                                        <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'>".printPrice(35)."</div>
                                        <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>от 3 минут<span class='buynow'><a style='display:block;' href='#' title='Для выбора кликните на названии и лот будет добавлен в корзину' >купить</a></span></div>
                                    </div>";

                                echo "</div><!-- End of column last dividerdownmidi -->
                                <div class=dividerdownbottom style='width: 700px; height: 40px; clear : both;'></div>
                            </div><!-- End of column last -->
                        </div><!-- End of row block grid2col row block border -->
                    </div>";  // End of design

                                ?>


                            </div>  <!-- End of showcase -->
                        </div>  <!-- End of news-main -->
                    </div>  <!-- End of main -->

                    <div id="footer">
                        <div id="footer-content">
                            <ol id="breadcrumbs">
                                <li>
                                    <p>Designed by alezhal-studio</p>
                                </li>
                                <li>
                                    |
                                </li>
                                <li>
                                    <p>Copyright © 2012 - 2014 All rights reserved.</p>
                                </li>
                            </ol>
                        </div>
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



