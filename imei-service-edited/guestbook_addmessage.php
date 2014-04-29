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
//            if( empty( $_POST['message'] ) ) {
//                $valid = "";
//                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: Сообщение</li>";
//            } elseif( iconv_strlen( $_POST['message'] , 'utf-8') < 3 ) {
//                $valid = "";
//                $error .= "<li style='color: rgb(255, 0, 0);'>Поле: Сообщение не должно иметь менее 3-х символов</li>";
//            }

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

                $PDO = new PDO("mysql:host=localhost;dbname=imei-service", 'root', 'zhalnin5334', array(
                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
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




                                <div class="mbsIframe">
<!--                                    <span class="form-field field-with-placeholder">-->
<!--                                        <label class="placeholder" for="message"><span>Сообщение ( обязательно )</span></label>-->
<!--                                        <textarea class="textarea" cols="42" rows="5" name="message" class="textarea" id="message">--><?php //echo $message; ?><!--</textarea>-->
<!--                                    </span>-->
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
                                                    <a href="#" id="editorResize" class="editorResize" >
                                                        <span class="editorIcon editorResize"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </span>
                                </div>






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
