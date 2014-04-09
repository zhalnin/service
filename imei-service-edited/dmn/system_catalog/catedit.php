<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.04.12
 * Time: 19:44
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

try
{
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    if(empty($_POST))
    {
        $query = "SELECT * FROM $tbl_cat_catalog
                    WHERE id_catalog=$_GET[id_catalog]
                    LIMIT 1";
        $cat = mysql_query($query);
        if(!$cat)
        {
            throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                    "Ошибка при обращении
                                    к каталогу");
        }
        $_REQUEST = mysql_fetch_array($cat);
        $_REQUEST['page'] = $_GET['page'];
        if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;
    }

    $name               = new FieldText("name",
                                        "Название",
                                        true,
                                        $_REQUEST['name']);
    $order_title        = new FieldText("order_title",
                                        "Название заказа",
                                        true,
                                        $_REQUEST['order_title']);
    $description        = new FieldTextarea("description",
                                        "Описание",
                                        false,
                                        $_REQUEST['description'],
                                        '100',
                                        '20');
    $keywords           = new FieldText("keywords",
                                        "Ключевые слова",
                                        false,
                                        $_REQUEST['keywords']);
    $abbreviatura       = new FieldText("abbreviatura",
                                        "Аббревиатура страны",
                                        false,
                                        $_REQUEST['abbreviatura']);
    $modrewrite         = new FieldTextEnglish("modrewrite",
                                        "Название для<br>ReWrite",
                                        false,
                                        $_REQUEST['modrewrite']);
    $urlpict            = new FieldFile("urlpict",
                                        "Изображение",
                                        false,
                                        $_FILES,
                                        "../../images/country_flag/");
    $alt                = new FieldText("alt",
                                    "ALT-тег",
                                    false,
                                    $_REQUEST['alt']);
    $title_flag         = new FieldText("title_flag",
                                    "Название страны",
                                    false,
                                    $_REQUEST['title_flag']);
    $rounded_flag       = new FieldFile("rounded_flag",
                                        "Маленький флаг",
                                        false,
                                        $_FILES,
                                        "../../images/rounded_flag/");
    $alt_flag           = new FieldText("alt_flag",
                                    "ALT-тег",
                                    false,
                                    $_REQUEST['alt_flag']);
    $hide               = new FieldCheckbox("hide",
                                            "Отображать",
                                            $_REQUEST['hide']);
    $id_catalog         = new FieldHiddenInt("id_catalog",
                                        true,
                                        $_REQUEST['id_catalog']);
    $id_parent          = new FieldHiddenInt("id_parent",
                                    true,
                                    $_REQUEST['id_parent']);
    $page               = new FieldHiddenInt("page",
                                false,
                                $_REQUEST['page']);

//    if(empty($cat['urlpict'])) {
        $form = new Form(array("name"           => $name,
                            "order_title"   => $order_title,
                            "description"   => $description,
                            "keywords"      => $keywords,
                            "abbreviatura"  => $abbreviatura,
                            "modrewrite"    => $modrewrite,
                            "urlpict"       => $urlpict,
                            "alt"           => $alt,
                            "title_flag"    => $title_flag,
                            "rounded_flag"  => $rounded_flag,
                            "alt_flag"      => $alt_flag,
                            "hide"          => $hide,
                            "id_catalog"    => $id_catalog,
                            "id_parent"     => $id_parent,
                            "page"          => $page),
                        "Редактировать",
                        "field");
//    } else {
//        $form = new Form(array("name"           => $name,
//                            "description"   => $description,
//                            "keywords"      => $keywords,
//                            "abbreviatura"  => $abbreviatura,
//                            "modrewrite"    => $modrewrite,
//                            "urlpict"       => $urlpict,
//                            "alt"           => $alt,
//                            "hide"          => $hide,
//                            "id_catalog"    => $id_catalog,
//                            "id_parent"     => $id_parent,
//                            "page"          => $page),
//                        "Редактировать",
//                        "field");
//    }



    // Обработчик HTML-формы
    if(!empty($_POST))
    {
        // Проверяем корректность заполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Скрытый или открытый раздел
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";

            // Если загружаем большое изображение
            if(!empty($_FILES['urlpict']['name'])){
                $query = "SELECT * FROM $tbl_cat_catalog
                            WHERE id_catalog = $_GET[id_catalog]";
                $cats = mysql_query($query);
                if(!$cats){
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при извлечении
                        текущей фотки");
                }
                $catalogs = mysql_fetch_array($cats);

                $var = "images/country_flag/".$form->fields['urlpict']->get_filename();
                if($catalogs['urlpict'] != $var){
                    if(file_exists("../../".$catalogs['urlpict'])){
                        @unlink("../../".$catalogs['urlpict']);
                    }
                }
                if(!empty($var)){
                    $url_pict = "urlpict = '$var',";
                }
            }

            // Если загружаем маленькое изображение
            if(!empty($_FILES['rounded_flag']['name'])){
                $query = "SELECT * FROM $tbl_cat_catalog
                            WHERE id_catalog = $_GET[id_catalog]";
                $cats = mysql_query($query);
                if(!$cats){
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при извлечении
                        маленького изображения");
                }
                $catalogs = mysql_fetch_array($cats);

                $var = "images/rounded_flag/".$form->fields['rounded_flag']->get_filename();
                if($catalogs['rounded_flag'] != $var){
                    if(file_exists("../../".$catalogs['rounded_flag'])){
                        @unlink("../../".$catalogs['rounded_flag']);
                    }
                }
                if(!empty($var)){
                    $rounded_pict = "rounded_flag = '$var',";
                }
            }

            // Формируем SQL-запрос на добавление раздела
            $query = "UPDATE $tbl_cat_catalog
                        SET name = '{$form->fields[name]->value}',
                            order_title ='{$form->fields[order_title]->value}',
                            description = '{$form->fields[description]->value}',
                            keywords = '{$form->fields[keywords]->value}',
                            abbreviatura = '{$form->fields[abbreviatura]->value}',
                            modrewrite = '{$form->fields[modrewrite]->value}',
                            hide = '$showhide',
                            $url_pict
                            alt = '{$form->fields[alt]->value}',
                            title_flag = '{$form->fields[title_flag]->value}',
                            $rounded_pict
                            alt_flag = '{$form->fields[alt_flag]->value}'
                      WHERE id_catalog = {$form->fields[id_catalog]->value}";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при редактировании
                                        подраздела");
            }
            // Осуществляем редирект на главную страницу администратирования
            header("Location: index.php?".
                    "id_parent={$form->fields[id_parent]->value}&".
                    "page={$form->fields[page]->value}");
            exit();
        }
    }

    // Начало страницы
    $title = 'Редактирование подменю';
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
    // Выводим HTML-форму
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
ob_get_flush();
?>