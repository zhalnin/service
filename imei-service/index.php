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
$parent_catalog['name'] = "Главная";
$title = "Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID + сертификаты и провижен профиль";
$description = "Официальный анлок iPhone позволит вам обновлять ваш аппарат в iTunes. iPhone будет работать с любой сим картой, после официальной отвязки iPhone, он станет factory unlock";
require_once("templates/top.php");
try
{

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
    <div id="main" class="main-content">
        <div id="slogan">Быстро - Качественно - Надежно</div>
            <div id="showcase" class="content">

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
                    $alt = $news[$i]['alt'];
                    $photo_print = " src='{$news[$i]['urlpict']}' alt='$alt'";
                    $img = "<img class='hero-image flushleft logo' $photo_print />";

                } else {
                    $img = "";
                }
                if($news[$i]['url'] != '' && $news[$i]['url'] != '-')
                {
                    $href = "href='".$news[$i]['url']."'";
                    $val_href = $news[$i]['urltext'];
                }
                $detail = "";
                if(mb_strlen($news[$i]['body'],'UTF-8') > 600){
                    $id_news = $news[$i]['id_news'];
                    $url = "?id_news=".$news[$i]['id_news'];
                    $news[$i]['body'] = mb_substr($news[$i]['body'],0,600,'UTF-8')." ...";
                    $detail_button = "<div class='gs grid-1of4 gs-last r-align' style='' onclick=\"detail_button('$url');\">
                                        <div id='detail-button' class='button rect transactional' title='Подробнее' type='button' style=''>
                                            <span style=''>
                                                <span class='effect'></span>
                                                <span class='label'> Подробнее </span>
                                            </span>
                                        </div><br/><br/>
                                    </div>";
                } else {
                    $detail_button = "";
                }

                echo "<div id='design'>
                        <div class='row block grid2col row block border'>
                        $img
                            <div class='column last'>
                            ".$putdate."
                                <h1><a href='http://imei-service.ru'>".nl2br(print_page($news[$i]['name']))."</a></h1>
                                <p>".nl2br(print_page($news[$i]['body']))."</p>
                            </div>
                            $detail_button
                        </div>
                      </div>";
            }
        }
    }
    // Если GET-параметр id_news передан - выводим полную
    // версию нвостного сообщения
    else {
        require_once("news_print.php");

    echo "<div id='design'>
            <div class='row block grid2col row block border'>
                <div class='column last'>
                    <h1>
                        Для связи с нами можете использовать:
                    </h1>
                    <p>
                        ‣ Skype - <a href='skype:zhalnin78?add'>Добавить в друзья</a>
                        <br/><br/>
                        ‣ Email - imei_service@icloud.com<br/><br/>
                        <span class='nowrap'>Вопрос по теме</span>
                        без внимания
                        <span class='more'>не оставим!</span>
                    </p>
                </div>
            </div>
        </div>";
    }



echo "</div></div>";

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