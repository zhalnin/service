<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/11/13
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
// Устанавливаем соединение с базой данных
//require_once("config/config.php");
require_once("class/class.Database.php");
// Подключаем FrameWork
require_once("config/class.config.php");
// Подключаем функцию вывода текста с bbCode
require_once("dmn/utils/utils.print_page.php");
Database::getInstance();
$parent_catalog[name] = "Главная";
$title = "Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID + сертификаты и провижен профиль";
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

<!--                        <div class='news-body superlink' onclick="detail_button('?id_news=1')">-->
<!--                            <div class='news-title'>-->
<!--                                <h1>-->
<!--                                    <a href="http://imei-service.ru">Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID</a>-->
<!--                                </h1>-->
<!--                            </div>-->
<!--                            <div class='news-image'>-->
<!--                                <img class="" alt="Официальная отвязка от оператора" src="files/news/Apple_logo_black_shadow.png" >-->
<!--                            </div>-->
<!--                            <div class='news-info'>-->
<!--                                <p>-->
<!--                                    <b>Мы рады вас приветствовать на нашем сайте!</b>-->
<!--                                    <br>-->
<!--                                    <br>-->
<!--                                    Можем вам предложить:-->
<!--                                    <br>-->
<!--                                    <br>-->
<!--                                    ‣-->
<!--                                    <a class="" href="http://imei-service.ru/unlock.php">Официальный анлок iPhone от оператора</a>-->
<!--                                    <br>-->
<!--                                    ‣-->
<!--                                    <a class="" href="http://imei-service.ru/udid.php">Регистрация UDID в аккаунте разработчика</a>-->
<!--                                    <br>-->
<!--                                    ‣-->
<!--                                    <a class="" href="http://imei-service.ru/carrier_check.php">Проверка по IMEI iPhone</a>-->
<!--                                    <br>-->
<!--                                    ‣-->
<!--                                    <a class="" href="http://imei-service.ru/blacklist_check.php">Проверка iPhone на blacklist</a>-->
<!--                                    <br>-->
<!--                                    ‣ Услуги 100% легальные-->
<!--                                    <br>-->
<!--                                    <br>-->
<!--                                    <b>Специальное предложение для реселлеров</b>-->
<!--                                    - обращайтесь на:-->
<!--                                    <a href="mailto:imei_service@icloud.com">imei_service@icloud.com</a>-->
<!--                                    <br>-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->


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

    // Объявляем объект постраничной навигации
    $obj = new PagerMysql($tbl_news,
        "WHERE hide = 'show' ",
        "ORDER BY pos",
        $pnumber,
        $page_link);
    // Получаем содержимое текущей страницы
    $news = $obj->get_page();

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
                    $align = "text-align: right;";
                } else if( $i % 2 == 0 ) {
                    $align = "text-align: left;";
                }
                $alt = $news[$i]['alt'];
                $photo_print = " src='{$news[$i]['urlpict']}' alt='$alt' width='276' height='153'";
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

            // Если индекс при модульном делении равен 1, т.е.
            // 0 или 1 или  2 % 3 == 1
            // 0 % 3 = 0
            // 1 % 3 = 1
            // 2 % 3 = 2
            // 3 % 3 = 0
            // 4 % 3 = 1
            // 5 % 3 = 2
            // нам нужен только средний элемент(2), который равен при делении 1,
            // чтобы назначить ему border-left и border-right
//            if( $i % 3 == 1 ) {
//                $borderleft = 'border-left-right';
//
//            } else {
//                $borderleft = "";
//            }
//            // Высчитываем разницу между количеством блоков на три в ряд
//            // эту разницу вычитаем из общего количества и назначаем нижний border для тех,
//            // которые не самые нижние
//            $count = count( $news );
//            $diff = $count % 3;
//            // Если весь нижний ряд заполнен, то убираем у него стиль border-bottom
//            if ( $diff == 0 ) {
//                $diff = 3;
//            }
//            if( $i < $count-$diff ) {
//                $borderbottom = 'border-bottom';
//            } else {
//                $borderbottom = "";
//            }





            echo "<div class='news-string-body superlink $borderleft $borderbottom' onclick=\"detail_button('$url')\">
                            <div class='news-title'>
                                <h1 class=\"h2\">".nl2br(print_page($news[$i]['name']))."</h1>
                            </div>
                            <div class='news-image' style=\"$align\">
                              $img
                            </div>
                            <div class='news-info'>
                                <p>".nl2br(print_page($news[$i]['preview']))."</p>
                            </div>
                        </div>";





        }
    }
}
// Если GET-параметр id_news передан - выводим полную
// версию нвостного сообщения
else
{
    require_once("news_print.php");

//        echo "<div id='design'>
//                <div class='row block grid2col row block border'>
//                    <div class='column last'>
//                        <h1>
//                            Для связи с нами можете использовать:
//                        </h1>
//                        <p>
//                            ‣ Skype - <a href='skype:zhalnin78?add'>Добавить в друзья</a>
//                            <br/><br/>
//                            ‣ Email - imei_service@icloud.com<br/><br/>
//                            <span class='nowrap'>Вопрос по теме</span>
//                            без внимания
//                            <span class='more'>не оставим!</span>
//                        </p>
//                    </div>
//                </div>
//            </div>
//        </div>";
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

<!--$arr = range(1, 25);-->
<!---->
<!--$chunks = array_chunk($arr, 4);-->
<!--$rows = array_map(function ($idx) use ($chunks) {-->
<!--return array_column($chunks, $idx);-->
<!--}, range(0, 3));-->
<!---->
<!--echo implode("<br />", array_map(function ($row){-->
<!--return implode(', ', $row);-->
<!--}, $rows));-->
<!---->
<!--echo "\n\n";-->
<!---->
<!---->
<!--для php 5.3+-->
<!--$arr = range(1, 25);-->
<!---->
<!--$chunks = array_chunk($arr, 4);-->
<!--$rows = array_map(function ($idx) use ($chunks) {-->
<!--// fallback, так как array_column появился только в 5,5+-->
<!--return array_reduce($chunks, function ($column, $chunk) use ($idx) {-->
<!--if (!isset($chunk[$idx])) return $column;-->
<!--array_push($column, $chunk[$idx]);-->
<!--return $column;-->
<!--}, array());-->
<!--}, range(0, 3));-->
<!---->
<!--echo implode("<br />", array_map(function ($row){-->
<!--return implode(', ', $row);-->
<!--}, $rows));-->