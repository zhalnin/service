<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 29/03/14
 * Time: 11:40
 * To change this template use File | Settings | File Templates.
 */
//require_once( "config/class.config.php" );
require_once ( "guestbook/add/class.PagerMysql.php" );
require_once( "templates/top.php" );
include( "utils/utils.getVerBrowser.php" );
include( "utils/utils.getIP.php" );
?>

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

    <div id="news-main" class="main-content">
        <div id="" class="news-content clear-fix">
            <div id='' class="news-header">
                <h2  class="h2">Гостевая книга</h2>
            </div>
            <div class='news-container'>

 <?php
    try {

        $ipAddress = getIP();
        $browser = getVerBrowser();
        if( isset( $_GET['page'] ) ) {
            $page = htmlspecialchars( stripslashes( $_GET['page'] ), ENT_QUOTES );
        } else {
            $page = 1;
        }

        // Подключаем рекурсивную функцию selectRecursion( id_parent );
        include( "utils/utils.print_child_post.php" );


        // Находим все посты, которые не имеют id_parent (значит они родительские)
        $pagerMysql = new \guestbook\add\PagerMysql('guest', " WHERE id_parent = 0 AND hide='show' ", " ORDER BY putdate DESC ", 10, 3, "");
        // Выводим постраничную навигацию
        echo "<div class='page-navigator'>" .  $pagerMysql->printPageNav() ."</div>";
        // В цикле получаем результат запроса и выводим его на страницу
        foreach ($pagerMysql->getPage() as $key=>$pm ) {
?>
             <div class='guestbook-all-body'>
                <div class='guestbook-all-wrap main-content'>
                    <div class='guestbook-all-title'>
    <!--                            <h1 class="h2">-->
    <!--                                <a href="http://imei-service.ru">Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID</a>-->
    <!--                            </h1>-->
                        <p class="ptdg"><b><?php echo $pm['name']; ?></b>&nbsp;
                        <?php if( ! empty( $pm['city'] ) ) print "($pm[city])"; ?>&nbsp;
                        <?php echo $pm['putdate']; ?></p>
                    </div>

                    <div class='guestbook-all-image'>
                        <img src="files/guestbook/avatar_64x64.png" border="0" width="64" height="64" alt="<? echo $pm['name']; ?>" >
                    </div>

                    <div class='guestbook-all-info'>
                        <p class='ptext'><?php echo nl2br( $pm['message'] ); ?></p>
                        <?php if( ! empty( $pm['answer'] ) && $pm['answer'] != '-' ) {
                                    print "<p class='panswer'><b><i>Администратор:</i></b>&nbsp;".nl2br($pm['answer'])."</p>";
                                }
                        ?>
                    </div>
                    <div class="guestbook-all-reply"><span><a href="?page=<?php print $page; ?>&id_parent=<?php print $pm['id']; ?>" >Ответить</a></span></div>
<!--                    Запускаем рекурсивную функцию, чтобы проверить у родителя дочерних постов (id_parent),-->
<!--                    если находим их, то выводим чуть ниже родительского поста,
                        , в функции проходим рекурсивно по всем постам, если они имеют id_parent
                        находится в utils/utils.print_child_post.php -->
                    <?php  selectRecursion($pm['id'], $page ); ?>

                </div><!-- End of guestboor-all-wrap -->
<?php
        echo "</div>"; //  End of guestbook-all-body
                }
                echo "<div class='page-navigator'>" .  $pagerMysql->printPageNav() ."</div>";
?>

            </div><!-- End of news-container -->
        <div class="news-footer"></div><!-- End of news-footer -->
    </div><!-- End of news-content -->
</div><!-- End of news-main -->

    <?php include("guestbook_addmessage.php" ); ?>

<div id="main-guestbook"></div>

<?php

    require_once("templates/bottom.php");

    } catch( Exception $ex ) {
        file_put_contents( dirname(__FILE__).'/error.txt', $ex->getMessage(), -1, FILE_APPEND );
        print $ex->getMessage();
    } catch( PDOException $ex ) {
        file_put_contents( dirname(__FILE__).'/error_pdo.txt', $ex->getMessage(), -1, FILE_APPEND );
        print $ex->getMessage();

    } catch(ExceptionMySQL $exc) {
        require_once("exception_mysql_debug.php");
    } catch(ExceptionObject $exc) {
        require_once("exception_object_debug.php");
    } catch(ExceptionMember $exc) {
        require_once("exception_member_debug.php");
    }


?>



