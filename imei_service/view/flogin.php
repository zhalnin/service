<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/06/14
 * Time: 19:38
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request        = \imei_service\view\VH::getRequest();
    $email          = "value='".$_POST['email']."'"; // получаем email
    // содержимое тега title
    $title          = "Восстановление логина и пароля";
    // содержимое тега meta
    $keywords       = "udid registration,регистрация udid,аккаунт разработчика,iOS 8 beta,iOS8 бета,провижен профиль,provision";
    // содержимое тега meta
    $description    = "Регистрация UDID iOS 8 в аккаунте разработчика позволит вам устанавливать прошивки бета-версии без опасения, что аппарат не активируется. Также появляется возможность установки платных приложений бесплатно.";

    //  подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );

    $feedback       = $request->getFeedback(); // получаем сообщения об ошибках

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

    <div id="main"  class="main-content">
        <div id="showcase" class="content">
            <div class="hero selfclear">
                <div id="shipping-box" class="box box-nomargin shipping-box ptn">
                    <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
                    <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;"><a href="?cmd=FLogin">Восстановление логина и пароля</a></h2>
                    <div id="shipping" class="step edit" style="opacity: 1;">
                        <div class="step-content top-divided" style="">
                            <div id="shipping-contact-form" class="step-mode edit clearfix" autocomplete="off" style="">
                                <div class="gs grid-1of2" style="position: static;">
                                    <div id="shipping-user" class="user-form-block substep" style="">
                                        <form method="post" id="flogin-form">
                                            <fieldset class="US first user-form-fieldset" style="">
                                                <legend style="">
                                                    <strong class="label">Выслать учетные данные на указанный email</strong>
                                                </legend>
                                                <div id="shipping-user-address_section" class="user-address fieldset-content" style="">
                                                    <div class="mbs" style="">
                                                        <span class="login-field field-with-placeholder" style="">
                                                            <label class="placeholder" for="shipping-user-companyName" style="">
                                                                <span>Email</span>
                                                            </label>
                                                            <input id="email" class="email" type="text" name="email" <?php print $email; ?> />
                                                        </span>
                                                    </div>
                                                </div>
                                                <input id="item" type="hidden" name="item" value="Восстановить пароль>
                                                <input id="type" type="hidden" name="type" value="flogin">
                                                <input type="hidden" name="sid_add_message" value="<?php echo $sid_add_message; ?>" />
                                                <input type="hidden" name="submitted" value="yes" />
                                                <!--                                                <div id="bot-nav">-->
                                                <!--                                                    <a class="btn bigblue">-->
                                                <!--                                                        <input id="signInHyperLink" type="submit" class>-->
                                                <!--                                                    </a>-->
                                                <!--                                                </div>-->
                                            </fieldset>
                                        </form>
                                    </div><!-- shipping user -->
                                </div><!-- shipping-box-title -->
                                <div class="gs grid-1of3 gs-last fdescr fbl" style=" position: static;">
                                    <div class="substep" style="">
                                        <div id="payment-form-astro" class="form-astro with-seperator">
                                            <p class="legend" style="">
                                                <a href="?cmd=Register" class="metrics-link">Зарегистрироваться</a>
                                                <a href="?cmd=Login" class="separated-link metrics-link">Войти на сайт</a>
                                            </p>
                                            <br />
                                            <p>Если вы забыли логин или пароль вашей учетной записи, вам необходимо указать адрес email, который вы указывали при регистрации.</p>
                                            <p>После нажатия кнопки "Отправить" вы получите письмо на указанный адрес email с вашими учетными данными.</p>
                                        </div><!-- payment-form-astro -->
                                    </div><!-- substep -->
                                </div><!-- gs grid-lof2 gs-last -->
                            </div><!-- content -->
                        </div><!-- step-content top-divided -->

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
                                            </div><!-- shipping-continue-button -->
                                        </div><!-- shipping-step-defaults -->
                                    </div><!-- chat chat-now cchat -->
                                </div><!-- gs grid-3of4 -->
                            </div><!-- continue-content clearfix -->
                        </div><!-- step-continue part-edit clear -->
                        <?php
                        if( ! empty( $feedback ) ) { // Вывод сообщений об ошибках
                            print "<div class='guestbook-error' style='color: rgb(255, 0, 0);'>";
                            print "<ul>\n";
                            print "<li>\n";
                            print $request->getFeedbackString('</li><li>');
                            print "</li>\n";
                            print "</ul>\n";
                            print "</div>";
                        }
                        echo "</div>";
                        ?>
                    </div><!-- shipping -->
                </div><!-- shipping-box -->
            </div><!-- hero selfclear -->
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