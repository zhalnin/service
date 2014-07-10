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
        <div id="news-main" class="main-content" style="overflow: hidden;">
            <div id="slogan"><span>&nbsp;</span></div>
            <div id="showcase" class="content">
                <div id="design">
                    <div class="row block grid2col row block border">
                        <img class="hero-image flushleft" alt="Корзина" src="imei_service/view/images/Apple_logo_black_shadow.png"  width="120" height="150">
                        <div class="column last">
                            <h1><a href="<?php echo $_SERVER[PHP_SELF] .'/'; ?>">Корзина</a></h1>
                            <div class='column last dividerdownmidi'>
                        <?php if( is_array( $colCatalogPosition )  && $_SESSION['total_items_imei_service'] != 0 ) {
                                ?>
                                <form action="?cmd=Cart&act=update" method="post" >
                                    <div>
                                        <div style='width: 160px; float: left; margin: 10px 0 20px 50px;'><b>Наименование</b></div>
                                        <div style='width: 156px; float: left; margin: 10px 0 20px 50px;'><b>Стоимость</b></div>
                                        <div style='width: 140px; float: left; margin: 10px 0 20px 65px;'><b>Количество</b></div>
                                        <div style='width: 190px; float: left; margin: 10px 0 20px 60px;'><b>Всего</b></div>
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

                                            echo "<div style='clear: both;'>
                                                    <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b>$country</ins> ($operator)</b></div>
                                                    <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>".number_format( $cost, 2 )."</div>
                                                    <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'><input type=\"text\" maxlength=\"2\" size=\"2\" name=\"{$id_catalog}_{$position}\" value=\"{$index}\" /></div>
                                                    <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>".number_format( $subtotal, 2 )."</div>
                                                </div>";


                                            $sum_subtotal = $sum_subtotal + $subtotal;
                                        }
                                    }
                                        echo "<div style='clear: both;'>
                                                    <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'>&nbsp;</div>
                                                    <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'></div>
                                                    <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'><input type='submit' name='update' value='Обновить' /></div>
                                                    <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'></div>
                                                </div>
                                                <div style='clear: both;'>
                                                    <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'>&nbsp;</div>
                                                    <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'>&nbsp;</div>
                                                    <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'><input type='button' name='checkout' value='Оформить заказ' /></div>
                                                    <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>Итого: ".number_format( $sum_subtotal, 2 )."</div>
                                                </div>
                                                </form>";
                                } else {
                                    echo "<h2>Ваша корзина пуста</h2>";
                                }
                                ?>


                                </div><!-- End of column last dividerdownmidi -->

                            <div class='dividerdownbottom' style='width: 700px; height: 40px; clear : both;'></div>
                        </div><!-- End of column last -->
                    </div><!-- End of row block grid2col row block border -->
                </div><!-- End of design -->
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
