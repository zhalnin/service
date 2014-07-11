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
                            <h1><a href="#">Ваша корзина</a></h1>

                        <?php if( is_array( $colCatalogPosition )  && $_SESSION['total_items_imei_service'] != 0 ) {
                                ?>
                            <div class='column last dividerdownmidi'>
                                <form action="?cmd=Cart&act=update" method="post" >
                                    <div class="cart_table">
                                        <div class="cart_table_name"><b>Наименование</b></div>
                                        <div class="cart_table_cost"><b>Стоимость</b></div>
                                        <div class="cart_table_quantity"><b>Количество</b></div>
                                        <div class="cart_table_total"><b>Всего</b></div>
                                    </div>

                                    <?php
                                    $sum_subtotal = 0;

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
                                                $cost = $in->getCost();
                                                $id_catalog = $in->getIdCatalog();
                                                $position = $in->getPos();
                                            }
                                            $subtotal = $index * $cost;
?>
                                                <div class="cart_table_div" style='clear: both;'>
                                                    <div class="cart_table_name_div" id='operator'><ins><b><?php echo $operator; ?></ins><?php if( ! empty( $country ) )  echo ' ('.$country.') '; ?></b></div>
                                                    <div class="cart_table_cost_div" id='compatible'><?php echo number_format( $cost, 2 ); ?></div>
                                                    <div class="cart_table_quantity_div" id='timeconsume'><input type="text" maxlength="2" size="2" <?php echo 'name='. $id_catalog.'_'.$position.' value='.$index ?> /></div>
                                                    <div class="cart_table_total_div" id='timeconsume'><span class="table_total_div_currency">RUB</span><span class="table_total_div_value"><?php echo number_format( $subtotal, 2 ); ?></span></div>
                                                </div>

<?php
                                            $sum_subtotal = $sum_subtotal + $subtotal;
                                        }
                                    }
                                        echo "<div style='clear:both;float: left; width: 900px;'>
                                                <div style='clear:both; width: 300px; float: right;'><span style='float: left; display: block;  margin: 64px 0 20px 20px;'>Итого: </span><span style='float: right; display: block; margin: 64px 90px 20px 0;' >RUB&nbsp;&nbsp;".number_format( $sum_subtotal, 2 )."<span></div>
                                                <div style='clear:both; width: 800px;'><input class='cart_refreshCode' type='submit' name='update' value='' /></div>
                                                <div style=' width: 760px;'><span style='float: right; margin: 0 10px 20px 0; display: block;'>Обновить корзину: </span></div>
                                                <div style='clear:both; width: 738px; display: block;'><input style='float: right; margin-bottom: 20px;' type='button' name='checkout' value='Оформить заявку' /></div>
                                                <div style='clear:both; width: 672px; display: block;'><input style='float: right; margin-bottom: 20px;' type='button' name='checkout' value='PayPal' /></div>
                                              </div>
                                                </form>";
                                        echo "</div>"; // End of column last dividerdownmidi
                                        echo "<div class='dividerdownbottom' style='width: 700px; height: 40px; clear : both;'></div>";
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
