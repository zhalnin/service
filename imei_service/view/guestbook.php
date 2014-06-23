<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 20:24
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {

    require_once( "imei_service/classes/class.PagerMysql.php" );
    require_once( "imei_service/view/ViewHelper.php" );
    require_once( "imei_service/view/utils/utils.printChildPost.php" );

    $title = "Гостевая книга";
    $keywords = "В гостевой книге сайта imei-service.ru вы можете оставить свой комментарий о работе сервиса или задать интересующий вопрос относительно анлока iPhone, проверки по IMEI, blacklist или регистрации UDID в аккаунте разработчика.";
    $description = "Гостевая книга";

    $request = \imei_service\view\VH::getRequest();
    $guestbookMain = $request->getObject('guestbook_pagination');
    $guestbook = $guestbookMain['select'];
    $guestbookNavigation = $guestbookMain['navigation'];
    $page = $request->getProperty('page');
    require_once( "imei_service/view/templates/top.php" );

    if( empty( $page ) ) {
        $page = 1;
    }

?>

<div id="header">
    <ul id="navigation" role="navigation">
        <li id="nav-home"><a  class="selected" href="?cmd=News"><span>Главная</span></a></li>
        <li id="nav-unlock"><a href="?cmd=Unlock"><span>Официальный Анлок iPhone</span></a></li>
        <li id="nav-udid"><a href="?cmd=Udid"><span>Регистрация UDID</span></a></li>
        <li id="nav-carrier"><a href="?cmd=CarrierCheck"><span>Проверка оператора по IMEI</span></a></li>
        <li id="nav-fast_check"><a href="?cmd=FastCheck"><span>Быстрая проверка</span></a></li>
        <li id="nav-blacklist"><a href="?cmd=BlacklistCheck"><span>Blacklist</span></a></li>
        <li id="nav-faq"><a href="?cmd=Faq"><span>Вопросы</span></a></li>
    </ul>
</div>
<div id="main" class="">

    <?php
    require_once( "utils/security_mod.php" );
    ?>

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

                if( ! empty( $guestbook ) ) {
                    // Выводим постраничную навигацию
                    echo "<div class='page-navigator'>" . $guestbookNavigation . "</div>";
                    // В цикле получаем результат запроса и выводим его на страницу
                    foreach ($guestbook as $record) {


                    ?>
                    <div class='guestbook-all-body'>
                        <div class='guestbook-all-wrap main-content'>
                            <div class='guestbook-all-title'>
                                <!--                            <h1 class="h2">-->
                                <!--                                <a href="http://imei-service.ru">Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID</a>-->
                                <!--                            </h1>-->
                                <p class="ptdg"><b><?php echo $record->getName(); ?></b>&nbsp;
                                    <?php  $citySelect = $record->getCity(); if( ! empty( $citySelect ) ) print "($citySelect)"; ?>&nbsp;
                                    <?php echo $record->getPutdate(); ?></p>
                            </div>

                            <div class='guestbook-all-image'>
                                <img src="imei_service/view/images/guestbook/avatar_64x64.png" border="0" width="64" height="64" alt="<? echo $record->getName(); ?>" >
                            </div>

                            <div class='guestbook-all-info'>
                                <p class='ptext'><?php echo html_entity_decode( $record->getMessage() ); ?></p>
                                <?php $answerSelect = $record->getAnswer(); if( ! empty( $answerSelect ) && $answerSelect != '-' ) {
                                    echo "<div class='panswer-wrap main-content-blue'>
                                                <p class='panswer ptdg'><b><i>Администратор</i></b></p>
                                                <div class='panswer-image'>
                                                    <img src=\"imei_service/view/images/guestbook/avatar_blue_64x64.png\" border=\"0\" width=\"64\" height=\"64\" alt=".$record->getName()." >
                                                </div>
                                                <p class=\"panswer\">".nl2br($answerSelect)."</p>
                                              </div>";
                                }
                                ?>
                            </div>
                            <div class="guestbook-all-reply" id="<?php print $record->getId(); ?>" ><span><a href="?page=<?php echo $page; ?>&idp=<?php print $record->getId(); ?>" >Ответить</a></span></div>
                            <!--                    Запускаем рекурсивную функцию, чтобы проверить у родителя дочерних постов (idp - id_parent),-->
                            <!--                    если находим их, то выводим чуть ниже родительского поста,
                                                    , в функции проходим рекурсивно по всем постам, если они имеют idp - id_parent
                                                    находится в utils/utils.print_child_post.php -->
                            <?php  \imei_service\view\utils\selectRecursion($record->getId(), $page ); ?>



                        </div><!-- End of guestboor-all-wrap -->
                        <?php
                        echo "</div>"; //  End of guestbook-all-body


                        }

                        echo "<div class='page-navigator'>" . $guestbookNavigation . "</div>";
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

$valid = $request->getProperty('valid');
//$valid = $_POST['valid'];
if( ! empty( $valid ) ) {
    $sid_add_message = $request->getProperty('sid_add_message');
    $feedback = $request->getFeedback();
//echo "<tt><pre> VALID ".print_r($request, true)."</pre></tt>";
}
//    echo "<tt><pre> NO VALID ".print_r($request, true)."</pre></tt>";
?>


<!--            Возвращаем текст в iFrame-->
    <script type="text/javascript">
        AM.Event.addEvent( window, 'load', function() {
//            alert('load');

            if( AM.DOM.$('textareaIframe') != null ) {
                var textareaIframe = AM.DOM.$('textareaIframe').value;
//                console.log(textareaIframe);
                //                   textareaIframe = textareaIframe.replace(/&nbsp;/,' ');
               setTimeout( function() {
                    wysiwyg.doc().body.innerHTML = textareaIframe;

               }, 500 );
            }
        });
    </script>

            <div id="guestbook-form" class="guestbook-all-addmessage main-content">
            <div id="shipping-box" class="guest-form-box">
                <h2 class="h2 primary">Добавить сообщение</h2>
                <div class="guest-all-form top-divided">
                    <!--                    <form method="POST" action="guestbook.php">-->
                    <!--                    <form method="POST" name="guestbook-form" action="faq2.php?idp=70">-->
                    <form method="POST" >
                        <fieldset>

<?php
                    if( intval( $enter !== 1 ) ) {
?>
                            <legend><strong class="label">Заполните все обязательные поля</strong></legend>
                            <div class="fieldset-content">
                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="name"><span>Имя ( обязательно )</span></label>
                                        <input type="text" name="name" id="name" maxlength="25" class="name" value="<?php echo $_POST['name']; ?>" />
                                    </span>
                                </div>
                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="city"><span>Город</span></label>
                                        <input type="text" name="city" id="city" maxlength="25" value="<?php echo $_POST['city']; ?>" />
                                    </span>
                                </div>

                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="email"><span>E-mail ( обязательно )</span></label>
                                        <input type="text" name="email" id="email" class="email" value="<?php echo $_POST['email']; ?>" />
                                    </span>
                                </div>

                                <div class="mbs">
                                    <span class="form-field field-with-placeholder">
                                        <label class="placeholder" for="url"><span>URL</span></label>
                                        <input type="text" name="url" id="url"  value="<?php echo $_POST['url']; ?>" />
                                    </span>
                                </div>


<?php
}
?>

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





<?php
    if( intval( $enter !== 1 ) ) {
?>
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

<?php
    }
?>
                                <div id="chipping-continue-button-submit" class="mbs">
                                    <span>
                                        <label for="submit"><span>&nbsp;</span></label>
                                        <input type="submit" id="submitButton" value='Отправить' name="Отправить" />
                                    </span>
                                </div>

                                <input type="hidden" name="valid" value="valid" />
                                <input type="hidden" name="sid_add_message" value="<?php echo $sid_add_message; ?>" />
                                <input type="hidden" name="type" value="guestbook" id="type" />
                                <input type="hidden" name="idp" value="<?php echo $_REQUEST['idp']; ?>" id="guestbookReply" />
                                <input type="hidden" name="codeConfirm" value="" id="codeConfirm" />
                                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
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
            if( ! empty( $feedback ) ) {
                print "<div class='guestbook-error' style='color: rgb(255, 0, 0);'>";
                print "<ul>\n";
                print "<li>\n";
                print $request->getFeedbackString('</li><li>');
                print "</li>\n";
                print "</ul>\n";
                print "</div>";
            }
            echo "</div>";
//        }
?>

            <div id="main-guestbook"></div>

            <?php

            require_once( "imei_service/view/templates/bottom.php" );
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>