<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 06.04.12
 * Time: 22:41
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


// Предотвращаем SQL-инъекцию
$_GET['id_news'] = intval($_GET['id_news']);

try
{
    // Извлекаем из таблицы news запись, соответствующую
    // исправляемому новостному сообщению
    $query = "SELECT * FROM $tbl_news
                WHERE id_news=$_GET[id_news]";
    $new = mysql_query($query);

    if(!$new)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении
                                к таблице новостей");
    }
    $news = mysql_fetch_array($new);
    if(empty($_POST))
    {
        // Берем информацию для оставшихся переменных из базы данных
        $_REQUEST = $news;
        $_REQUEST['date']['month']      = substr($news['putdate'],5,2);
        $_REQUEST['date']['day']        = substr($news['putdate'],8,2);
        $_REQUEST['date']['year']       = substr($news['putdate'],0,4);
        $_REQUEST['date']['hour']       = substr($news['putdate'],11,2);
        $_REQUEST['date']['minute']     = substr($news['putdate'],14,2);
        // Определяем, скрыто поле или нет
        if($news['hidedate'] == 'show' ) $_REQUEST['hidedate'] = true;
        else $_REQUEST['hidedate'] = false;
        if($news['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;
        if( $news['hidepict'] == 'show' ) $_REQUEST['hidepict'] = true;
        else $_REQUEST['hidepict'] = false;

    }

    $name       = new FieldText("name",
                                "Название",
                                true,
                                $_REQUEST['name']);
    $body       = new FieldTextarea("body",
                                    "Содержимое",
                                    true,
                                    $_REQUEST['body']);
    $date       = new FieldDatetime("date",
                                    "Дата новости",
                                    $_REQUEST['date']);
    $hidedate       = new FieldCheckbox("hidedate",
                                    "Отображать дату",
                                    $_REQUEST['hidedate']);
    $urltext    = new FieldText("urltext",
                                "Текст ссылки",
                                false,
                                $_REQUEST['urltext']);
    $url        = new FieldText("url",
                                "Ссылка",
                                false,
                                $_REQUEST['url']);
    $alt        = new FieldText("alt",
                                "ALT-тег",
                                false,
                                $_REQUEST['alt']);
    $filename   = new FieldFile("filename",
                                "Изображение",
                                false,
                                $_FILES,
                                "../../files/news/",
                                "news_");
    $hide       = new FieldCheckbox("hide",
                                    "Отображать",
                                    $_REQUEST['hide']);
    $hidepict   = new FieldCheckbox("hidepict",
                                    "Отображать фото",
                                    $_REQUEST['hidepict']);
    $id_news    = new FieldHiddenInt("id_news",
                                        "",
                                        $_REQUEST['id_news']);
    $page       = new FieldHiddenInt("page",
                                        "",
                                        $_REQUEST['page']);
    // Инициируем форму массивом из двух элементов
    // управления - поля ввода name и текстовой области
    // textarea


        // Удаление изображения
        $delimg     = new FieldCheckbox("delimg",
                                        "Удалить изображение",
                                        $_REQUEST['delimg']);
        $form       = new Form(array("name"     => $name,
                                    "body"      => $body,
                                    "date"      => $date,
                                    "hidedate" => $hidedate,
                                    "urltext"   => $urltext,
                                    "url"       => $url,
                                    "alt"       => $alt,
                                    "filename"  => $filename,
                                    "delimg"    => $delimg,
                                    "hide"      => $hide,
                                    "hidepict"  => $hidepict,
                                    "id_news"   => $id_news,
                                    "page"      => $page),
                            "Редактировать",
                            "field");


    // Обработчик HTML-формы
    if(!empty($_POST))
    {
        // Проверяем корректность заполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Отображать дату или нет
            if($form->fields['hidedate']->value) $hidedate = "show";
            else $hidedate = "hide";
            // Скрытая или открытая фотография
            if($form->fields['hidepict']->value) $showhidepict = "show";
            else $showhidepict = "hide";

            // Проверяем, скрыта или открыта директория
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";
            // Удаляем старые файлы, если они имеются
            $delimg = $form->fields['delimg']->value;

            // if(!empty($str) || !empty($_FILES['filename']['name']))
             if(!empty($delimg))
            {
                $path = str_replace("//","/","../../".$news['urlpict']);
                $path_small = str_replace("//","/","../../".$news['urlpict_s']);
                if(file_exists($path))
                {
                    @unlink($path);

                }
                if(file_exists($path_small)){
                    @unlink($path_small);
                }
//                $url_pict = "urlpict = ' ',";
//                $url_pict_s = "urlpict_s = ' ',";
            }

            $url_pict = $url_pict_s = "";

            if(!empty($_FILES['filename']['name']))
            {
                // Удаляем старые изображения
                $query = "SELECT * FROM $tbl_news
                			WHERE id_news=$_GET[id_news]";
                $pos = mysql_query($query);
                if(!$pos)
                {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при извлечении
                                            текущей фотки");
                }
                $position = mysql_fetch_array($pos);
                $pospict = mysql_result($pos, 0);
                if(file_exists("../../".$position['urlpict']))
                {
                    @unlink("../../".$position['urlpict']);
                }
                if(file_exists("../../".$position['urlpict_s']))
                {
                    @unlink("../../".$position['urlpict_s']);
                }
                // Новые изображения
                $var = $form->fields['filename']->get_filename();
                if(!empty($var))
                {
                    $url_pict = "urlpict='files/news/$var',";
                    $url_pict_s = "urlpict_s='files/news/s_$var',";
                }
                // Извлекаем параметры галереи
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
                else
                {
                    $settings['width'] = 150;
                    $settings['height'] = 133;
                }
                // Формируем малое изобажение
                resizeimg("files/news/$var", "files/news/s_$var", $settings['width_news'], $settings['height_news']);
            }

			
			
            // Формируем SQL-запрос на добавление новости
            $query = "UPDATE $tbl_news
                        SET name = '{$form->fields['name']->value}',
                            body = '{$form->fields['body']->value}',
                            putdate = '{$form->fields['date']->get_mysql_format()}',
                            hidedate = '{$hidedate}',
                            urltext = '{$form->fields['urltext']->value}',
                            url     = '{$form->fields['url']->value}',
                            alt     = '{$form->fields['alt']->value}',
                            $url_pict
                            $url_pict_s
                            hide = '{$showhide}',
                            hidepict = '{$showhidepict}'
                        WHERE id_news =".$form->fields['id_news']->value;
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

    // Переменные, определяющие названия страницы и подсказку
    $title = "Редактирование новости";
    $pageinfo = '<p class="help"></p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href='#' onclick='history.back()'>Назад</a></p>";
    // Выводи сообщение об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style='\"color:red\"'>$err</span><br>";
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
    require("../utils/exception_object.php");
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