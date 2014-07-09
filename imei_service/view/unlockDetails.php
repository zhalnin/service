<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 23:48
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;

error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем обработчик bbcode
    require_once( "imei_service/view/utils/utils.printPage.php" );
    // подключаем обработчик курса валют
    require_once( "imei_service/view/utils/utils.printPrice.php" );
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request        = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию unlockParent
    $unlockParent   = $request->getObject('unlockParent');
    // получаем объект-коллекцию unlockDetails
    $unlockDetails  = $request->getObject('unlockDetails');
    // получаем объект-коллекцию decorateUnlock
    $decorateUnlock = $request->getObject('decorateUnlock');
    // содержимое тега title
    $title          = $unlockParent->getName();
    // содержимое тега meta
    $keywords       = "unlock iPhone,официальный анлок,AT&T,Orange,UK,USA,Bouygues,Telia,SFR,Vodafone,T-mobile,Verizon";
    // содержимое тега meta
    $description    = "Официальный анлок iPhone. Стоимость разлочки iPhone зависит от оператора, к которому он привязан.";

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
    </div><!-- End of header -->

    <div id="main"  class="">

    <!--        подключаем обработчик авторизации-->
    <?php require_once( "utils/security_mod.php" ); ?>

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div><!-- End of main-slogan -->
            <div id="news-main" class="main-content">
                <div id="slogan"><span class='currency' id='uah'></span><span class='currency' id='usd'></span><span class='currency' id='eur'></span><span class='currency' id='rub'></span></div>
                <div id="showcase" class="content">
                    <div id="design">
                        <div class="row block grid2col row block border">
                            <img class="hero-image flushleft" alt="<?php echo $unlockParent->getAlt(); ?>" src="imei_service/view/<?php echo $unlockParent->getUrlPict();?>">
                            <div class="column last">
                                <h1><a href="<?php echo $_SERVER[PHP_SELF] .'?cmd=Unlock&ctr='.$unlockParent->getAbbreviatura().'&idp='.$unlockParent->getIdParent() ?>"><?php echo $unlockParent->getName();?></a></h1>
                                <div class='column last dividerdownmidi'>
                                    <div>
                                        <div style='width: 160px; float: left; margin: 10px 0 20px 50px;'><b>Оператор</b></div>
                                        <div style='width: 156px; float: left; margin: 10px 0 20px 50px;'><b>Совместимость</b></div>
                                        <div style='width: 140px; float: left; margin: 10px 0 20px 65px;'><b>Стоимость</b></div>
                                        <div style='width: 190px; float: left; margin: 10px 0 20px 60px;'><b>Сроки</b></div>
                                    </div>
            <?php foreach ( $unlockDetails as $uda ): ?>
                    <div>
                        <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b><?php echo $uda->getOperator(); ?></b></ins></div>
                        <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>iPhone <?php echo $uda->getCompatible(); ?></div>
                        <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'><?php echo printPrice($uda->getCost()); ?></div>
                        <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'><?php echo $uda->getTimeconsume() . " " . $uda->getStatus(); ?><span class='buynow'><a style='display:block;' href='?cmd=Unlock&act=add_to_cart&ctr=<?php echo $_GET['ctr']; ?>&idp=<?php echo $_GET['idp']; ?>&idc=<?php echo $_GET['idc']; ?>&pos=<?php echo $uda->getPos(); ?>' title='Для выбора кликните на названии и лот будет добавлен в корзину' >купить</a></span></div>
                    </div>
            <?php endforeach;

            echo "</div><!-- End of column last dividerdownmidi -->
                                <div class=\"dividerdownbottom\"; style='width: 700px; height: 40px; clear : both;'></div>
                            </div><!-- End of column last -->
                        </div><!-- End of row block grid2col row block border -->
                    </div>";  // End of design

?>
            <!--    <div style='width: 203px; float: left; margin: 10px 0 0 2px;' id='cost'>".$subcatalog[compatible]."</div>-->
            <!--    <div style='width: 130px; float: left; margin: 10px 0 0 40px;' id='timeconsume'>$subcatalog[timeconsume] $subcatalog[status]</div>-->
                    <div class="hero selfclear">
                        <div id="shipping-box" class="box box-nomargin shipping-box ptn">
                            <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
                            <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;" ><a href="<?php echo $_SERVER[PHP_SELF] .'?cmd=Unlock&?ctr='.$unlockParent->getAbbreviatura().'&idp='.$unlockParent->getIdParent() ?>"><?php echo $unlockParent->getName();?></a></h2>
                            <div id="shipping" class="step edit" style="opacity: 1;">
                                <div class="step-content top-divided" style="">
                                    <div id="shipping-contact-form" class="step-mode edit clearfix" style="">
                                        <div class="gs grid-1of2" style="">
                                            <div id="shipping-user" class="user-form-block substep" style="">
                                                <form method="post" id="unlockDetails-form">
                                                    <fieldset style="">
                                                        <legend style="">
                                                            <strong class="label"><b><?php echo $decorateUnlock->getOrderTitle(); ?></b></strong>
                                                        </legend>
                                                        <div id="shipping-user-address_section" class="user-address fieldset-content" style="">

                                                            <div class="mbs" style="">
                                                                <span class="companyName-field field-with-placeholder" style="">
                                                                    <label class="placeholder" for="shipping-user-companyName" style="">
                                                                        <span>Адрес Email (обязательно)</span>
                                                                    </label>
                                                                    <input id="email" class="email" type="email" maxlength="" size="35" name="email">
                                                                </span>
                                                            </div>
                                                            <div class="mbs" style="">

                                                                    <span class="daytimePhone-field field-with-placeholder" style="">
                                                                    <label class="placeholder" for="shipping-user-daytimePhone" style="">
                                                                        <span>IMEI (15-ть цифр)</span>
                                                                    </label>
                                                                    <input id="imei" class="imei" type="text" maxlength="" size="8" name="imei">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <input id="item" type="hidden" name="item" value="официальный анлок iPhone">
                                                        <input id="type" type="hidden" name="type" value="unlock">
                                                        <input id="operator" type="hidden" name="operator" value="<?php print $unlockParent->getName();?>">
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="gs grid-1of3 gs-last fdescr fbl" style="">
                                            <div class="substep" style="">
                                                <div id="payment-form-astro" class="form-astro with-seperator">
                                                    <p class="legend" style="">
                                                        <strong id="coherent_id_103">Условия</strong>
                                                        <a href="?cmd=CarrierCheck" class="separated-link metrics-link">Проверить iPhone на привязку к оператору</a>
                                                    </p>
                                                    <br />
                                                    <p>
                                                        <?php
                                                        echo nl2br(\imei_service\view\utils\printPage( $decorateUnlock->getDescription() ) );
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  <!--  End of shipping-contact-form -->
                                </div>  <!--  End of step-content top-divided -->
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
                            </div>  <!-- End of shipping -->
                        </div>  <!--  End of shipping-box -->
                    </div>  <!-- End of hero selfclear -->
            </div>  <!-- End of showcase -->
        </div>  <!-- End of news-main -->
        </div>  <!-- End of main -->
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