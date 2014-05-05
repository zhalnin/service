<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 05/05/14
 * Time: 19:28
 */

error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы формы
require_once("../../config/class.config.dmn.php");

try {
    $_GET['id'] = intval( $_GET['id'] );
    if( empty( $_POST ) ) {
        $query = "SELECT * FROM system_guestbook
                    WHERE id=$_GET[id]
                    LIMIT 1";
        $guestbook = mysql_query( $query );
        if( ! $guestbook ) {
            throw new ExceptionMySQL( mysql_error(),
                                    $query,
                                    "Ошибка при обращении к гостевой книге" );
        }
        $_REQUEST = mysql_fetch_array( $guestbook );
        $_REQUEST['page'] = intval( $_GET['page'] );
        if( $_REQUEST['hide'] == 'show' ) $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;
    }

    $name                   = new FieldText("name",
                                      "Имя пользователя",
                                      true,
                                      $_REQUEST['name']);
    $city                   = new FieldText("city",
                                        "Город",
                                        false,
                                        $_REQUEST['city']);
    $email                  = new FieldText("email",
                                        "Email",
                                        true,
                                        $_REQUEST['email']);
    $url                    = new FieldText("url",
                                        "URL",
                                        false,
                                        $_REQUEST['url']);
    $message                = new FieldTextarea("message",
                                                "Сообщение",
                                                false,
                                                $_REQUEST['message'],
                                                '100',
                                                '20');
    $answer                 = new FieldTextarea("answer",
                                                "Ответ",
                                                false,
                                                $_REQUEST['answer'],
                                                '100',
                                                '20');
    $putdate                = new FieldDatetime("putdate",
                                            "Дата сообщения",
                                            $_REQUEST['putdate']);
    $hide                   = new FieldCheckbox("hide",
                                               "Отображать",
                                               $_REQUEST['hide']);
    $id_parent              = new FieldHiddenInt("id_parent",
                                                "",
                                                $_REQUEST['id_parent']);
    $ip                     = new FieldHidden("ip",
                                             "",
                                             $_REQUEST['ip']);
    $browser                = new FieldHidden("browser",
                                             "",
                                             $_REQUEST['browser']);
    $id                     = new FieldHiddenInt("id",
                                                "",
                                                $_REQUEST['id']);



    $form = new Form( array(
        "name"      => $name,
        "city"      => $city,
        "email"     => $email,
        "url"       => $url,
        "message"   => $message,
        "answer"    => $answer,
        "putdate"   => $putdate,
        "hide"      => $hide,
        "id_parent" => $id_parent,
        "ip"        => $ip,
        "browser"   => $browser,
        "id"        => $id),
        "Редактировать",
        "field");

    if( ! empty( $_POST ) ) {
        $error = $form->check();
        if( empty( $error ) ) {
            // Проверяем, скрыта или открыта директория
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";

            // Формируем SQL-запрос на добавление новости
            $query = "UPDATE system_guestbook
                        SET name = '{$form->fields['name']->value}',
                            city = '{$form->fields['city']->value}',
                            email = '{$form->fields['email']->value}',
                            url = '{$form->fields['url']->value}',
                            message = '{$form->fields['message']->value}',
                            answer  = '{$form->fields['answer']->value}',
                            putdate ='{$form->fields['putdate']->get_mysql_format()}',
                            hide = '{$showhide}',
                            id_parent = '{$form->fields['id_parent']->value}',
                            ip = '{$form->fields['ip']->value}',
                            browser = '{$form->fields['browser']->value}'
                        WHERE id=".$form->fields['id']->value;
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при редактировании
                     новостного сообщения");
            }
            // Осуществляем переадресацию на главную страницу
            // администрирования
            header("Location: index.php?page={$form->fields[page]->value}");
            exit();
        }
    }

    // Начало страницы
    $title = 'Редактирование поста гостевой книги';
    $pageinfo = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    echo "<p><a href=# onclick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style=\"color: red\">$err</span><br>";
        }
    }
    ?>
    <p class=help>
        ITALIC: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[i]','[/i]'); return false;">[i][/i]</a><br/>
        BOLD: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[b]','[/b]'); return false;">[b][/b]</a><br/>
        UNDERLINE: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[ins]','[/ins]'); return false;">[ins][/ins]</a><br/>
        URL: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[url]','[/url]'); return false;">[url][/url]</a><br/>
        IMG: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[img]','[/img]'); return false;">[img][/img]</a><br/>
        COLOR: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[color]','[/color]'); return false;">[color][/color]</a><br/>
        SIZE: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[size]','[/size]'); return false;">[size][/size]</a><br/>
        MAIL: <a href=# onclick="javascript:AM.DOM.tagInsert(AM.DOM.tag('textarea')[0],'[mail]','[/mail]'); return false;">[mail][/mail]</a><br/>
    </p>
    <?php
    $form->print_form();

}
catch(ExceptionObject $exc)
{
    require("../utils/exception_object.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
// Включаем завершение страницы
require_once("../utils/bottom.php");