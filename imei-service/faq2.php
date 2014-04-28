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
                        <div class="guestbook-all-reply"><span><a href="?page=<?php echo $page; ?>&id_parent=<?php print $pm['id']; ?>" >Ответить</a></span></div>
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

        <?php
        /**
         * Created by JetBrains PhpStorm.
         * User: zhalnin
         * Date: 29/03/14
         * Time: 13:22
         * To change this template use File | Settings | File Templates.
         */


        ?>


        <?php
        try {
        $valid = "";
        $error = "";
        $valid = $_POST['valid'];
        if( ! empty( $valid ) ) {
            if( $sid_add_message != $_POST['sid_add_message'] ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Попробуйте отправить форму заново</li>";
            }
            if( empty( $_POST['name'] ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: Имя</li>";
            } elseif( iconv_strlen( trim( ( $_POST['name']) ,'utf-8') ) < 2 ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Поле: Имя должно содержать не менее двух букв</li>";
            }
//            if( empty( $_POST['city'] ) ) {
//                $valid = "";
//                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: Город</li>";
//            }
            if( empty( $_POST['email'] ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: E-mail</li>";
            } elseif ( ! preg_match('|^[-a-z0-9_+.]+\@(?:[-a-z0-9.]+\.)+[a-z]{2,6}$|i', $_POST['email'] ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Введите ваш действительный E-mail</li>";
            }
//            if( empty( $_POST['url'] ) ) {
//                $valid = "";
//                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: URL</li>";
//            }
            if( empty( $_POST['message'] ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: Сообщение</li>";
            } elseif( iconv_strlen( $_POST['message'] , 'utf-8') < 3 ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Поле: Сообщение не должно иметь менее 3-х символов</li>";
            }

//            if( empty( $_POST['code'] ) ) {
//                $valid = "";
//                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо указать код с картинки</li>";
//            }
            if( $_SESSION['code'] != $_POST['code'] ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Указанный код с картинки неверный</li>";
            }
            if( isset( $_POST['id_parent_post'] ) ) {
                $id_parent = htmlspecialchars( stripslashes( $_POST['id_parent_post'] ), ENT_QUOTES );
            }
            if( isset( $_GET['id_parent'] ) ) {
                $id_parent = htmlspecialchars( stripslashes( $_GET['id_parent'] ), ENT_QUOTES );
            }
            if( ! isset( $id_parent ) ) {
                $id_parent = 0;
            }
            if( isset( $_GET['page'] ) ) {
                $page = htmlspecialchars( stripslashes( $_GET['page'] ), ENT_QUOTES );
            }
            if( isset( $_POST['page'] ) ) {
                $page = htmlspecialchars( stripslashes( $_POST['page'] ), ENT_QUOTES );
            }
            if( !isset( $page ) ) {
                $page = 1;
            }

            $name = htmlspecialchars( stripslashes( $_POST['name'] ), ENT_QUOTES );
            $city = htmlspecialchars( stripslashes( $_POST['city'] ), ENT_QUOTES );
            $email = htmlspecialchars( stripslashes( $_POST['email'] ), ENT_QUOTES );
            $url = htmlspecialchars( stripslashes( $_POST['url'] ), ENT_QUOTES );
            $message = htmlspecialchars( stripslashes( $_POST['message'] ), ENT_QUOTES );
            $time = new DateTime;
            $putdate = $time->format('Y-m-d H:i:s');
            $sendmail = false;



            if( empty( $error ) ) {

                $PDO = new PDO("mysql:host=localhost;dbname=talking", 'root', 'zhalnin5334', array(
                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
                ) );

                $insertStmt = "INSERT INTO guest (name,
                                                    city,
                                                    email,
                                                    url,
                                                    message,
                                                    answer,
                                                    putdate,
                                                    hide,
                                                    id_parent,
                                                    ip,
                                                    browser)
                                       VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
                $sth = $PDO->prepare( $insertStmt );
                $result = $sth->execute( array( $name, $city, $email, $url, $message, '-', $putdate, 'show', $id_parent, $ipAddress, $browser ) );
                if( $result ) {
                    if( $sendmail === true ) {
                        $to = 'zhalninpal@me.com';
                        $subject = 'Новый пост в адресной книге';
                        $body = "Поступило новое сообщение: $message\n";
                        $body .= "От пользователя: $name\n";
                        $body .= "Адрес email: $email\n";
                        $header = "From: zhalnin@mail.com\r\n";
                        $header .= "Reply-to: zhalnin@mail.com \r\n";
                        $header .= "Content-type: text/plane; charset=utf-8\r\n";
//                        mail($to,$subject,$body,$header);
                    } else {
                        print "<html><head>\n";
                        print "<meta http-equiv='Refresh' content='0; url=guestbook.php?page=$page'>\n";
                        print "</head></html>\n";
                        exit();
                    }
                }
            }
        }

        if( empty( $valid ) || ! empty( $error ) ) {
        if( isset( $_GET['id_parent'] ) ) {
            if( isset( $_GET['page'] ) ) {
                $page = "&page=".htmlspecialchars( stripslashes( $_GET['page'] ), ENT_QUOTES );
            } else {
                $page = "&page=1";
            }
            $id_parent = "?id_parent=".htmlspecialchars( stripslashes( $_GET['id_parent'] ), ENT_QUOTES ).$page;
        } else {
            $id_parent = "";
        }


        ?>
        <div id="guestbook-form" class="guestbook-all-addmessage main-content">
            <div class="guest-form-box">
                <h2 class="h2 primary">Добавить сообщение</h2>
                <div class="guest-all-form top-divided">
                    <!--                    <form method="POST" action="guestbook.php">-->
                    <!--                    <form method="POST" name="guestbook-form" action="faq2.php?id_parent=70">-->
                    <form method="POST" action="guestbook.php<?php echo $id_parent; ?>">
                        <fieldset>

                            <legend><strong class="label">Заполните все обязательные поля</strong></legend>
                            <div class="fieldset-content">
                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="name"><span>Имя ( обязательно )</span></label>
                                        <input type="text" name="name" id="name" maxlength="25" class="name" value="<?php echo $name; ?>" />
                                    </span>
                                </div>
                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="city"><span>Город</span></label>
                                        <input type="text" name="city" id="city" maxlength="25" value="<?php echo $city; ?>" />
                                    </span>
                                </div>

                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="email"><span>E-mail ( обязательно )</span></label>
                                        <input type="text" name="email" id="email" class="email" value="<?php echo $email; ?>" />
                                    </span>
                                </div>

                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="url"><span>URL</span></label>
                                        <input type="text" name="url" id="url"  value="<?php echo $url; ?>" />
                                    </span>
                                </div>




                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="message"><span>Сообщение ( обязательно )</span></label>
                                        <textarea class="textarea" cols="42" rows="5" name="message" class="textarea" id="message"><?php echo $message; ?></textarea>
                                    </span>
                                </div>








                                <span class="defaultSkin">
                                    <table cellspacing="0" cellpadding="0" style="height: 312px;" class="mceLayout" >
                                        <tr class="mceFirst">
                                            <td style="height: 28px; bottom: 0;" class="mceToolbar mceLeft mceFirst mceLast">
                                                <table cellspacing="0" cellpadding="0" class="mceToolbar">
                                                    <tr>
                                                        <td class="mceToolbarStart mceToolbarStartButton mceFirst"> </td>
                                                        <td><a href="#" id="bold" value="Жирный" class="mce_bold"><span class="mceIcon mce_bold">b</span></a></td>
                                                        <td><a href="#" id="italic" value="Курсив" class="mce_italic"><span class="mceIcon mce_italic">i</span></a></td>
                                                        <td><a href="#" id="underline" value="Подчеркнутый" class="mce_underline"><span class="mceIcon mce_underline">u</span></a></td>
                                                        <td><a href="#" id="strikethrough" value="Перечеркнутый" class="mce_strikethrough"><span class="mceIcon mce_strikethrough">s</span></a></td>
                                                        <td><a href="#" id="justifyleft" value="Выровнять влево" class="mce_justifyright"><span class="mceIcon mce_justifyright">l</span></a></td>
                                                        <td><a href="#" id="justifycenter" value="Выровнять по центру" class="mce_justifycenter"><span class="mceIcon mce_justifycenter">c</span></a></td>
                                                        <td><a href="#" id="justifyright" value="Выровнять вправо" class="mce_justifyright"><span class="mceIcon mce_justifyright">r</span></a></td>
                                                        <td><a href="#" id="image" value="Картинка" class="mce_image"><span class="mceIcon mce_image">im</span></a></td>
                                                        <td><a href="#" id="url" value="Ссылка" class="mce_link"><span class="mceIcon mce_link">ur</span></a></td>
                                                        <td style="padding-right: 2px;" class="mceToolbarEnd mceToolbarEndButton mceLast"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="mceIframe">
                                            <td class="mceIframeContainer mceFirst mceLast">
                                                <div style="position: absolute; overflow: hidden; top: 0px; left: 0px; width: 100%; height: 269px;"></div>
                                                <iframe id="iframe_redactor" style="width: 100%; height: 264px; position: relative;" ></iframe>
                                            </td>
                                        </tr>
                                        <tr class=" mceLast">
                                            <td style="border-bottom: 1px solid #CCCCCC;" class="mceStatusbar mceLast mceFirst">
                                                <a class="resize" href="#"></a>
                                            </td>
                                        </tr>
                                    </table>
                                </span>









                                <div class="mbs">
                                    <span  class="capcha">
                                        <label for="capcha"><span>&nbsp;</span></label>
                                        <img src="guestbook/capcha/capcha.php" name="capcha" >
                                    </span>
                                </div>

                                <div class="mbs">
                                    <span class="form-field field-with-placeholder code">
                                        <label class="placeholder" for="code"><span>Введите код с картинки</span></label>
                                        <input type="text" name="code" class="required" id="code">
                                    </span>
                                </div>

                                <div id="chipping-continue-button-submit" class="mbs">
                                    <span>
                                        <label for="submit"><span>&nbsp;</span></label>
                                        <input type="submit" id="submitButton" value='Отправить' name="Отправить" />
                                    </span>
                                </div>

                                <input type="hidden" name="valid" value="valid" />
                                <input type="hidden" name="sid_add_message" value="<?php echo $sid_add_message ?>" />
                                <input type="hidden" name="client_ip" value="<?php echo $ipAddress; ?>" />
                                <input type="hidden" name="client_browser" value="<?php echo $browser; ?>" />
                                <input type="hidden" name="type" value="guestbook" id="type" />
                                <input type="hidden" name="id_parent_post" value="" id="guestbookReply" />
                                <input type="hidden" name="page" value="<?php echo $page; ?>" />



                                <div id="submit-button" class="" style="">
                                    <div class="">
                                        <div id="shipping-step-defaults" style="">
                                            <div id="shipping-continue-button" class="button rect transactional" title="Отправить" value="click" type="submit" >
                                                    <span style="">
                                                        <span class="effect"></span>
                                                        <span class="label"> Отправить </span>
                                                    </span>
                                            </div><!-- shipping-continue-button -->
                                        </div><!-- shipping-step-defaults -->
                                    </div><!-- chat chat-now cchat -->
                                </div><!-- gs grid-3of4 -->


                                <div id="cancel-button" style="">
                                    <div id="shipping-button" class="button rect transactional blues" title="Отмена" type="button">
                                        <span style="">
                                            <span class="effect"></span>
                                            <span class="label"> Отмена </span>
                                        </span>
                                    </div><!-- shipping-button -->
                                </div><!-- gs grid-1of4 gs-last r-align -->


                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>




            <?php
            if( ! empty( $error ) ) {
                print "<div class='guestbook-error' style='color: rgb(255, 0, 0);'>";
                print "<ul>\n";
                print $error;
                print "</ul>\n";
                print "</div>";
            }
            echo "</div>";
            }
            } catch (Exception $ex) {
                file_put_contents( dirname(__FILE__).'/error.txt', $ex->getMessage()."\r\n", FILE_APPEND );
                print $ex->getMessage();
            } catch ( PDOException $ex ) {
                file_put_contents( dirname(__FILE__).'/error_pdo.txt', $ex->getMessage()."\r\n", FILE_APPEND );
                print $ex->getMessage();
            }
            ?>


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



