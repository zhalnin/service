<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 19:05
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем обработчик bbcode
    require_once( "imei_service/view/utils/utils.printPage.php" );
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию unlock
    $unlocks = $request->getObject( 'unlock' );
    // получаем объект-коллекцию decorateUnlock
    $decorateUnlock = $request->getObject( 'decorateUnlock' );

    // содержимое тега title
    $title          = $decorateUnlock->getName();
    // содержимое тега meta
    $keywords       = $decorateUnlock->getKeywords();
    // содержимое тега meta
    $description    = "Официальный анлок iPhone от оператора не занимает много времени. Для этого надо лишь отправить IMEI аппарата на imei_service@icloud.com, оплатить услугу отвязки iPhone и ваш аппарат будет сим фри";

    // подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );
?>

        <div id="header">
            <ul id="navigation" role="navigation">
                <li id="nav-home"><a href="?cmd=News"><span>Главная</span></a></li>
                <li id="nav-unlock"><a  class="selected" href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
                <li id="nav-udid"><a href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
                <li id="nav-carrier"><a href="?cmd=CarrierCheck"><span>Проверка оператора по IMEI</span></a></li>
                <li id="nav-fast_check"><a href="?cmd=FastCheck"><span>Быстрая проверка</span></a></li>
                <li id="nav-blacklist"><a href="?cmd=BlacklistCheck"><span>Blacklist</span></a></li>
                <li id="nav-faq"><a href="?cmd=Faq"><span>Вопросы</span></a></li>
            </ul>
        </div><!-- header -->

    <div id="main"  class="">

        <!--        подключаем обработчик авторизации-->
        <?php require_once( "utils/security_mod.php" ); ?>

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>

            <!--    Будут представлены аппарататы, которые доступны анлоку-->
            <div id="pb-ipad" class="productbrowser main-content pb-dynamic"  style="overflow: hidden;">
                <div class="pb-slider">
                    <div class="pb-slide" style="width: 970px;">
                        <ul class="ul-slider" page="1" style="width: 960px; margin: 30px 5px;">


                            <!--    Будут представлены аппарататы, которые доступны анлоку-->
                            <?php
                            foreach ( $unlocks as $unlock ) {
                                echo "    <li><a href=?cmd=Unlock&ctr={$unlock->getAbbreviatura()}&idc={$unlock->getId()}&idp={$unlock->getIdParent()} class='started'>
                                <div>
                                    <div>
                                        <img alt=\"{$unlock->getAltFlag()}\"  src=\"imei_service/view/{$unlock->getRoundedFlag()}\" />
                                    </div>
                                </div>
                                        {$unlock->getTitleFlag()}</a></li>";

                            }
                            ?>


                        </ul>
                    </div><!-- pb-slide -->
                </div><!-- pb-slider -->
            </div><!-- pb-ipad -->

            <div id="showcase" class="main-content">

            </div><!-- showcase -->
        </div><!-- main -->

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