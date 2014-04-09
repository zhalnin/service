<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 11:31 AM
 * To change this template use File | Settings | File Templates.
 */

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем функцию изменения размера изображения
require_once("../utils/utils.resizeimg.php");

// Защита от SQL-инъекции
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_position'] = intval($_GET['id_position']);


if(empty($_POST)) $_REQUEST['hide'] = true;
try
{
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
                                    array("text"        => "Параграф",
                                        "title_h1"      => "Заголовок H1",
                                        "title_h2"      => "Заголовок H2",
                                        "title_h3"      => "Заголовок H3",
                                        "title_h4"      => "Заголовок H4",
                                        "title_h5"      => "Заголовок H5",
                                        "title_h6"      => "Заголовок H6",
                                        "list"          => "Список"),
                                    $_REQUEST['type']);
    $align          = new FieldSelect("align",
                                    "Выравнивание параграфа",
                                    array("left"    => "Слева",
                                        "center"    => "По центру",
                                        "right"     => "Справа"),
                                    $_REQUEST['align']);
    $hidepict       = new FieldCheckbox("hidepict",
                                        "Отображать фото",
                                        $_REQUEST['hide']);
    $hide           = new FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);
    $id_position    = new FieldHiddenInt("id_position",
                                        true,
                                        $_REQUEST['id_position']);
    $id_catalog     = new FieldHiddenInt("id_catalog",
                                        true,
                                        $_REQUEST['id_catalog']);
    $pos            = new FieldHidden("pos",
                                    false,
                                    $_REQUEST['pos']);
    $page           = new FieldHiddenInt("page",
                                        false,
                                        $_REQUEST['page']);
    $form           = new Form(array("name"         => $name,
                                    "namepict"      => $namepict,
                                    "alt"           => $alt,
                                    "big"           => $big,
                                    "hidepict"      => $hidepict,
                                    "type"          => $type,
                                    "align"         => $align,
                                    "hide"          => $hide,
                                    "id_catalog"    => $id_catalog,
                                    "id_position"   => $id_position,
                                    "pos"           => $pos,
                                    "page"          => $page),
                                "Добавить",
                                "field");

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
        // Проверяем корректность заполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Скрытый или открытый элемент
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";

             $paragraph = Database::get_next_id($tbl_paragraph);

 //           $query = "SELECT MAX(id_paragraph)FROM $tbl_paragraph
 //                     WHERE id_catalog = {$form->fields[id_catalog]->value} AND
 //                           id_position={$form->fields[id_position]->value}";
 //           $par =  mysql_query($query);
 //           if(!$par)
 //           {
 //               throw new ExceptionMySQL(mysql_errno(),
 //                   $query,
 //                   "Ошибка при извлечении
 //                   текущей позиции");
 //           }
 //           $paragraph = mysql_result($par, 0) + 1;

            $img_var = $form->fields['big']->get_filename();
            if(!empty($img_var)){
                if($form->fields['hidepict']->value) $showhidepict = "show";
                else $showhidepict = "hide";
                $query = "SELECT MAX(pos) FROM $tbl_paragraph_image
                          WHERE id_catalog={$form->fields['id_catalog']->value}";
                $pospict = mysql_query($query);
                if(!$pospict)
                {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка извлечения
                         фотографии");
                }
                $pospict = mysql_result($pospict, 0) + 1;

                $var = $form->fields['big']->get_filename();
                if(!empty($var))
                {
                    $big = "files/article/".$var;
                    $small = "files/article/s_".$var;
                }
                else $big = "";
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
                resizeimg($big, $small, $settings['width'], $settings['height']);
                $query = "INSERT INTO $tbl_paragraph_image
                          VALUES(NULL,
                                  '{$form->fields[namepict]->value}',
                                  '{$form->fields[alt]->value}',
                                  '$small',
                                  '$big',
                                  '$showhidepict',
                                  $pospict,
                                  {$form->fields['id_position']->value},
                                  {$form->fields['id_catalog']->value},
                                  $paragraph)";
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при добавлении
                        фотографии");
                }
            }

            if(empty($form->fields['pos']->value))
            {
                // Вставляем параграф в конец статьи
                // Извлекаем текущую максимальную позицию
                $query = "SELECT MAX(pos) FROM $tbl_paragraph
                        WHERE id_catalog = {$form->fields[id_catalog]->value} AND
                            id_position={$form->fields[id_position]->value}";
                $pos =  mysql_query($query);
                if(!$pos)
                {
                    throw new ExceptionMySQL(mysql_errno(),
                        $query,
                        "Ошибка при извлечении
                        текущей позиции");
                }
                $pos = mysql_result($pos, 0) + 1;
            }

//            elseif($position_form->fields['pos']->value < 0)
            elseif($form->fields['pos']->value < 0)
            {
                // Вставляем параграф в начало статьи
                $query = "UPDATE $tbl_paragraph
                        SET pos = pos + 1
                        WHERE id_catalog={$form->fields[id_catalog]->value} AND
                        id_position={$form->fields[id_position]->value}";
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при редактировании
                        позиции параграфа");
                }
                $pos = 1;
            }
            else
            {
                // Вставляем параграф в середину статьи
                $query = "UPDATE $tbl_paragraph
                            SET pos = pos + 1
                            WHERE id_catalog={$form->fields[id_catalog]->value} AND
                                  id_position={$form->fields[id_position]->value} AND
                                  pos > {$form->fields[pos]->value}";
                if(!mysql_query($query))
                {
                    throw   new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при редактировании
                        позиции параграфа");
                }
                $pos = $form->fields['pos']->value + 1;
            }
            // Формируем SQL-запрос на добавление позиции
            $query = "INSERT INTO $tbl_paragraph
                       VALUES(NULL,
                                '{$form->fields['name']->value}',
                                '{$form->fields['type']->value}',
                                '{$form->fields['align']->value}',
                                '$showhide',
                                $pos,
                                {$form->fields['id_position']->value},
                                {$form->fields['id_catalog']->value})";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при добавлении
                    нового параграфа");
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
    $title = 'Добавление параграфа';
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
ob_get_flush();
?>