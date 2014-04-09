<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 9:46 AM
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
        $query = "SELECT * FROM $tbl_catalog
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

    $name           = new FieldText("name",
                                    "Название",
                                    true,
                                    $_REQUEST['name']);
    $description    = new FieldText("description",
                                    "Описание",
                                    false,
                                    $_REQUEST['description']);
    $keywords       = new FieldText("keywords",
                                    "Ключевые слова",
                                    false,
                                    $_REQUEST['keywords']);
    $modrewrite     = new FieldTextEnglish("modrewrite",
                                            "Название для<br/>ReWrite",
                                            false,
                                            $_REQUEST['modrewrite']);
    $hide           = new FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);
    $id_catalog     = new FieldHiddenInt("id_catalog",
                                        true,
                                        $_REQUEST['id_catalog']);
    $id_parent      = new FieldHiddenInt("id_parent",
                                        true,
                                        $_REQUEST['id_parent']);
    $page           = new FieldHiddenInt("page",
                                        false,
                                        $_REQUEST['page']);
    $form           = new Form(array("name"         => $name,
                                    "description"   => $description,
                                    "keywords"      => $keywords,
                                    "modrewrite"    => $modrewrite,
                                    "hide"          => $hide,
                                    "id_catalog"    => $id_catalog,
                                    "id_parent"     => $id_parent,
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
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";
            // Формируем SQL-запрос на добавление раздела
            $query = "UPDATE $tbl_catalog
                        SET name = '{$form->fields[name]->value}',
                            description = '{$form->fields[description]->value}',
                            keywords = '{$form->fields[keywords]->value}',
                            modrewrite = '{$form->fields[modrewrite]->value}',
                            hide = '$showhide'
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