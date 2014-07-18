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
    $request                = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию catalogCollection
    $colCatalogPosition     = $request->getObject( 'cartCatalogPosition' );
    $colCatalog             = $request->getObject( 'cartCatalog' );


//    echo "<tt><pre>".print_r( $colCatalogPosition , true ) ."</pre></tt>";
    // содержимое тега title
    $title          = "Корзина";
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
            <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
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
        <div id="news-main" class="main-content" style="overflow: hidden;">
            <div class="news-slogan"></div>
            <div id="slogan"><span>&nbsp;</span></div>
            <div id="showcase" class="content">
                <div id="design">
                    <div class="row block grid2col row block border">
                        <img class="hero-image flushleft" alt="Корзина" src="imei_service/view/images/country_flag/Apple_logo_black_shadow_256x192.png"  width="256" height="192">
                        <div class="column last">
                            <h1>Ваша корзина</h1>

                        <?php if( is_array( $colCatalogPosition )  && $_SESSION['total_items_imei_service'] != 0 ) { ?>

                            <div class='column last dividerdownmidi'>

<!--                                Форма для обновления корзины-->
                                <form action="?cmd=Cart&act=update" method="post" >
                                    <div class="cart_table">
                                        <div class="cart_table_name"><b>Наименование</b></div>
                                        <div class="cart_table_cost"><b>Стоимость</b></div>
                                        <div class="cart_table_quantity"><b>Количество</b></div>
                                        <div class="cart_table_total"><b>Всего</b></div>
                                    </div>

                                    <?php
                                    $sum_subtotal = 0;
                                    $sum_shipping = 0;
                                    $shipping = 0;

                                    // echo "<tt><pre> - ".print_r( $colCatalog , true )."</pre></tt>";
                                    // подсчитываем результирующее количество в коллекции и проходим в цикле
                                    for( $i=0; $i < count( $colCatalogPosition ); $i++ ) {
                                        // получаем по индексу количество предметов по данной позиции
                                        foreach ( $colCatalogPosition[$i] as $index => $qty) {
                                            foreach( $colCatalog[$i][$index] as $int ) {
                                                $name       = $int->getName();
                                                $country    = $int->getTitleFlag() ;
                                            }
                                            foreach( $colCatalogPosition[$i][$index] as $in ) {
//                                                echo "<tt><pre> - ".print_r( $in , true )."</pre></tt>";
                                                $operator   = $in->getOperator();
                                                $status     = $in->getStatus();
                                                $cost = $in->getCost();
                                                $id_catalog = $in->getIdCatalog();
                                                $position = $in->getPos();
                                            }
                                            $subtotal = $index * $cost;
?>
                                                <div class="cart_table_div" style='clear: both;'>
                                                    <div class="cart_table_name_div" id='operator'><ins><b><?php echo $operator; ?></ins><?php if( ! empty( $country ) )  echo ' ('.$country.') '; ?><?php if( ! empty( $status ) )  echo  $status; ?></b></div>
                                                    <div class="cart_table_cost_div" id='compatible'><?php echo number_format( $cost, 2 ); ?></div>
                                                    <div class="cart_table_quantity_div" id='timeconsume'><input type="text" maxlength="2" size="2" <?php echo 'name='. $id_catalog.'_'.$position.' value='.$index ?> /></div>
                                                    <div class="cart_table_total_div" id='timeconsume'><span class="table_total_div_currency">RUB</span><span class="table_total_div_value"><?php echo number_format( $subtotal, 2 ); ?></span></div>
                                                </div>
<?php
                                            $sum_subtotal = $sum_subtotal + $subtotal;
                                        }
                                    }
                                    echo "</div>"; // End of column last dividerdownmidi
                                    echo "<div class='dividerdownbottom' style='width: 700px; height: 40px; clear : both;'></div>";

                                        echo "<div style='clear:both;float: left; width: 900px;'>
                                                <div class='table_total_checkout'><span class='total_checkout'>Итого: </span><span class='cost_checkout' >RUB&nbsp;&nbsp;".number_format( $sum_subtotal, 2 )."<span></div>
                                                <div id='bot-nav_cart' class='table_update_checkout'><a class='btn bigblue' ><input id='signInHyperLinkMini' class='' type='submit' name='update' value='Обновить корзину' /></a></div>
                                              </div>
                                                </form>";
                                    ?>

<!--                                    Форма оформления заказа-->
                                    <form action="?cmd=CartOrder" method="post">
                                        <input type="hidden" name="upload" value="1" >
                                        <input type="hidden" name="business" value="zhalninpal-facilitator@me.com" >

                                        <?php
                                        $num = 1;
                                        $sum_subtotal = 0;
                                        $sum_shipping = 0;
                                        $shipping = 0;

                                        for( $i=0; $i < count( $colCatalogPosition ); $i++ ) {
                                            // получаем по индексу количество предметов по данной позиции
                                            foreach ( $colCatalogPosition[$i] as $index => $qty) {
                                                foreach( $colCatalog[$i][$index] as $int ) {
                                                    $name       = $int->getName();
                                                    $country    = $int->getTitleFlag();
                                                }
                                                foreach( $colCatalogPosition[$i][$index] as $in ) {
//                                                        echo "<tt><pre> - ".print_r( $in , true )."</pre></tt>";
                                                    $operator   = $in->getOperator();
                                                    $status     = $in->getStatus();
                                                    $cost       = $in->getCost();
                                                    $id_catalog = $in->getIdCatalog();
                                                    $position   = $in->getPos();
                                                }
                                                $subtotal = $index * $cost;
                                                ?>

                                                <input type="hidden" name="item_name_<?php echo $num; ?>" value="<?php echo $operator; ?><?php if( ! empty( $country ) )  echo ' ('.$country.')'; ?><?php if( ! empty( $status ) )  echo  $status; ?>" >
                                                <input type="hidden" name="item_number_<?php echo $num; ?>" value="<?php echo $id_catalog.'_'.$position; ?>" >
                                                <input type="hidden" name="amount_<?php echo $num; ?>" value="<?php echo $cost; ?>" >
                                                <input type="hidden" name="quantity_<?php echo $num; ?>" value="<?php echo $index; ?>" >

                                                <?php
                                                $num++;
                                                $sum_subtotal = $sum_subtotal + $subtotal;
                                            }
                                            if( $sum_subtotal <= 10 ) {
                                                $sum_shipping = $sum_subtotal;
                                            } else {
                                                $sum_shipping = $sum_subtotal / 100  * 3.9  + 10;
                                            }
                                        }
                                        ?>
                                        <input type="hidden" name="shipping_1" value="<?php echo $sum_shipping; ?>" >
                                        <div id='bot-nav_cart' class='table_order_checkout'><a class='btn bigblue' ><input id='signInHyperLink' style='float: right; margin-bottom: 20px;' type='submit' name='checkout' value='Оформить заявку' /></a></div>
                                    </form>


<!--                                    Форма для оплаты на PayPal-->
                                    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                        <input type="hidden" name="cmd" value="_cart" >
                                        <input type="hidden" name="upload" value="1" >
                                        <input type="hidden" name="business" value="zhalninpal-facilitator@me.com" >

                                        <?php
                                        $num = 1;
                                        $sum_subtotal = 0;
                                        $sum_shipping = 0;
                                        $shipping = 0;

                                        for( $i=0; $i < count( $colCatalogPosition ); $i++ ) {
                                            // получаем по индексу количество предметов по данной позиции
                                            foreach ( $colCatalogPosition[$i] as $index => $qty) {
                                                foreach( $colCatalog[$i][$index] as $int ) {
                                                    $name       = $int->getName();
                                                    $country    = $int->getTitleFlag();
                                                }
                                                foreach( $colCatalogPosition[$i][$index] as $in ) {
//                                                        echo "<tt><pre> - ".print_r( $in , true )."</pre></tt>";
                                                    $operator   = $in->getOperator();
                                                    $status     = $in->getStatus();
                                                    $cost       = $in->getCost();
                                                    $id_catalog = $in->getIdCatalog();
                                                    $position   = $in->getPos();
                                                }
                                                $subtotal = $index * $cost;
                                                if( $cost <= 10 ) {
                                                    $shipping = $cost;
                                                } else {
                                                    $shipping = $cost / 100  * 3.9  + 10;
                                                }
                                                    ?>

                                                    <input type="hidden" name="item_name_<?php echo $num; ?>" value="<?php echo $operator; ?><?php if( ! empty( $country ) )  echo ' ('.$country.')'; ?><?php if( ! empty( $status ) )  echo  $status; ?>" >
                                                    <input type="hidden" name="item_number_<?php echo $num; ?>" value="<?php echo $id_catalog.'_'.$position; ?>" >
                                                    <input type="hidden" name="amount_<?php echo $num; ?>" value="<?php echo $cost; ?>" >
                                                    <input type="hidden" name="quantity_<?php echo $num; ?>" value="<?php echo $index; ?>" >

                                                    <?php
                                                $num++;
                                                $sum_subtotal = $sum_subtotal + $subtotal;
//                                                $sum_shipping = $sum_shipping + $shipping;

                                            }
                                            if( $sum_subtotal <= 10 ) {
                                                $sum_shipping = $sum_subtotal;
                                            } else {
                                                $sum_shipping = $sum_subtotal / 100  * 3.9  + 10;
                                            }
                                        }
                                            ?>

                                        <input type="hidden" name="currency_code" value="RUB" >
                                        <input type="hidden" name="lc" value="RUS" >
                                        <input type="hidden" name="rm" value="2" >
                                        <input type="hidden" name="shipping_1" value="<?php echo $sum_shipping; ?>" >
                                        <input type="hidden" name="return" value="http://zhalnin.tmweb.ru/runner.php?cmd=PaypalThankYou" >
                                        <input type="hidden" name="cancel_return" value="http://zhalnin.tmweb.ru/runner.php?cmd=News" >
                                        <input type="hidden" name="notify_url" value="http://zhalnin.tmweb.ru/runner.php?cmd=Paypal" >
                                        <input type="image" src="imei_service/view/images/paypal/paypal_mini.png" name="pay now" value="pay" class="pay-button" width="150" height="37" />
                                    </form>

                                    <?php

                                       // echo "</div>"; // End of column last dividerdownmidi
                                       // echo "<div class='dividerdownbottom' style='width: 700px; height: 40px; clear : both;'></div>";
                                } else {
                                    echo "<h2 class='h2 empty_cart'>Вы не совершили ни одной покупки</h2>";
                                }
                                ?>
                        </div><!-- End of column last -->
                    </div><!-- End of row block grid2col row block border -->
                </div><!-- End of design -->
            </div>  <!-- End of showcase -->
            <div class="news-footer"></div>
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
