<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 06/07/14
 * Time: 19:55
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request                = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию catalogCollection
    $colCatalogPosition     = $request->getObject( 'cartCatalogPosition' );
    $colCatalog             = $request->getObject( 'cartCatalog' );
//    // содержимое тега title
//    $title          = $catalog->getName();
//    // содержимое тега meta
//    $keywords       = $catalog->getKeywords();
//    // содержимое тега meta
//    $description    = $catalog->getDescription();

//    echo "<tt><pre>".print_r( $colCatalog, true )."</pre></tt>";
//    echo gettype( $colCatalogPosition );

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
            <li id="nav-faq"><a  class="selected" href="?cmd=Faq"><span>Вопросы</span></a></li>
        </ul>
    </div>
    <div id="main" class="">

        <!--        подключаем обработчик авторизации-->
        <?php require_once( "utils/security_mod.php" ); ?>

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>
        <!--        End of main-slogan-->

        <div id="news-main" class="main-content">
            <div id="" class="news-content clear-fix">
                <div id='' class="news-header">
                    <h2  class="h2">Корзина</h2>
                </div>
                <div class='news-container'>
                    <div class='faq-body'>

                        <?php
                        echo "<div class='faq-title'>
                                    <h1 class=h2>Содержимое корзины</h1>
                                </div>
                                <div class='faq-image'>
                                    <img alt='IMEI-service - Вопросы' src='imei_service/view/images/Apple_logo_black_shadow.png'/>
                                </div>

                                <div class='faq-info'>";

//                        item item_price qty subtotal
//
//                        1
//                        2
//                        3
//
//                                update  subtotal
//                                        shipping
//                                        grand total



                        //    echo "<tt><pre> - ".print_r( $colCatalogPosition , true )."</pre></tt>";


                        if( is_array( $colCatalogPosition )  && $_SESSION['total_items_imei_service'] != 0 ) {
//                                    echo "<tt><pre> total quantity - ".print_r( $colCatalogPosition , true )."</pre></tt>";
                            ?>
                            <form action="?cmd=Cart&act=update" method="post" >
                                <table width="100%" >
                                    <thead>
                                    <tr>
                                        <td>Наименование</td>
                                        <td>Стоимость</td>
                                        <td>Количество</td>
                                        <td>Всего</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <?php
                            $sum_subtotal = 0;
                            for( $i=0; $i < count( $colCatalogPosition ); $i++ ) {
                                foreach ( $colCatalogPosition[$i] as $index => $qty) {
                                    foreach( $colCatalog[$i][$index] as $int ) {
                                        echo "<td>{$int->getName()}</td>";
//                                        echo "<tt><pre> country - ".print_r( $qty , true )."</pre></tt>";
                                    }
                                    foreach( $colCatalogPosition[$i][$index] as $in ) {
                                        $item_price = $in->getCost();
                                        echo "<td>" . number_format( $in->getCost(), 2 ) . "</td>";
//                                        echo "<tt><pre> operator - ".print_r( $in->getOperator() , true )."</pre></tt>";
//                                        echo "<tt><pre> rub - ".print_r( $in->getPos() , true )."</pre></tt>";
                                    }
                                    $subtotal = $index * $item_price;
//                            echo "<tt><pre>".print_r( $in->getIdCatalog(), true )."</pre></tt>";
                                    echo "<td><input type=\"text\" maxlength=\"2\" size=\"2\" value=\"{$index}\"  name=\"{$in->getPos()}\" /></td>";
                                    ?>
                                          <td><?php echo number_format( $subtotal, 2 ) ?></td>
                                    <?php
    //                            echo "<tt><pre>".print_r( $colCatalogPosition[$i][$index] , true )."</pre></tt>";
//                                echo "<tt><pre>".print_r( $qty , true )."</pre></tt>";

                                    $sum_subtotal = $sum_subtotal + $subtotal;


                                }

//                                print "<br /> ----------------- <br />";
                                echo "</tr>";
                            }
                            ?>
                                  <tr>
                                    <td><input type="hidden" name="id_catalog" value="<?php echo $in->getIdCatalog(); ?>" /></td>
                                    <td><input type="submit" name="update" value="Обновить" /></td>
                                    <td>Итого</td>
                                    <td><?php echo number_format( $sum_subtotal, 2 ); ?></td>
                                  </tr>
                                  </tbody></table></form>
                            <?php
                            echo "<p><a href=\"#\" class=\"main_txt_lnk\">Оплатить</a></p>";

                        } else {
                            echo "<h2>Ваша корзина пуста</h2>";
                        }


                        echo "</div> "; // faq-info
                        ?>
                    </div>  <!-- End of faq-body -->
                </div>  <!--   End of news-container -->
            </div> <!-- End of news-content clear-fix -->
            <div class="news-footer"></div>
        </div><!--     End of news-content -->
    </div><!--        End of news-main-->


    <!--    <div id="main-guestbook"></div>-->
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