<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/06/14
 * Time: 17:18
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем обработчик bbcode
    require_once( "imei_service/view/utils/utils.printPage.php" );
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );
    // подключаем обработчик курса валют
    require_once( "imei_service/view/utils/utils.printPrice.php" );

    // получаем объект request
    $request        = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию udidCollection
    $udidCollection = $request->getObject( 'udidCollection' );
    $udidDetailsCollection = $udidCollection->getUdidDetails();
    // содержимое тега title
    $title          = $udidCollection->getName();
    // содержимое тега meta
    $keywords       = $udidCollection->getKeywords();
    // содержимое тега meta
    $description    = "Регистрация UDID iOS 8 в аккаунте разработчика позволит вам устанавливать прошивки бета-версии без опасения, что аппарат не активируется. Также появляется возможность установки платных приложений бесплатно.";

//    echo "<tt><pre>".print_r( $udidDetailsCollection, true) ."</pre></tt>";
//    echo "<tt><pre>".print_r( $udidCollection, true) ."</pre></tt>";
    // подключаем верхний шаблон
    require_once( "imei_service/view/templates/top.php" );

    ?>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="?cmd=News"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a  class="selected" href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
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
    <div id="news-main" class="main-content" style="overflow: hidden;">
        <div id="slogan"><span class='currency' id='uah'></span><span class='currency' id='usd'></span><span class='currency' id='eur'></span><span class='currency' id='rub'></span></div>
            <div id="showcase" class="content">
                <div id="design">
                <div class="row block grid2col row block border">
                    <img class="hero-image flushleft" alt="<?php echo $udidCollection->getAlt(); ?>" src="imei_service/view/<?php echo $udidCollection->getUrlPict();?>" width="256" height="192" >
                <div class="column last">
                    <h1><a href="<?php echo $_SERVER[PHP_SELF] .'?cmd=Unlock&ctr='.$udidCollection->getAbbreviatura().'&idp='.$udidCollection->getIdParent() ?>"><?php echo $udidCollection->getName();?></a></h1>
                <div class='column last dividerdownmidi'>
                    <div style='clear:both'>
                        <div style='width: 160px; float: left; margin: 10px 0 20px 50px;'><b>Оператор</b></div>
                        <div style='width: 156px; float: left; margin: 10px 0 20px 50px;'><b>Совместимость</b></div>
                        <div style='width: 140px; float: left; margin: 10px 0 20px 65px;'><b>Стоимость</b></div>
                        <div style='width: 190px; float: left; margin: 10px 0 20px 60px;'><b>Сроки</b></div>
                    </div>
                    <?php foreach ( $udidDetailsCollection as $uda ): ?>
                        <div  style='clear:both'>
                            <div style='width: 160px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b><?php echo $uda->getOperator(); ?></b></ins></div>
                            <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='compatible'><?php echo $uda->getCompatible() . " " . $uda->getStatus();  ?></div>
                            <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'><?php echo printPrice($uda->getCost()); ?></div>
                            <div style='width: 260px; float: left; margin: 10px 0 0 10px;' id='timeconsume'><?php echo $uda->getTimeconsume()?><span class='buynow'><a style='display:block;' href='?cmd=Unlock&act=add_to_cart&ctr=<?php echo $_GET['ctr']; ?>&idp=<?php echo $_GET['idp']; ?>&idc=<?php echo $_GET['idc']; ?>&pos=<?php echo $uda->getPos(); ?>' title='Для выбора кликните на названии и лот будет добавлен в корзину' >купить</a></span></div>
                        </div>
                    <?php endforeach;

                echo "</div><!-- End of column last dividerdownmidi -->
                                            <div class=\"dividerdownbottom\"; style='width: 700px; height: 40px; clear : both;'></div>
                                        </div><!-- End of column last -->
                                    </div><!-- End of row block grid2col row block border -->
                                </div>";  // End of design
        ?>
            </div><!-- showcase -->
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