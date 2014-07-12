<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/07/14
 * Time: 17:25
 */
namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем обработчик bbcode
    require_once( "imei_service/view/utils/utils.printPage.php" );
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request        = \imei_service\view\VH::getRequest();
    // получаем сообщения об ошибках
    $feedback = $request->getFeedback();
//    echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
    // получаем объект-коллекцию udidCollection
//    $udidCollection = $request->getObject( 'udidCollection' );
//    // содержимое тега title
//    $title          = $udidCollection->getName();
//    // содержимое тега meta
//    $keywords       = $udidCollection->getKeywords();
    // содержимое тега meta
    $description    = "Регистрация UDID iOS 8 в аккаунте разработчика позволит вам устанавливать прошивки бета-версии без опасения, что аппарат не активируется. Также появляется возможность установки платных приложений бесплатно.";

    // подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );

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

    <div id="main"  class="">

        <?php require_once( "utils/security_mod.php" ); ?>

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>

        <div id="showcase" class="main-content">
            <div class="hero selfclear">
                <div id="shipping-box" class="box box-nomargin shipping-box ptn">
                    <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
                    <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;"><?php echo "Оформление заявки на:";?></h2>
                    <div id="shipping" class="step edit" style="opacity: 1;">
                        <div class="step-content top-divided" style="">
                            <?php
                            $subtotal = 0.00;
                            foreach( $_POST as $key => $val ) {
                                if( preg_match('|amount_(.*)|', $key, $match ) ) {
                                    $count = $match[1];
                                }
                            }

                            for( $i=1; $i <= $count; $i++ ) {

                                $item_name      = $_POST['item_name_'.$i];
                                $item_number    = $_POST['item_number_'.$i];
                                $amount         = $_POST['amount_'.$i];
                                $quantity       = $_POST['quantity_'.$i];
                                $total_cost     = $amount * $quantity;

                                ?>

                                <?php
                                echo "<span id='item_name' style='width: 300px; margin: 0 10px; display: inline-block'>$i. ".$item_name."</span><span id='quantity' style='width: 50px; margin: 0 10px; display: inline-block'>".$quantity." ед.</span><span id='amount'  style='width: 200px; margin: 0 10px; display: inline-block;'>по ".number_format( $amount, 2 )." RUB,</span><span id='subtotal' style='width: 250px; margin: 0 10px; display: inline-block;'> всего: ".number_format( $total_cost, 2 )." RUB</span><br />";

                                $subtotal = $subtotal + $total_cost;
                            }
                            echo "<span style='width: 500px; margin: 10px 10px; display: inline-block;'><ins>Сумма к оплате: ".number_format( $subtotal, 2 )." RUB</ins></span>";
                            ?>
                            <div class="top-divided"></div>
                            <div id="shipping-contact-form" class="step-mode edit clearfix" autocomplete="off" style="">
                                <div class="gs grid-1of2" style="">
                                    <div id="shipping-user" class="user-form-block substep" style="">
                                        <form method="post" id="form">
                                            <fieldset class="US first user-form-fieldset" style="">
                                                <legend style="">
                                                    <strong class="label"><?php echo "Заполните все поля";?></strong>
                                                </legend>
                                                <div id="shipping-user-address_section" class="user-address fieldset-content" style="">
                                                    <input type="submit" value="send" >
                                                    <div class="mbs" style="">
                                                        <span class="companyName-field field-with-placeholder" style="">
                                                            <label class="placeholder" for="shipping-user-companyName" style="">
                                                                <span>Адрес Email (обязательно)</span>
                                                            </label>
                                                            <input id="email" class="email" type="email" maxlength="35" size="35" name="email">
                                                        </span>
                                                    </div>
                                                    <div class="mbs" style="">

                                                        <span class="udidPhone-field field-with-placeholder" style="">
                                                            <label class="placeholder" for="shipping-user-udidPhone" style="">
                                                                <span>UDID или IMEI (обязательно)</span>
                                                            </label>
                                                            <textarea name="data" class="textarea" id="cart_order"></textarea>
                                                        </span>
                                                    </div>
                                                </div>
                                                <?php
                                                foreach( $_POST as $key => $val ) {
                                                    if( preg_match('|amount_(.*)|', $key, $match ) ) {
                                                        $count = $match[1];
                                                    }
                                                }
                                                for( $i=1; $i <= $count; $i++ ):?>

                                                    <input type='hidden' <?php echo "name='item_name_$i'"?> <?php echo "value='".$_POST['item_name_'.$i]."'" ?> />
                                                    <input type='hidden' <?php echo "name='amount_$i'"?> <?php echo "value='".$_POST['amount_'.$i]."'" ?> />
                                                    <input type='hidden' <?php echo "name='quantity_$i'"?> <?php echo "value='".$_POST['quantity_'.$i]."'" ?> />
                                                    <input type='hidden' <?php echo "name='total_cost_$i'"?> <?php echo "value='".$_POST['amount_'.$i] * $_POST['quantity_'.$i]."'" ?> />

                                             <?php endfor; ?>
                                                <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                                                <input type="hidden" name="sid_add_message" value="<?php echo $sid_add_message; ?>" />
                                                <input type="hidden" name="submitted" value="yes" />
                                            </fieldset>
                                        </form>
                                    </div><!-- shipping user -->
                                </div><!-- shipping-box-title -->
                                <div class="gs grid-1of3 gs-last fdescr fbl" style="">
                                    <div class="substep" style="">
                                        <div id="payment-form-astro" class="form-astro with-seperator">
                                            <p class="legend" style="">
                                                <strong id="coherent_id_103">Инструкция</strong>
                                                <a href="?cmd=Faq" class="separated-link metrics-link">Часто задаваемые вопросы</a>
                                            </p>
                                            <br />
                                            <p>
                                                <?php
                                                echo "В поле для email укажите ваш действительный адрес электронной почты.<br />
                                                        Он будет использован для связи с вами.<br />
                                                        В текстовом поле укажите UDID или IMEI заказываемой услуги.<br />
                                                        После нажатия кнопки 'Отправить' вы получите на указанный адрес email письмо
                                                        с реквизитами для оплаты выбранной услуги.<br />
                                                        Ваша заявка будет сохранена и действительна в течение 24 часов, если по истечении указанного срока
                                                        услуга не будет оплачена, то она будет аннулирована.";
                                                ?>
                                            </p>
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
                                <div class="gs grid-1of4 gs-last r-align" style="">
                                    <div id="shipping-button" class="button rect transactional blues" title="Сбросить" type="button" style="">
                                        <span style="">
                                            <span class="effect"></span>
                                            <span class="label"> Сбросить </span>
                                        </span>
                                    </div><!-- shipping-button -->
                                </div><!-- gs grid-1of4 gs-last r-align -->
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