<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.04.12
 * Time: 22:59
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
$_REQUEST['id_catalog'] = intval($_REQUEST['id_catalog']);

try
{
    $operator   = new FieldSelect("operator",
                                "Оператор",
                                  array("AT&T"                => "AT&T",
                                        "Avea"                => "Avea",
                                        "Bell"                => "Bell",
                                        "Bouygues"            => "Bouygues",
                                        "Cellcom"             => "Cellcom",
                                        "Claro"               => "Claro",
                                        "Etisalat"            => "Etisalat",
                                        "EMEA"                => "EMEA",
                                        "Entel"               => "Entel",
                                        "Fido/Rogers"         => "Fido/Rogers",
                                        "KPN"                 => "KPN",
                                        "KT-Freetel"          => "KT-Freetel",
                                        "Mobinil"             => "Mobinil",
                                        "Mobilkom"            => "Mobilkom",
                                        "Movistar"            => "Movistar",
                                        "Netcom"              => "Netcom",
                                        "Omnitel"             => "Omnitel",
                                        "Optus"               => "Optus",
                                        "Orange"              => "Orange",
                                        "O2"                  => "O2",
                                        "Pelephone"           => "Pelephone",
                                        "Play"                => "Play",
                                        "SFR"                 => "SFR",
                                        "Softbank"            => "Softbank",
                                        "STC"                 => "STC",
                                        "Sunrise"             => "Sunrise",
                                        "Swisscom"            => "Swisscom",
                                        "3Three/Hutchison"    => "3Three/Hutchison",
                                        "Tukcell"             => "Tukcell",
                                        "Telenor"             => "Telenor",
                                        "Tele2"               => "Tele2",
                                        "Telia"               => "Telia",
                                        "Telus"               => "Telus",
                                        "Telstra"             => "Telstra",
                                        "Tim"                 => "Tim",
                                        "T-Mobile"            => "T-Mobile",
                                        "Vivo"                => "Vivo",
                                        "Verizon"             => "Verizon",
                                        "Vodafone"            => "Vodafone",
                                        "Zain"                => "Zain"),
                                $_REQUEST['operator']);

    $cost    = new FieldText("cost",
                              "Стоимость",
                               true,
                                $_REQUEST['cost']);
    $timeconsume    = new FieldText("timeconsume",
                        "Сроки отвязки",
                        true,
                        $_REQUEST['timeconsume']);
    $compatible    = new FieldText("compatible",
                                "Совместимость",
                                false,
                                $_REQUEST['compatible']);
    $status    = new FieldText("status",
                                "Статус аппарата",
                                false,
                                $_REQUEST['status']);

    $currency   = new FieldSelect("currency",
                                "Валюта",
                                array("RUR" => "RUR",
                                      "EUR" => "EUR",
                                      "USD" => "USD"),
                                $_REQUEST['currency']);
    $hide       = new FieldCheckbox("hide",
                                    "Отображать",
                                    $_REQUEST['hide']);
    $id_catalog = new FieldHiddenInt("id_catalog",
                                        true,
                                        $_REQUEST['id_catalog']);
    $form       = new Form(array("operator"         => $operator,
                                 "cost"             => $cost,
                                 "timeconsume"      => $timeconsume,
                                 "compatible"       => $compatible,
                                    "status"        => $status,
                                    "currency"      => $currency,
                                    "hide"          => $hide,
                                    "id_catalog"    => $id_catalog),
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
            $query = "SELECT MAX(pos)
                        FROM $tbl_cat_position
                        WHERE id_catalog={$form->fields[id_catalog]->value}";
            $pos = mysql_query($query);
            if(!$pos)
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка при извлечении
                                         текущей позиции");
            }
            $position = mysql_result($pos, 0)+1;
            // Выясняем, скрыта или открыта позиция
            if($form->fields['hide']->value) $showhide = "show";
            else $showhide = 'hide';

            // Формируем SQL-запрос на добавление позиции
            $query = "INSERT INTO $tbl_cat_position
                        VALUES (NULL,
                                '{$form->fields[operator]->value}',
                                '{$form->fields[cost]->value}',
                                '{$form->fields[timeconsume]->value}',
                                '{$form->fields[compatible]->value}',
                                '{$form->fields[status]->value}',
                                '{$form->fields[currency]->value}',
                                '$showhide',
                                '$position',
                                NOW(),
                                '{$form->fields[id_catalog]->value}')";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка добавления
                                        позиции");
            }
            // Осуществляем педирект на главную страницу администрирования
            header("Location: position.php?".
                    "id_catalog={$form->fields[id_catalog]->value}&".
                    "page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = 'Добавление позиции';
    $pageinfo = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href='#'onclick='history.back()'>Назад</a></p>";
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