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
            <div id="news-main" class="main-content" style="overflow: hidden;">
                <div class="news-slogan">
                    <div id="slogan"><span class='currency' id='uah'></span><span class='currency' id='usd'></span><span class='currency' id='eur'></span><span class='currency' id='rub'></span></div>
                </div>
                <div id="showcase" class="content">
                    <div id="design">
                        <div class="row block grid2col row block border">
                            <img class="hero-image flushleft" alt="<?php echo $unlockParent->getAlt(); ?>" src="imei_service/view/<?php echo $unlockParent->getUrlPict();?>">
                            <div class="column last">
                                <h1><a href="<?php echo $_SERVER[PHP_SELF] .'?cmd=Unlock&ctr='.$unlockParent->getAbbreviatura().'&idp='.$unlockParent->getIdParent().'&idc='.$unlockParent->getId(); ?>"><?php echo $unlockParent->getName();?></a></h1>
                                <div class='column last dividerdownmidi'>
                                    <div class="table_items">
                                        <div class="table_name"><b>Наименование</b></div>
                                        <div class="table_cost"><b>Совместимость</b></div>
                                        <div class="table_quantity"><b>Стоимость</b></div>
                                        <div class="table_total"><b>Сроки</b></div>
                                    </div>
                            <?php foreach ( $unlockDetails as $uda ): ?>
                                    <div class="table_div" style='clear:both'>
                                        <div class="table_name_div" id='operator'><ins><b><?php echo $uda->getOperator(); ?></b></ins></div>
                                        <div class="table_quantity_div" id='compatible'><?php echo $uda->getCompatible() . " " . $uda->getStatus();  ?></div>
                                        <div class="table_cost_div" id='timeconsume'><?php echo printPrice($uda->getCost()); ?></div>
                                        <div class="table_total_div" id='timeconsume'><?php echo $uda->getTimeconsume()?><span class='buynow'><a style='display:block;' href='?cmd=Unlock&act=add_to_cart&ctr=<?php echo $_GET['ctr']; ?>&idp=<?php echo $_GET['idp']; ?>&idc=<?php echo $_GET['idc']; ?>&pos=<?php echo $uda->getPos(); ?>' title='Для выбора кликните на названии и лот будет добавлен в корзину' >купить</a></span></div>
                                    </div>
                            <?php endforeach;

             echo "</div><!-- End of column last dividerdownmidi -->
                    <div class=\"dividerdownbottom\" style='width: 700px; height: 40px; clear : both;'></div>
                </div><!-- End of column last -->
            </div><!-- End of row block grid2col row block border -->
        </div>";  // End of design

?>

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