<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 10:17 AM
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

$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_catalog'] = intval($_GET['id_catalog']);


try
{
    if(empty($_POST))
    {
        $query = "SELECT * FROM $tbl_position
                WHERE id_catalog = $_GET[id_catalog] AND
                      id_position=$_GET[id_position]
                LIMIT 1";
        $pos = mysql_query($query);
        if(!$pos)
        {
            throw new ExceptionMySQL(mysql_error(),
                $query,
                "Ошибка при обращении к
                позиции");
        }
        $_REQUEST = mysql_fetch_array($pos);
        $_REQUEST['page'] = $_GET['page'];
        if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;

    }
    $name           = new FieldText("name",
                                    "Название",
                                    true,
                                    $_REQUEST['name']);
    $url            = new FieldText("url",
                                    "URL",
                                    true,
                                    $_REQUEST['url']);
    $keywords       = new FieldText("keywords",
                                    "Ключевые слова",
                                    false,
                                    $_REQUEST['keywords']);
    $modrewrite     = new FieldTextEnglish("modrewrite",
                                            "Название для<br>ReWrite",
                                            false,
                                            $_REQUEST['modrewrite']);
    $hide           = new FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);
    $id_position    = new FieldHiddenInt("id_position",
                                        true,
                                        $_REQUEST['id_position']);
    $id_catalog     = new FieldHiddenInt("id_catalog",
                                        true,
                                        $_REQUEST['id_catalog']);
    $page           = new FieldHiddenInt("page",
                                        false,
                                        $_REQUEST['page']);
    $form           = new Form(array("name"         => $name,
                                    "url"           => $url,
                                    "keywords"      => $keywords,
                                    "modrewrite"    => $modrewrite,
                                    "hide"          => $hide,
                                    "id_catalog"    => $id_catalog,
                                    "id_position"   => $id_position,
                                    "page"          => $page),
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
            // Скрытый или открытый раздел
            if($form->fields['hide']->value) $showhide = 'show';
            else $showhide = 'hide';
            // Формируем SQL-запрос на добавление раздела
            $query = "UPDATE $tbl_position
                    SET name        = '{$form->fields[name]->value}',
                        url         = '{$form->fields[url]->value}',
                        keywords    = '{$form->fields[keywords]->value}',
                        modrewrite  = '{$form->fields[modrewrite]->value}',
                        hide        = '$showhide'
                    WHERE id_position = {$form->fields[id_position]->value} AND
                          id_catalog = {$form->fields[id_catalog]->value}";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при редактировании
                    позиции");
            }

            // Осуществляем редирект на главную страницу администрирования
            header("Location: index.php?".
                "id_parent={$form->fields[id_catalog]->value}&".
                "page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = 'Редактирование позиции';
    $pageinfo = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href=# onclick='history.back()'></a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style='color: red'>$err</span><br>";
        }
    }
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