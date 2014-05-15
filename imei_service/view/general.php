<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 19:45
 */

require_once( "imei_service/add/class.PagerMysql.php" );
require_once( "imei_service/view/ViewHelper.php" );
require_once( "imei_service/view/utils/utils.printPage.php" );

$request = \imei_service\view\VH::getRequest();


    $title = "GENERAL IMEI-SEVICE";
    $keywords = "udid,unlock,blacklist,carrier,iPhone,iPod,iPad,iTunes";
    $description = "Официальный анлок iPhone позволит вам обновлять ваш аппарат в iTunes. Регистрация UDID в аккаунте разработчика нужен для безопасной установки iOS 7.1 бета 3. iPhone.
    Проверка iPhone по IMEI/серийному номеру даст вам самую полную информацию о вашем iPhone.
    Проверка iPhone на blacklist даст вам информацию о статусе вашего аппарата (потерян/украден/задолженность по контракту)";
    require_once("templates/top.php");
    try
    {
    //    $url = "?id_news=1";

    //mail("zhalninpal@me.com","test","test");
    ?>
    <script type="text/javascript">
        /**
              * For detail-button
              * To view whole news
              * @param url
              */
        function detail_button(url) {
            location.href=url;
        }

    </script>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a  class="selected" href="index.php"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="unlock.php"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="udid.php"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="carrier_check.php"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="fast_check.php"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="blacklist_check.php"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a href="faq.php"><span>Вопросы</span></a></li>
        </ul>
    </div>
    <div id="main" class="">
        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>
        <!--        End of main-slogan-->

        <div id="addNav" class="">
            <a href="guestbook.php"><div id="nav-guestbook" class="addNav-body rounded main-content"><h3 class="h3">Гостевая</h3></div></a>
            <a href="contacts.php"><div id="nav-contact" class="addNav-body rounded main-content"><h3 class="h3">Контакты</h3></div></a>
        </div>


        <div id="news-main" class="main-content">
            <div id="" class="news-content clear-fix">
                <div id='' class="news-header">
                    <h2  class="h2">Новости</h2>
                </div>
                <div class='news-container'>

<?php
// Если GET-параметр id_news не передан - выводим
// список новостных сообщений
if(empty($_GET['id_news']))
{
    $_GET['page'] = intval($_GET['page']);

    // Количество сообщений на странице
    $pnumber = 100;
    // Количество ссылок в постраничной навигации
    $page_link = 3;

    $tbl_news = 'system_news';

    // Объявляем объект постраничной навигации
    $obj = new \imei_service\add\PagerMysql($tbl_news,
        "WHERE hide = 'show' ",
        "ORDER BY pos",
        $pnumber,
        $page_link);
    // Получаем содержимое текущей страницы
    $news = $obj->getPage();

    // Если имеется хотя бы одна запись - выводим ее
    if(!empty($news))
    {

        $urlpict = "";
        for($i = 0; $i < count($news); $i++)
        {
            if($news[$i]['hidedate'] != 'hide' ) {
                $editdate = date('d.m.Y H:i', strtotime($news[$i]['putdate']));
                $putdate =  "<span id=\"datetime\">".$editdate."</span>";
            } else {
                $putdate = "";
            }
            if($news[$i]['urlpict'] != '' && $news[$i]['hidepict'] != 'hide')
            {

                if( $i % 2 == 1 ) {
                    $align = "float: right;";
                    $textAlign = "text-align: right";
                    $reverseAlign = "float: left";
                    $reverseTextAlign = "text-align: left";
                } else if( $i % 2 == 0 ) {
                    $align = "float: left;";
                    $textAlign = "text-align: left";
                    $reverseAlign = "float: right";
                    $reverseTextAlign = "text-align: right";
                }
                $alt = $news[$i]['alt'];
                $photo_print = " src='imei_service/view/{$news[$i]['urlpict_s']}' alt='$alt'";
                $img = "<img class=''  $photo_print />";

            } else {
                $img = "";
            }
            if($news[$i]['url'] != '' && $news[$i]['url'] != '-')
            {
                $href = "href='".$news[$i]['url']."'";
                $val_href = $news[$i]['urltext'];
            }
            $detail = "";

            $url = "?id_news=".$news[$i]['id_news'];

            echo "<div class='news-string-body superlink main-content $borderleft $borderbottom' onclick=\"detail_button('$url')\">
                            <div class='news-title'>
                                <h1 class=\"h2\">".nl2br(\imei_service\view\utils\printPage($news[$i]['name']))."</h1>
                            </div>
                            <div class='news-image' style=\"$align\">
                              $img
                            </div>
                            <div class='news-info' style=\"$reverseAlign\" >
                                <p>".nl2br(\imei_service\view\utils\printPage($news[$i]['preview']))."</p>
                            </div>
                        </div>";
        }
    }
}
// Если GET-параметр id_news передан - выводим полную
// версию нвостного сообщения
else
{
//    require_once("newsPrint.php");

}



echo "
                    </div><!-- End of news-container -->
                 <div class=\"news-footer\"></div><!-- End of news-footer -->
            </div><!-- End of news-content -->
        </div><!-- End of news-main -->
        <div id=\"main-guestbook\"></div>";

require_once("templates/bottom.php");
}

catch(ExceptionMySQL $exc)
{
    require_once("exception_mysql_debug.php");
}
catch(ExceptionObject $exc)
{
    require_once("exception_object_debug.php");
}
catch(ExceptionMember $exc)
{
    require_once("exception_member_debug.php");
}


?>