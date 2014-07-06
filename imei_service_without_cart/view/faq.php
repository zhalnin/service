<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 15:10
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request        = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию catalogCollection
    $catalog        = $request->getObject( 'catalogCollection' );
    // содержимое тега title
    $title          = $catalog->getName();
    // содержимое тега meta
    $keywords       = $catalog->getKeywords();
    // содержимое тега meta
    $description    = $catalog->getDescription();

//    echo "<tt><pre>".print_r( $catalog, true )."</pre></tt>";

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
                    <h2  class="h2">FAQ</h2>
                </div>
                <div class='news-container'>
                    <div class='faq-body'>

<?php
                        echo "<div class='faq-title'>
                                    <h1 class=h2>{$catalog->getName()}</h1>
                                </div>
                                <div class='faq-image'>
                                    <img alt='IMEI-service - Вопросы' src='imei_service/view/images/Apple_logo_black_shadow.png'/>
                                </div>

                                <div class='faq-info'>";
//                                проходим в цикле по коллекции
                                foreach ($catalog->getFaqPosition() as $pos ) {
                                    // если это статья
                                    if($pos->getUrl() != 'article') {
                                        echo "<p><a href=\"".$pos->getUrl()."\"
                                                            class=\"main_txt_lnk\">
                                                            ".htmlspecialchars( stripslashes( $pos->getName() ) )."</a></p>";
                                    } else { // если это ссылка
                                        echo "<p><a href=\"?cmd=Faq&idc={$pos->getIdCatalog()}&".
                                            "idp={$pos->getId()}\"
                                                            class=\"main_txt_lnk\">".
                                            htmlspecialchars($pos->getName())."</a></p>";
                                    }
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