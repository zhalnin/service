<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/26/13
 * Time: 2:06 PM
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
require_once("count.php");
$title = "Быстрая проверка по IMEI iPhone";
$description = "Бесплатная проверка по IMEI. Быстрая проверка по IMEI iPhone поможет определить, к какому оператору привязан аппарат без отправления формы.";

require_once("templates/top.php");

?>

<div id="header">
    <ul id="navigation" role="navigation">
        <li id="nav-home"><a href="index.php"><span>Главная</span></a></li>
        <li id="nav-unlock"><a href="unlock.php"><span>Официальный Анлок iPhone</span></a></li>
        <li id="nav-udid"><a href="udid.php"><span>Регистрация UDID</span></a></li>
        <li id="nav-carrier"><a href="carrier_check.php"><span>Проверка оператора по IMEI</span></a></li>
        <li id="nav-fast_check"><a  class="selected" href="fast_check.php"><span>Быстрая проверка</span></a></li>
        <li id="nav-blacklist"><a href="blacklist_check.php"><span>Blacklist</span></a></li>
        <li id="nav-faq"><a href="faq.php"><span>Вопросы</span></a></li>
    </ul>
</div>

<div id="main"  class="main-content">
    <div id="progressbar"></div>
    <!--    Будут представлены аппарататы, которые доступны анлоку-->
    <div id="showcase" class="content">
        <div class="hero selfclear">
            <div id="shipping-box" class="box box-nomargin shipping-box ptn">
                <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
                <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;" ><a href="<?php echo $_SERVER[PHP_SELF] ?>">Быстрая и бесплатная проверка по IMEI iPhone</a></h2>
                <div id="shipping" class="step edit" style="opacity: 1;">
                    <div class="step-content top-divided" style="">
                        <div id="shipping-contact-form" class="step-mode edit clearfix" style="">
                            <div class="gs grid-1of2" style="">
                                <div id="shipping-user" class="user-form-block substep" style="">
                                    <form method="post">
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
                                                        <input id="imei" class="imei" type="text" maxlength="" size="8" name="imei" />
                                                    </span>
                                                </div>
                                            </div>
                                            <input id="item" type="hidden" name="item" value="официальный анлок iPhone" />
                                            <input id="type" type="hidden" name="type" value="unlock" />
                                            <input id="mode" type="hidden" name="type" value="fast_check" />
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="gs grid-1of2 gs-last" style="">
                                <div class="substep" style="">
                                    <div id="payment-form-astro" class="form-astro with-seperator">
                                        <p class="legend" style="">
                                            <strong id="coherent_id_103"><a href="unlock.php" class=" metrics-link">Сделать заказ на отвязку iPhone</a> </strong>
                                            <a href="carrier_check.php" class="separated-link metrics-link">Проверить на SIM Lock</a>
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
                <p>Copyright © 2013 All rights reserved.</p>
            </li>
        </ol>
    </div>
</div><!-- footer-->
</body>
</html>