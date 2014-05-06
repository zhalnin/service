<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07.05.12
 * Time: 22:05
 * To change this template use File | Settings | File Templates.
 */
 error_reporting(E_ALL & ~E_NOTICE);
ob_start();
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
require_once("../utils/utils.resizeimg.php");
require_once("../../class/class.Database.php");

try
{
    $query = "SELECT * FROM $tbl_contactaddress LIMIT 1";
    $cnt = mysql_query($query);
    if(!$cnt)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении к
                                контактной информации");
    }
    $contact = mysql_fetch_array($cnt);
    if(empty($_POST)) $_REQUEST = $contact;

    // Телефон

    $name          = new FieldText('name',
                                        "Заголовок",
                                        false,
                                        $_REQUEST['name']);

    $phone          = new FieldText("phone",
                                        "Телефоны",
                                        false,
                                        $_REQUEST['phone']);
    // Факс
    $fax            = new FieldText("fax",
                                         "Факс",
                                         false,
                                        $_REQUEST['fax']);
    // Ссылка
    $email          = new FieldText("email",
                                        "E-mail",
                                        false,
                                        $_REQUEST['email']);
    $skype          = new FieldText("skype",
                                        "Skype",
                                        false,
                                        $_REQUEST['skype']);
    $vk             = new FieldText("vk",
                                        "Группа ВКонтакте",
                                        false,
                                        $_REQUEST['vk']);
    // Адрес
    $address        = new FieldTextarea("address",
                                        "Адрес",
                                        false,
                                        $_REQUEST['address']);
    $urlpict        = new FieldFile("urlpict",
                                      "Изображение",
                                       false,
                                       $_FILES,
                                      "../../files/contacts");
    $alt            = new FieldText("alt",
                                  "ALT-тег",
                                  false,
                                  $_REQUEST['alt']);
    // Инициируем форму массивом из двух элементов
    // управления - поля ввода name и текстовой области
    // textarea
    $form           = new Form(array("name"     => $name,
                                    "phone"     => $phone,
                                    "fax"       => $fax,
                                    "email"     => $email,
                                    "skype"     => $skype,
                                    "vk"        => $vk,
                                    "address"   => $address,
                                    "urlpict"   => $urlpict,
                                    "alt"       => $alt),
                                "Редактировать",
                                "field");
    // Обработчик HTML-формы
    if(!empty($_POST)) {
        // Проверяем корректность заполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(  empty($error)) {
          $var = $form->fields['urlpict']->get_filename();
          if(!empty($var)){
              $big = "files/contacts/".$var;
              $small = "files/contacts/s_".$var;
              $width = 180;
              $height = 180;
              resizeimg( $big, $small, $width, $height);
          } else {
            $photo = "";
          }
            $query = "SELECT photo, photo_small FROM $tbl_contactaddress";
            $pos = mysql_query( $query );
            if( !$pos ) {
                throw new ExceptionMySQL( mysql_error(),
                                        $query,
                                        "Ошибка при выборке из таблицы 'system_contactaddress'");
            }
            if( mysql_num_rows( $pos ) ) {
                $selectCount = mysql_fetch_array( $pos );
            }
            if( empty( $selectCount['photo_small']) ) {
//                if( $form->fields['urlpict']->get_filename()  ){
//                    $photo = $form->fields['urlpict']->get_filename();
//
//                } else {
//                    $photo = "";
//                }
                $query = "INSERT INTO $tbl_contactaddress
                            VALUES (
                            '{$form->fields[name]->value}',
                            '{$form->fields[phone]->value}',
                            '{$form->fields[fax]->value}',
                            '{$form->fields[email]->value}',
                            '{$form->fields[skype]->value}',
                            '{$form->fields[vk]->value}',
                            '{$form->fields[address]->value}',
                            '$big',
                            '$small',
                            '{$form->fields[alt]->value}'
                            )";
                if( !mysql_query( $query ) ) {
                    throw new ExceptionMySQL(mysql_error(),
                        $query,
                        "Ошибка при вставке
                        контактной информации");
                }

            } else {
                if( file_exists( "../../".$selectCount['photo'] ) ) {
                    @unlink("../../".$selectCount['photo']);

                }
                if( file_exists("../../".$selectCount['photo_small'] ) ) {
                    @unlink("../../".$selectCount['photo_small']);
                }
                // Формируем SQL-запрос на добавление позиции
                $query = "UPDATE $tbl_contactaddress
                            SET name    = '{$form->fields[name]->value}',
                                phone   = '{$form->fields[phone]->value}',
                                fax     = '{$form->fields[fax]->value}',
                                email   = '{$form->fields[email]->value}',
                                skype   = '{$form->fields[skype]->value}',
                                vk      = '{$form->fields[vk]->value}',
                                address = '{$form->fields[address]->value}',
                                photo   = '$big',
                                photo_small = '$small',
                                alt   = '{$form->fields[alt]->value}'";
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                                            $query,
                                            "Ошибка при редактировании
                                            контактной информации");
                }
            }
            // Осуществляем редирект на главную страницу
            // администрирования
            header("Location: ../index.php");
            exit();
        }
    }
    // Данные переменные определяют название страницы и подсказку.
    $title = "Редактирование контактной информации";
    $pageinfo = '<p class="help"></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href=# onclick='window.history.back()'>Назад</a></p>";
    // Выводим сообщение об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style=\"color: red\">$err</span>";
        }
    }
    // Выводим HTML-форму
    $form->print_form();

    // Включаем завершение страницы
    require_once("../utils/bottom.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionObject $exc)
{
    require("../utils/exception_object.php");
}
ob_get_flush();
?>