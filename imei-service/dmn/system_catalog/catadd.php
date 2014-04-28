<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16.04.12
 * Time: 23:10
 * To change this template use File | Settings | File Templates.
 */
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы формы
require_once("../../config/class.config.dmn.php");


if(empty($_POST)) $_REQUEST['hide'] = true;
try
{
    $name               = new FieldText("name",
                            "Название",
                            true,
                            $_POST['name']);
    $order_title        = new FieldText("order_title",
                                        "Название заказа",
                                        true,
                                        $_POST['order_title']);
    $description        = new FieldTextarea("description",
                                    "Описание",
                                    false,
                                    $_POST['description'],
                                    '100',
                                    '20');
    $keywords           = new FieldText("keywords",
                                        "Ключевые слова",
                                        false,
                                        $_POST['keywords']);
    $abbreviatura       = new FieldText("abbreviatura",
                            "Аббревиатура страны",
                            false,
                            $_POST['abbreviatura']);
    $modrewrite         = new FieldTextEnglish("modrewrite",
                                        "Название для<br/>ReWrite",
                                        false,
                                        $_POST['modrewrite']);
    $hide               = new FieldCheckbox("hide",
                            "Отображать",
                            $_REQUEST['hide']);
    $urlpict            = new FieldFile("urlpict",
                                    "Большой флаг",
                                    false,
                                    $_FILES,
                                    "../../images/country_flag/");
    $alt                = new FieldText("alt",
                                    "ALT-тег",
                                    false,
                                    $_POST['alt']);
    $title_flag         = new FieldText("title_flag",
                                    "Название страны",
                                    false,
                                    $_POST['title_flag']);
    $rounded_flag        = new FieldFile("rounded_flag",
                            "Маленький флаг",
                            false,
                            $_FILES,
                            "../../images/rounded_flag/");
    $alt_flag           = new FieldText("alt_flag",
                            "ALT-тег",
                            false,
                            $_POST['alt_flag']);
    $id_parent          = new FieldHiddenInt("id_parent",
                                    true,
                                    $_REQUEST['id_parent']);
    $page               = new FieldHiddenInt("page",
                                false,
                                $_REQUEST['page']);
    // Форма
    $form               = new Form(array("name"           => $name,
                                        "order_title"   => $order_title,
                                        "description"   => $description,
                                        "keywords"      => $keywords,
                                        "abbreviatura"  => $abbreviatura,
                                        "modrewrite"    => $modrewrite,
                                        "hide"          => $hide,
                                        "urlpict"       => $urlpict,
                                        "alt"           => $alt,
                                        "title_flag"    => $title_flag,
                                        "rounded_flag"  => $rounded_flag,
                                        "alt_flag"      => $alt_flag,
                                        "modrewrite"    => $modrewrite,
                                        "id_parent"     => $id_parent,
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
            // Извлекаем текущую максимальную позицию
            $query = "SELECT MAX(pos) FROM $tbl_cat_catalog
                        WHERE id_parent = {$form->fields[id_parent]->value}";
            $pos = mysql_query($query);
            if(!$pos)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при извлечении
                                        текущей позиции");
            }
            $position = mysql_result($pos, 0) + 1;

            // Скрытый или открытый раздел
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";

            $str = $form->fields['urlpict']->get_filename();
            if(!empty($str))
            {
                $img = "images/country_flag/".$form->fields['urlpict']->get_filename();
            }
            else $img = "";

            $rounded_str = $form->fields['rounded_flag']->get_filename();
            if(!empty($rounded_str))
            {
                $rounded_img = "images/rounded_flag/".$form->fields['rounded_flag']->get_filename();
            }
            else $rounded_img = "";
            // Формируем SQL-запрос на добавление раздела
            $query = "INSERT INTO $tbl_cat_catalog
                    VALUES (NULL,
                            '{$form->fields[name]->value}',
                            '{$form->fields[order_title]->value}',
                            '{$form->fields[description]->value}',
                            '{$form->fields[keywords]->value}',
                            '{$form->fields[abbreviatura]->value}',
                            '{$form->fields[modrewrite]->value}',
                            $position,
                            '$showhide',
                            '$img',
                            '{$form->fields[alt]->value}',
                            '$rounded_img',
                            '{$form->fields[title_flag]->value}',
                            '{$form->fields[alt_flag]->value}',
                            '{$form->fields[id_parent]->value}')";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при добавлении нового
                                        раздела");
            }
            // Осуществляем редирект на главную страницу администрирования
            header("Location: index.php?".
                    "id_parent={$form->fields[id_parent]->value}&".
                    "page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = "Добавление подраздела";
    $pageinfo = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href=# onclick='history.back()'>Назад</a></p>";
    // Выводим сообщение об ошибках, если они имеются
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