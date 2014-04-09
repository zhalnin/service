<<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/11/13
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
$title = "Заявка отправлена";
$description = "Оповещение о успешной отправки заявки на проверку iPhone по IMEI";
//require_once("templates/top.php");

require_once("count.php");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES); ?></title>
    <meta content="width=1024" name="viewport">
    <meta content="<? echo htmlspecialchars($description, ENT_QUOTES); ?>" name="Description">
    <meta http-equiv="refresh" content="15; url=http://imei-service.ru">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link href="css/home-style.css" type="text/css" rel="stylesheet">
    <link href="css/form.css" type="text/css" rel="stylesheet">
    <!--    <link href="css/style_enhanced.css" type="text/css" rel="stylesheet">-->
    <script type="text/javascript" src="js/helperFunctions.js"></script>
    <script type="text/javascript" src="js/utilities.js"></script>
    <script type="text/javascript" src="js/AlezhalModules.js"></script>
    <script type="text/javascript" src="js/load.js"></script>
    <script type="text/javascript" src="js/lib.js"></script>
    <script type="text/javascript" src="js/check.js"></script>
    <script type="text/javascript" src="js/currency.js"></script>
</head>
<body>

<div id="header">
    <ul id="navigation" role="navigation">
        <li id="nav-home"><a  class="selected" href="index.php"><span>Главная</span></a></li>
        <li id="nav-unlock"><a href="unlock.php"><span>Официальный Анлок iPhone</span></a></li>
        <li id="nav-udid"><a href="udid.php"><span>Регистрация UDID</span></a></li>
        <li id="nav-carrier"><a href="carrier_check.php"><span>Проверка оператора по IMEI</span></a></li>
        <li id="nav-fast_check"><a href="fast_check.php"><span>Быстрая проверка</span></a></li>
        <li id="nav-blacklist"><a href="blacklist_check.php"><span>Blacklist</span></a></li>
        <li id="nav-faq"><a href="faq.php"><span>Вопросы</span></a></li>
    </ul>
</div>

<div id="main" class="main-content">
    <div id="slogan">Быстро - Качественно - Надежно</div>
    <div id="showcase" class="content">
        <div id="design" class="success">
            <a class="row block grid2col row block border" href="index.php" >
                <img class="hero-image flushleft logo" alt="IMEI-service - Заявка на проверку iPhone на blacklist" src="images/Apple_logo_black_shadow.png">
                <div class="column last">
                    <h1>
                        Спасибо, что воспользовались нашим сервисом!
                    </h1>
                    <p>
                        Ваша заявка будет обработана в кратчайшие сроки!<br/><br/>
                        Через некоторое время вы получите письмо на тот почтовый адрес, что вы указали в заявке<br/><br/>
                        Мы вам сообщим:<br/>
                        ‣ О возможности отвязки <br/>
                        ‣ Сроки выполнения отвязки<br/>
                        ‣ Реквизиты для оплаты<br/>
                        ‣ Услуги 100% легальные<br/><br/>
                        <span class="nowrap">Гарантируем </span>

                        <span class="more">качество услуг и максимально короткие сроки</span>
                    </p>
                </div>
            </a>
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
                <p>Copyright © 2013 All rights reserved.</p>
            </li>
        </ol>
    </div>
</div><!-- footer-->

</body>
</html>