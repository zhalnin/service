<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 20:24
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;


require_once( "imei_service/classes/class.PagerMysql.php" );

try {

include( "utils/utils.getVerBrowser.php" );
include( "utils/utils.getIP.php" );
$title = "Гостевая книга";
$keywords = "В гостевой книге сайта imei-service.ru вы можете оставить свой комментарий о работе сервиса или задать интересующий вопрос относительно анлока iPhone, проверки по IMEI, blacklist или регистрации UDID в аккаунте разработчика.";
$description = "Гостевая книга";
require_once( "templates/top.php" );

?>

<div id="header">
    <ul id="navigation" role="navigation">
        <li id="nav-home"><a  class="selected" href="?cmd=News"><span>Главная</span></a></li>
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
        <a href="?cmd=Guestbook"><div id="nav-guestbook" class="addNav-body rounded main-content"><h3 class="h3">Гостевая</h3></div></a>
        <a href="?cmd=Contacts"><div id="nav-contact" class="addNav-body rounded main-content"><h3 class="h3">Контакты</h3></div></a>
    </div>


    <div id="news-main" class="main-content">
        <div id="" class="news-content clear-fix">
            <div id='' class="news-header">
                <h2  class="h2">Гостевая книга</h2>
            </div>
            <div class='news-container'>

                <?php


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
                $pagerMysql = new \imei_service\classes\PagerMysql('system_guestbook', " WHERE id_parent = 0 AND hide='show' ", " ORDER BY putdate DESC ", 10, 3, "");

                //                if( ! empty( $pagerMysql ) ) {
                if( 0 < count($pagerMysql->getPage() ) ) {
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
                            <img src="imei_service/view/images/guestbook/avatar_64x64.png" border="0" width="64" height="64" alt="<? echo $pm['name']; ?>" >
                        </div>

                        <div class='guestbook-all-info'>
                            <p class='ptext'><?php echo html_entity_decode( $pm['message'] ); ?></p>
                            <?php if( ! empty( $pm['answer'] ) && $pm['answer'] != '-' ) {
                                echo "<div class='panswer-wrap main-content-blue'>
                                            <p class='panswer ptdg'><b><i>Администратор</i></b></p>
                                            <div class='panswer-image'>
                                                <img src=\"imei_service/view/images/guestbook/avatar_blue_64x64.png\" border=\"0\" width=\"64\" height=\"64\" alt=".$pm['name']." >
                                            </div>
                                            <p class=\"panswer\">".nl2br($pm['answer'])."</p>
                                          </div>";
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
                    } else {
                    ?>
                    <div class='guestbook-all-body'>
                        <div class='guestbook-all-wrap main-content'>
                            <b><p class='guestbook-empty'>
                                    В настоящий момент в 'Гостевой книге' нет ни одного сообщения
                                </p></b>
                        </div><!-- End of guestboor-all-wrap -->
                        <?php
                        echo "</div>"; //  End of guestbook-all-body
                        }
                        ?>

                    </div><!-- End of news-container -->
                    <div class="news-footer"></div><!-- End of news-footer -->
                </div><!-- End of news-content -->
            </div><!-- End of news-main -->














<?php
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
    }
    if( empty( $_POST['email'] ) ) {
        $valid = "";
        $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: E-mail</li>";
    } elseif ( ! preg_match('|^[-a-z0-9_+.]+\@(?:[-a-z0-9.]+\.)+[a-z]{2,6}$|i', $_POST['email'] ) ) {
        $valid = "";
        $error .= "<li style='color: rgb(255, 0, 0);'>Введите ваш действительный E-mail</li>";
    }
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

    $name =  $_POST['name'];
    $city =  $_POST['city'];
    $email =  $_POST['email'];
    $url = $_POST['url'];
    //            $message = htmlspecialchars( stripslashes( $_POST['message'] ), ENT_QUOTES );
    $message = $_POST['message'];
    $time = new DateTime;
    $date = $time->format('Y-m-d H:i:s');
    $sendmail = true;
?>


<!--            Возвращаем текст в iFrame-->
    <script type="text/javascript">
        AM.Event.addEvent( window, 'load', function() {
            if( AM.DOM.$('textareaIframe') != null ) {
                var textareaIframe = AM.DOM.$('textareaIframe').value;
                //                   textareaIframe = textareaIframe.replace(/&nbsp;/,' ');
                wysiwyg.doc().body.innerHTML = textareaIframe;
            }
        });
    </script>
    <?php




    if( empty( $error ) ) {

        $PDO = new \PDO("mysql:host=localhost;dbname=imei-service", 'root', 'zhalnin5334', array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
        ) );

        $insertStmt = "INSERT INTO system_guestbook (name,
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
        $result = $sth->execute( array( $name, $city, $email, $url, $message, '-', $date, 'show', $id_parent, $ipAddress, $browser ) );
        if( $result ) {
            if( $sendmail === true ) {
                $to = 'zhalninpal@me.com';
                $subject = 'Новый пост в адресной книге';
                $body = "Поступило новое сообщение, которое следует проверить\n";
                $body .= "От пользователя: $name\n";
                $body .= "Адрес email: $email\n";
                $header = "From: zhalnin@mail.com\r\n";
                $header .= "Reply-to: zhalnin@mail.com \r\n";
                $header .= "Content-type: text/plane; charset=utf-8\r\n";
                mail($to,$subject,$body,$header);
                print "<html><head>\n";
                print "<meta http-equiv='Refresh' content='0; url=guestbook.php?page=$page'>\n";
                print "</head></html>\n";
                exit();
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
            <div id="shipping-box" class="guest-form-box">
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




                                <div class="mbsIframe">
                                    <span class="editorSpan" id="editorSpan">
                                        <table id="wysiwyg_toolbar" cellspacing="0" cellpadding="0" class="editorMain" >
                                            <tr class="editorFirst">
                                                <td class="editorToolbar">
                                                    <table>
                                                        <tr id="editorTR">
                                                            <td class=""><span></span></td>
                                                            <td><a href="#" id="bold" value="Жирный" class="editor_bold"
                                                                   title="Нажмите на иконку и напечатайте выделенный текст, повторное нажатие отключит режим выделенного текста. Так же вы можете сначала напечатать текст, выделить его и, однократно нажав на иконку, сделать его выделенным.">
                                                                    <span id="bold" class="editorIcon editor_bold"></span></a></td>
                                                            <td><a href="#" id="italic" value="Курсив" class="editor_italic"
                                                                   title="Нажмите на иконку и напечатайте наклонный текст, повторное нажатие отключит режим наклонного текста. Так же вы можете сначала напечатать текст, выделить его и, однократно нажав на иконку, сделать его наколонным.">
                                                                    <span id="italic" class="editorIcon editor_italic"></span></a></td>
                                                            <td><a href="#" id="underline" value="Подчеркнутый" class="editor_underline"
                                                                   title="Нажмите на иконку и напечатайте подчеркнутый текст, повторное нажатие отключит режим подчеркнутого текста. Так же вы можете сначала напечатать текст, выделить его и, однократно нажав на иконку, сделать его подчеркнутым.">
                                                                    <span id="underline" class="editorIcon editor_underline"></span></a></td>
                                                            <td><a href="#" id="strikethrough" value="Перечеркнутый" class="editor_strikethrough"
                                                                   title="Нажмите на иконку и напечатайте перечеркнутым текст, повторное нажатие отключит режим перечеркнутого текста. Так же вы можете сначала напечатать текст, выделить его и, однократно нажав на иконку, сделать его перечеркнутым.">
                                                                    <span id="strikethrough" class="editorIcon editor_strikethrough"></span></a></td>
                                                            <td><a href="#" class="editorSpacer" ><span class="editorIcon editorSpacer"></span></a></td>
                                                            <td><a href="#" id="justifyleft" value="Выровнять влево" class="editor_justifyleft" title="Выравнивание влево"><span id="justifyleft" class="editorIcon editor_justifyleft"></span></a></td>
                                                            <td><a href="#" id="justifycenter" value="Выровнять по центру" class="editor_justifycenter" title="Выравнивание по центру"><span id="justifycenter" class="editorIcon editor_justifycenter"></span></a></td>
                                                            <td><a href="#" id="justifyright" value="Выровнять вправо" class="editor_justifyright" title="Выравнивание вправо"><span id="justifyright" class="editorIcon editor_justifyright"></span></a></td>
                                                            <td><a href="#" class="editorSpacer" ><span class="editorIcon editorSpacer"></span></a></td>
                                                            <td><a href="#" id="image" value="Прикрепить изображение" class="editor_image" title="Вставить ссылку на изображение"><span id="image" class="editorIcon editor_image" ></span></a></td>
                                                            <td><a href="#" id="uploadImage" value="Загрузить изображение" class="editor_uploadImage" title="Загрузить изображение с компьютера"><span id="uploadImage" class="editorIcon editor_uploadImage"></span></a></td>
                                                            <td><a href="#" id="url" value="Ссылка" class="editor_link"
                                                                   title="Напечатайте описание ссылки, выделите его, и нажмите на иконку. Введите адрес ссылки, нажмите кнопку 'Вставить'" >
                                                                    <span id="link" class="editorIcon editor_link" ></span></a></td>
                                                            <td><a href="#" id="emoticon" value="Вставить смайлик" class="editor_emoticon" title="Вставить смайлик"><span id="emoticon" class="editorIcon editor_emoticon"></span></a></td>
                                                            <td><a href="#" class="editorSpacer" ><span class="editorIcon editorSpacer"></span></a></td>
                                                            <td style="padding-right: 2px;" class=""><span></span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="editorIFrame" id="editorIFrame">
                                                <td class="" id="iframe_td">
                                                    <iframe id="iframe_redactor" name="iframe_redactor" class="iframe_redactor" ></iframe>
                                                </td>
                                            </tr>
                                            <tr class="editorLast">
                                                <td class="editorStatusbar">
                                                    <div type="submit" onclick="previewPost();" class="button smallButton" value="Вставить" id="submit">
                                                    <span style="">
                                                        <span class="effect"></span>
                                                        <span class="label"> Предпросмотр </span>
                                                    </span>
                                                    </div>
                                                    <div id="editorResize" class="editorResize" >
                                                        <span class="editorIcon editorResize"></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </span>
                                </div>






                                <div class="mbs">
                                    <span  class="capcha">
                                        <label for="capcha"><span>&nbsp;</span></label>
                                        <img id="capchaImg" src="imei_service/view/utils/capcha/capcha.php" name="capcha" />
                                    </span>
                                </div>

                                <div class="refreshDiv">
                                    <div id="refreshCode" class="refreshCode" title="Обновить код на картинке"></div>
                                </div>

                                <div class="mbs capchaField">
                                    <span class="form-field field-with-placeholder code">
                                        <label class="placeholder" for="code"><span>Введите код с картинки</span></label>
                                        <input type="text" name="code" class="code" id="code" maxlength="6" />
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
                                <input type="hidden" name="codeConfirm" value="" id="codeConfirm" />
                                <input type="hidden" name="page" value="<?php echo $page; ?>" />
                                <textarea name="message" id="textareaIframe" style="display:none;"><?php echo $_POST['message']; ?></textarea>


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
?>











            <div id="main-guestbook"></div>

            <?php

            require_once("templates/bottom.php");

            } catch( \Exception $ex ) {
                file_put_contents( dirname(__FILE__).'/error.txt', $ex->getMessage(), -1, FILE_APPEND );
                print $ex->getMessage();
            } catch( \PDOException $ex ) {
                file_put_contents( dirname(__FILE__).'/error_pdo.txt', $ex->getMessage(), -1, FILE_APPEND );
                print $ex->getMessage();
}

?>