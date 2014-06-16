<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;

require_once( "imei_service/view/ViewHelper.php" );

$request = \imei_service\view\VH::getRequest();
$contacts = $request->getObject( 'contacts' );

foreach ( $contacts as $contact ) {
    $name       = $contact->getName();
    $phone      = $contact->getPhone();
    $fax        = $contact->getFax();
    $email      = $contact->getEmail();
    $skype      = $contact->getSkype();
    $vk         = $contact->getVk();
    $address    = $contact->getAddress();
    $photo      = $contact->getPhoto();
    $photoSmall = $contact->getPhotoSmall();
    $alt        = $contact->getAlt();

}


$title = "Контакты";
$keywords = "udid,unlock,blacklist,carrier,iPhone,iPod,iPad,iTunes";
$description = "Официальный анлок iPhone позволит вам обновлять ваш аппарат в iTunes. Регистрация UDID в аккаунте разработчика нужен для безопасной установки iOS 7.1 бета 3. iPhone.
Проверка iPhone по IMEI/серийному номеру даст вам самую полную информацию о вашем iPhone.
Проверка iPhone на blacklist даст вам информацию о статусе вашего аппарата (потерян/украден/задолженность по контракту)";
require_once("imei_service/view/templates/top.php");
try {
?>
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
        <a href="?cmd=Guestbook"><div id="nav-guestbook" class="addNav-body rounded main-content"><h3 class="h3">Гостевая</h3></div></a>
        <a href="?cmd=Contacts"><div id="nav-contact" class="addNav-body rounded main-content"><h3 class="h3">Контакты</h3></div></a>
    </div>


    <div id="news-main" class="main-content">
    <div id="" class="contact-content">
    <div id='' class="contact-header">
        <h2  class="h2">Контакты</h2>
    </div>
    <div class='contact-container'>
        <div class='contact-string-body' >
            <div class='contact-title'>
                <!--                <h1>-->
                <!--                    <a href="http://imei-service.ru">Наши контакты</a>-->
                <!--                </h1>-->
            </div>
            <div class='view contact-image' >
                <img alt="Фото контрагента"  src="<?php echo 'imei_service/view/'.$photoSmall; ?>" >
                <div class="mask">
                    <h2>Спасибо за обращение</h2>
                    <p>Мы постараемся как можно скорее ответить на ваше обращение к нам.
                        Постарайтесь, чтобы ваш вопрос укладывался в контекст самой цели сайта.
                        Также вы можете воспользоваться 'Гостевой книгой'</p>
                </div>
            </div>
            <div class='contact-info'>

                <h3 class="h3"><b>Мы рады вас приветствовать на нашем сайте!</b></h3>
                <p><b><?php echo $name; ?></b></p>
                <ul class="contact-address">
                    <li>
                        <a class="" href="mailto:<?php echo $email; ?>"><div id="contactIcons"><img class="image-shadow" src="imei_service/view/icons/50x50/email_50x49.png" />
                                <div id="contactIconsDescr"> - <?php echo $email; ?></div>
                            </div></a>
                    </li>
                    <li>
                        <a class="" href="skype:<?php echo $skype; ?>?add"><div id="contactIcons"><img class="image-shadow" src="imei_service/view/icons/50x50/skype_50x50.png" />
                                <div id="contactIconsDescr"> - <?php echo $skype; ?></div>
                            </div></a>
                    </li>
                    <li>
                        <a class="" href="http://vk.com/<?php echo $vk; ?>"><div id="contactIcons"><img class="image-shadow" src="imei_service/view/icons/50x50/vkGroup_50x50.png" />
                                <div id="contactIconsDescr"> - группа VKontakte</div>
                            </div></a>
                    </li>
                </ul>
            </div>
        </div>

    <?php
echo "
                    </div><!-- End of contact-container -->
                 <div class=\"contact-footer\"></div><!-- End of contact-footer -->
            </div><!-- End of contact-content -->
        </div><!-- End of contact-main -->";


require_once( "imei_service/view/templates/bottom.php" );

} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}

?>