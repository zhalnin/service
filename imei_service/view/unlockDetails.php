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
    require_once( "imei_service/view/utils/utils.printPage.php" );
    require_once( "imei_service/view/utils/utils.printPrice.php" );
    require_once( "imei_service/view/ViewHelper.php" );
    $request = \imei_service\view\VH::getRequest();
    $unlockParent = $request->getObject('unlockParent');
    $unlockDetails = $request->getObject('unlockDetails');
    $decorateUnlock = $request->getObject('decorateUnlock');
    $title = $unlockParent->getName();
    $keywords = "unlock iPhone,официальный анлок,AT&T,Orange,UK,USA,Bouygues,Telia,SFR,Vodafone,T-mobile,Verizon";
    $description = "Официальный анлок iPhone. Стоимость разлочки iPhone зависит от оператора, к которому он привязан.";

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
                                        <div style='width: 130px; float: left; margin: 10px 0 20px 50px;'><b>Оператор</b></div>
                                        <div style='width: 156px; float: left; margin: 10px 0 20px 50px;'><b>Совместимость</b></div>
                                        <div style='width: 140px; float: left; margin: 10px 0 20px 65px;'><b>Стоимость</b></div>
                                        <div style='width: 120px; float: left; margin: 10px 0 20px 60px;'><b>Сроки</b></div>
                                    </div>
<?php
        foreach ( $unlockDetails as $uda ) {
                echo "<div>
                        <div style='width: 150px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b>".$uda->getOperator()."</b></ins></div>
                        <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>iPhone ".$uda->getCompatible()."</div>
                        <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'>".printPrice($uda->getCost())."</div>
                        <div style='width: 170px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>".$uda->getTimeconsume()." ".$uda->getStatus()."</div>
                    </div>";
            }
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
                                                <form method="post">
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
                                        <div class="gs grid-1of2 gs-last" style="">
                                            <div class="substep" style="">
                                                <div id="payment-form-astro" class="form-astro with-seperator">
                                                    <p class="legend" style="">
                                                        <strong id="coherent_id_103">Условия</strong>
                                                        <a href="carrier_check.php" class="separated-link metrics-link">Проверить iPhone на привязку к оператору</a>
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
    require_once( "imei_service/view/templates/bottom.php" );
} catch(\imei_service\base\AppException $exc){
    require_once( "imei_service/base/Exceptions.php" );
} catch(\imei_service\base\DBException $exc) {
    require_once( "imei_service/base/Exceptions.php" );
}
?>