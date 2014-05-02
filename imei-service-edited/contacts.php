<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/05/14
 * Time: 20:58
 */

// Устанавливаем соединение с базой данных
//require_once("config/config.php");
require_once("class/class.Database.php");
// Подключаем FrameWork
require_once("config/class.config.php");
// Подключаем функцию вывода текста с bbCode
require_once("dmn/utils/utils.print_page.php");
Database::getInstance();

$title = "Контакты";
$keywords = "udid,unlock,blacklist,carrier,iPhone,iPod,iPad,iTunes";
$description = "Официальный анлок iPhone позволит вам обновлять ваш аппарат в iTunes. Регистрация UDID в аккаунте разработчика нужен для безопасной установки iOS 7.1 бета 3. iPhone.
Проверка iPhone по IMEI/серийному номеру даст вам самую полную информацию о вашем iPhone.
Проверка iPhone на blacklist даст вам информацию о статусе вашего аппарата (потерян/украден/задолженность по контракту)";
require_once("templates/top.php");
try
{
//    $url = "?id_news=1";

//mail("zhalninpal@me.com","test","test");
?>

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
<div id="main" class="">
<div id="main-slogan" class="main-content">
    <div id="slogan">Быстро - Качественно - Надежно</div>
</div>
<!--        End of main-slogan-->

<div id="addNav" class="main-content">
    <a href="guestbook.php"><div id="nav-guestbook" class="addNav-body rounded"><h3 class="h3">Гостевая</h3></div></a>
    <div id="addNav-border"></div>
    <a href="guestbook.php"><div id="nav-contact" class="addNav-body rounded"><h3 class="h3">Контакты</h3></div></a>
</div>


<div id="news-main" class="main-content">
<div id="" class="contact-content clear-fix">
<div id='' class="contact-header">
    <h2  class="h2">Контакты</h2>
</div>
<div class='contact-container'>

        <div class='contact-string-body superlink' onclick="detail_button('?id_news=1')">
            <div class='contact-title'>
                <h1>
                    <a href="http://imei-service.ru">Наши контакты</a>
                </h1>
            </div>
            <div class='contact-image'>
                <img class="" alt="Фото контрагента" src="files/news/Apple_logo_black_shadow.png" >
            </div>
            <div class='contact-info'>

                <h3 class="h3"><b>Мы рады вас приветствовать на нашем сайте!</b></h3>
                <p><b>Вы можете связаться с нами по одному из указанных реквизитов:</b></p>
                    <ul class="contact-address">
                        <li><b>Адрес электронной почты</b>
                        -
                        <a class="" href="http://imei-service.ru/unlock.php">imei_service@icloud.com</a></li>
                        <li><b>Skype</b>
                        -
                        <a class="" href="http://imei-service.ru/udid.php">Skype</a></li>
                        <li><b>Группа ВКонтакте</b>
                        -
                        <a class="" href="http://imei-service.ru/carrier_check.php">группа VKontakte</a></li>
                    </ul>
            </div>
        </div>


<?php



echo "
                    </div><!-- End of contact-container -->
                 <div class=\"contact-footer\"></div><!-- End of contact-footer -->
            </div><!-- End of contact-content -->
        </div><!-- End of contact-main -->";


require_once("templates/bottom.php");
}

catch(ExceptionMySQL $exc)
{
    require_once("exception_mysql_debug.php");
}
catch(ExceptionObject $exc)
{
    require_once("exception_object_debug.php");
}
catch(ExceptionMember $exc)
{
    require_once("exception_member_debug.php");
}


?>