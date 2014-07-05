<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 10:25 AM
 * To change this template use File | Settings | File Templates.
 */

ob_start();
//  Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы формы
require_once("../../config/class.config.dmn.php");

// Защита от SQL-инъекции
$_GET['id_parent'] = intval($_GET['id_parent']);

if(empty($_POST)) $_REQUEST['hide'] = true;
try
{
    $name           = new FieldText("name",
                                    "Название",
                                    true,
                                    $_POST['name']);
    $description    = new FieldTextarea("description",
                                        "Содержимое статьи",
                                        true,
                                        $_POST['description']);
    $keywords       = new FieldText("keywords",
                                    "Ключевые слова",
                                    false,
                                    $_POST['keywords']);
    $modrewrite     = new FieldTextEnglish("modrewrite",
                                            "Название для<br/>ReWrite",
                                            false,
                                            $_POST['modrewrite']);
    $hide           = new FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);
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
            $query = "SELECT MAX(pos) FROM $tbl_position
                        WHERE id_catalog = {$form->fields[id_parent]->value}";
            $pos = mysql_query($query);
            if(!$pos)
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при извлечении
                    текущей позиции");
            }
            $pos = mysql_result($pos, 0) + 1;
            // Скрытый или открытый элемент
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = "hide";
            // Формируем SQL-запрос на добавление элемента
            $query = "INSERT INTO $tbl_position
                        VALUES (NULL,
                        '{$form->fields[name]->value}',
                        '{$form->fields[description]->value}',
                        'article',
                        '{$form->fields[keywords]->value}',
                        '{$form->fields[modrewrite]->value}',
                        $pos,
                        '$showhide',
                        {$form->fields[id_parent]->value})";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                    $query,
                    "Ошибка при добавлении
                    новой позиции");
            }
            // Извлекаем значение первичного ключа только
            // что вставленного элемента, назначенного механизмом
            // AUTO_INCREMENT
            $id_position = mysql_insert_id();
            // Разбиваем текст на параграфы
            $par = preg_split("|\r\n|",
                $form->fields['description']->value);
            if(!empty($par))
            {
                $i = 0;
                foreach($par as $parag)
                {
                    $i++;
                    $sql[] = "(NULL,
                                '$parag',
                                'text',
                                'left',
                                'show',
                                $i,
                                $id_position,
                                {$form->fields[id_parent]->value})";
                }
                $query = "INSERT INTO $tbl_paragraph
                            VALUES ".implode(",",$sql);
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при добавлении
                        нового параграфа");
                }
            }
            // Осуществляем редирект на главную страницу администрирования
            header("Location: index.php?".
                "id_parent={$form->fields[id_parent]->value}&".
                "page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = 'Добавление элемента';
    $pageinfo = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href=# onclick='history.back()'>Назад</a></p>";
    // Выводим сообщение об ошибках, если они имеются
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