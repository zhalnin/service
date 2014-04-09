<?php

/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 04.04.12
 * Time: 23:42
 * To change this template use File | Settings | File Templates.
 */

ob_start();
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы формы
require_once("../../config/class.config.dmn.php");
// Подключаем функцию изменения размера изображения
require_once("../utils/utils.resizeimg.php");

if(empty($_POST))
{
    // Отмечаем флажок hide
    $_REQUEST['hide'] = true;
}
try
{
    $name           = new FieldText("name",
                                    "Название",
                                    true,
                                    $_POST['name']);

    $body           = new FieldTextarea("body",
                                        "Содержимое",
                                        true,
                                        $_POST['body']);
    $date           = new FieldDatetime("date",
                                        "Дата новости",
                                        $_POST['date']);
    $hidedate       = new FieldCheckbox("hidedate",
                                        "Отображать дату",
                                        $_POST['hidedate']);
    $urltext        = new FieldText("urltext",
                                    "Текст ссылки",
                                    false,
                                    $_POST['urltext']);
    $url            = new FieldText("url",
                                    "Ссылка",
                                    false,
                                    $_POST['url']);
    $alt            = new FieldText("alt",
                                    "ALT-тег",
                                    false,
                                    $_POST['alt']);
    $filename       = new FieldFile("filename",
                                    "Изображение",
                                    false,
                                    $_FILES,
                                    "../../files/news/",
                                    "news_");
    $hide           = new FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);

    $hidepict       = new FieldCheckbox("hidepict",
                                    "Отображать фото",
                                    $_REQUEST['hidepict']);
    $page           = new FieldHiddenInt("page",
                                            false,
                                            $_REQUEST['page']);
    $form           = new Form(array("name"     => $name,
                                    "body"      => $body,
                                    "date"      => $date,
                                    "hidedate"  => $hidedate,
                                    "urltext"   => $urltext,
                                    "url"       => $url,
                                    "alt"       => $alt,
                                    "filename"  => $filename,
                                    "hide"      => $hide,
                                    "hidepict"  => $hidepict,
                                    "page"      => $page),
                                "Добавить",
                                "field");

//    echo $form->fields[date]->get_mysql_format();
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
//      echo $_POST['date']['year'];
//      echo "<tt><pre>". print_r($form, TRUE) . "</pre></tt>";
        // echo "page=".$_REQUEST['page'];
        // Проверяем корректность заполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Извлекаем текущую максимальную позицию
            $query = "SELECT MAX(pos) FROM $tbl_news";
            $pos = mysql_query($query);
            if(!$pos)
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при извлечении
                    текущей позиции");
            }
            $position = mysql_result($pos, 0) + 1;

            // Отображать дату или нет
            if($form->fields['hidedate']->value) $hidedate = "show";
            else $hidedate = "hide";

            // Скрытая или открытая фотография
            if($form->fields['hidepict']->value) $showhidepict = "show";
            else $showhidepict = "hide";

            // Выясняем, скрыта или открыта дректория
            if($form->fields['hide']->value) {
                $showhide = "show";
            } else {
                $showhide = "hide";
            }
            // Изображение
            $str = $form->fields['filename']->get_filename();
            if(!empty($str))
            {
                $url_pict = "files/news/$str";
                $url_pict_s = "files/news/s_$str";
            }
            else $url_pict = "";
			
			$query = "SELECT * FROM $tbl_photo_settings LIMIT 1";
            $set = mysql_query($query);
            if(!$set)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при извлечении
                                        параметров галереи");
            }
            if(mysql_num_rows($set))
            {
                $settings = mysql_fetch_array($set);
            }
			resizeimg("files/news/$str","files/news/s_$str" , $settings['width_news'], $settings['height_news']);
			
            // Формируем SQL-запрос на добавление
            // новостного сообщения
            $query = "INSERT INTO $tbl_news
                        VALUES (NULL,
                                '{$form->fields[name]->value}',
                                '{$form->fields[body]->value}',
                                '{$form->fields[date]->get_mysql_format()}',
                                '$hidedate',
                                '{$form->fields[urltext]->value}',
                                '{$form->fields[url]->value}',
                                '{$form->fields[alt]->value}',
                                '$url_pict',
                                '$ulr_pict_s',
                                $position,
                                '$showhide',
                                '$showhidepict')";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL($mysql_error(),
                                        $query,
                                        "Ошибка добавления новостного
                                        сообщения");
            }
            // Осуществляем пренаправление
            // на главную страницу администрирования
            header("Location: index.php?page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = "Добавление новостного сообщения";
    $pageinfo = '<p class="help"></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href='#' onclick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style=\"color:red\">$err</span><br>";
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
    // Выводим HTML-форму
    $form->print_form();
}
catch(ExceptionObject $exc)
{
//    require("../utils/exception_object.php");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}

// Включаем завершение страницы
require_once("../utils/bottom.php");
ob_get_flush();
?>