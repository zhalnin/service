<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 11:44 AM
 * To change this template use File | Settings | File Templates.
 */

ob_start();
error_reporting(E_ALL & ~E_NOTICE);

// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем функцию изменения размера изображения
require_once("../utils/utils.resizeimg.php");

// Защита от SQL-инъекции
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_paragraph'] = intval($_GET['id_paragraph']);
try
{
    if(empty($_POST))
    {
        $query = "SELECT * FROM $tbl_paragraph
                    WHERE id_catalog=$_GET[id_catalog] AND
                          id_position=$_GET[id_position] AND
                          id_paragraph=$_GET[id_paragraph]
                    LIMIT 1";
        $par = mysql_query($query);
        if(!$par)
        {
            throw new ExceptionMySQL(mysql_error(),
                $query,
                "Ошибка при обращении
                                     к позиции");
        }
        $_REQUEST = mysql_fetch_array($par);
        $_REQUEST['page'] = $_GET['page'];
        if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;

        $query = "SELECT * FROM $tbl_paragraph_image
                WHERE id_catalog=$_GET[id_catalog] AND
                          id_position=$_GET[id_position] AND
                          id_paragraph=$_GET[id_paragraph]
                LIMIT 1";
        $parimg = mysql_query($query);
        if(!$parimg)
        {
            throw new ExceptionMySQL(mysql_error(),
                $query,
                "Ошибка при обращении
                                    к позиции");
        }
        $paragraph_img = mysql_fetch_array($parimg);
//      $_REQUEST = mysql_fetch_array($parimg);
        $_REQUEST['namepict'] = $paragraph_img[name];
        $_REQUEST['alt'] = $paragraph_img[alt];
        if($paragraph_img['hide'] == 'show') $_REQUEST['hidepict'] = true;
        else $_REQUEST['hidepict'] = false;


    }
    $name           = new FieldTextarea("name",
                                    "Содержимое",
                                    true,
                                    $_REQUEST['name'],
                                    50,
                                    15);
    $namepict       = new FieldText("namepict",
                                    "Название изображения",
                                    false,
                                    $_REQUEST['namepict']);
    $alt            = new FieldText("alt",
                                    "ALT-тег",
                                    false,
                                    $_REQUEST['alt']);
    $big            = new FieldFile("big",
                                    "Изображение",
                                    false,
                                    $_FILES,
                                    "../../files/article/");
    $type           = new FieldSelect("type",
                                    "Тип параграфа",
                                    array("text"      => "Параграф",
                                        "title_h1"    => "Заголовок H1",
                                        "title_h2"    => "Заголовок H2",
                                        "title_h3"    => "Заголовок H3",
                                        "title_h4"    => "Заголовок H4",
                                        "title_h5"    => "Заголовок H5",
                                        "title_h6"    => "Заголовок H6",
                                        "list"        => "Список"),
                                    $_REQUEST['type']);
    $align          = new FieldSelect("align",
                                    "Тип параграфа",
                                    array(  "left"  => "Слева",
                                        "center"    => "По центру",
                                        "right"     => "Справа"),
                                    $_REQUEST['align']);
    $hidepict       = new FieldCheckbox("hidepict",
                                    "Отображать фото",
                                    $_REQUEST['hidepict']);
    $hide           = new FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);
    $id_catalog     = new FieldHiddenInt("id_catalog",
                                        true,
                                        $_REQUEST['id_catalog']);
    $id_position    = new FieldHiddenInt("id_position",
                                        true,
                                        $_REQUEST['id_position']);
    $id_paragraph   = new FieldHiddenInt("id_paragraph",
                                        true,
                                        $_REQUEST['id_paragraph']);
    $page           = new FieldHiddenInt("page",
                                        false,
                                        $_REQUEST['page']);
    $form           = new Form(array( "name"            => $name,
                                    "namepict"          => $namepict,
                                    "alt"               => $alt,
                                    "big"               => $big,
                                    "type"              => $type,
                                    "align"             => $align,
                                    "hidepict"          => $hidepict,
                                    "hide"              => $hide,
                                    "id_catalog"        => $id_catalog,
                                    "id_position"       => $id_position,
                                    "id_paragraph"      => $id_paragraph,
                                    "page"              => $page),
                                "Редактировать",
                                "field");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
        // Проверяем корректность зполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Скрытая или открытая позиция
            if($form->fields['hidepict']->value) $showhidepict = "show";
            else $showhidepict = "hide";

            // Формируем SQL-запрос на редактирование фото
            $big = $small = "";
            if(!empty($_FILES['big']['name']))
            {
                // Удаляем старые изображения
                $query = "SELECT * FROM $tbl_paragraph_image
                      WHERE id_position = $_GET[id_position] AND
                            id_catalog = $_GET[id_catalog] AND
                            id_paragraph = $_GET[id_paragraph]";
                $pos = mysql_query($query);
                if(!$pos)
                {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при извлечении
                                      текущей позиции");
                }
                $position = mysql_fetch_array($pos);
                $pospict = mysql_result($pos, 0);
                if(file_exists("../../".$position['big']))
                {
                    @unlink("../../".$position['big']);
                }
                if(file_exists("../../".$position['small']))
                {
                    @unlink("../../".$position['small']);
                }
                // Новые изображения
                $var = $form->fields['big']->get_filename();
                if(!empty($var))
                {
                    $big = "big='files/article/$var',";
                    $small = "small='files/article/s_$var',";
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
                resizeimg("files/article/$var", "files/article/s_$var", $settings['width'], $settings['height']);
            }
            $query = "UPDATE $tbl_paragraph_image
                    SET name = '{$form->fields[namepict]->value}',
                        alt = '{$form->fields[alt]->value}',
                        $small
                        $big
                        hide = '$showhidepict'
                    WHERE id_position = {$form->fields[id_position]->value} AND
                          id_catalog = {$form->fields[id_catalog]->value} AND
                          id_paragraph = {$form->fields[id_paragraph]->value}";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при обновлении
                    фотографии");
            }

            // Скрытая или открытая позиция
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";
            // Формируем SQL-запрос на редактирование позиции
            $query = "UPDATE $tbl_paragraph
                        SET name = '{$form->fields[name]->value}',
                            type = '{$form->fields[type]->value}',
                            align = '{$form->fields[align]->value}',
                            hide = '{$form->fields[hide]->value}'
                        WHERE id_position = {$form->fields[id_position]->value} AND
                              id_paragraph = {$form->fields[id_paragraph]->value}";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при редактировании
                                        параграфа");
            }
            // Осуществляем редирект на главную страницу администрирования
            header("Location: paragraph.php?".
                "id_position={$form->fields[id_position]->value}&".
                "id_catalog={$form->fields[id_catalog]->value}&".
                "page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = 'Редактирование параграфа';
    $pageinfo = '<p class="help"></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href='#' onclick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style='color: red'>$err</span><br/> ";
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
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionObject $exc)
{
    require("../utils/exception_object.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}

// Включаем завершение страницы
require_once("../utils/bottom.php");
?>