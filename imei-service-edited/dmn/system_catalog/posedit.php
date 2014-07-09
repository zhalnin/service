<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01.05.12
 * Time: 16:55
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

if(empty($_POST)) $_REQUEST['hide'] = true;
$_REQUEST['id_catalog'] = intval($_REQUEST['id_catalog']);

try
{
    $query = "SELECT * FROM $tbl_cat_position
        WHERE id_position=$_GET[id_position]";
    $pos = mysql_query($query);
    if(!$pos)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка при обращении к
                                таблице позиций");
    }
    $position = mysql_fetch_array($pos);
    if(empty($_POST))
    {
        $_REQUEST = $position;
        if($position['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;
    }

    $operator   = new FieldSelect("operator",
                                "Услуга",
        array("AT&T/Verizon"                              => "AT&T/Verizon",
            "T-Mobile USA"                              => "T-Mobile USA",
            "UK/USA"                                    => "UK/USA",
            "Только SimLock"                            => "Только SimLock",
            "Полная проверка GSX"                       => "Полная проверка GSX",
            "Регистрация UDID в аккаунте разработчика"  => "Регистрация UDID в аккаунте разработчика",
            "AT&T"                                      => "AT&T",
            "Avea"                                      => "Avea",
            "Bell"                                      => "Bell",
            "Bouygues"                                  => "Bouygues",
            "Cellcom"                                   => "Cellcom",
            "Claro"                                     => "Claro",
            "Etisalat"                                  => "Etisalat",
            "EMEA"                                      => "EMEA",
            "Entel"                                     => "Entel",
            "Fido/Rogers"                               => "Fido/Rogers",
            "KPN"                                       => "KPN",
            "KT-Freetel"                                => "KT-Freetel",
            "Mobinil"                                   => "Mobinil",
            "Mobilkom"                                  => "Mobilkom",
            "Movistar"                                  => "Movistar",
            "Netcom"                                    => "Netcom",
            "Omnitel"                                   => "Omnitel",
            "Optus"                                     => "Optus",
            "Orange"                                    => "Orange",
            "O2"                                        => "O2",
            "Pelephone"                                 => "Pelephone",
            "Play"                                      => "Play",
            "SFR"                                       => "SFR",
            "Softbank"                                  => "Softbank",
            "STC"                                       => "STC",
            "Sunrise"                                   => "Sunrise",
            "Swisscom"                                  => "Swisscom",
            "3Three/Hutchison"                          => "3Three/Hutchison",
            "Tukcell"                                   => "Tukcell",
            "Telenor"                                   => "Telenor",
            "Tele2"                                     => "Tele2",
            "Telia"                                     => "Telia",
            "Telus"                                     => "Telus",
            "Telstra"                                   => "Telstra",
            "Tim"                                       => "Tim",
            "T-Mobile"                                  => "T-Mobile",
            "Vivo"                                      => "Vivo",
            "Verizon"                                   => "Verizon",
            "Vodafone"                                  => "Vodafone",
            "Zain"                                      => "Zain"),
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
    $id_position = new FieldHiddenInt("id_position",
                                        true,
                                        $_REQUEST['id_position']);
    $form       = new Form(array("operator"         => $operator,
                                "cost"              => $cost,
                                "timeconsume"       => $timeconsume,
                                "compatible"        => $compatible,
                                "status"            => $status,
                                "currency"          => $currency,
                                "hide"              => $hide,
                                "id_catalog"        => $id_catalog,
                                "id_position"       => $id_position),
                            "Редактировать",
                            "field");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
        // Проверяем корректность заполнения HTML-формы
        // и проверяем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Скрытый или открытый раздел
            if($form->fields['hide']->value) $showhide = 'show';
            else $showhide = 'hide';
            // Формируем SQL-запрос на редактирование позиции
            $query = "UPDATE $tbl_cat_position
                    SET
                        operator    = '{$form->fields[operator]->value}',
                        cost        = '{$form->fields[cost]->value}',
                        timeconsume = '{$form->fields[timeconsume]->value}',
                        compatible  = '{$form->fields[compatible]->value}',
                        status      = '{$form->fields[status]->value}',
                        currency    = '{$form->fields[currency]->value}',
                        hide        = '$showhide',
                        putdate = NOW()
                    WHERE id_position = {$form->fields[id_position]->value} AND
                          id_catalog = {$form->fields[id_catalog]->value}";
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка редактирования
                                        позиции");
            }
            // Осуществляем редирект на главную страницу администрирования
            header("Location: position.php?".
                   "id_catalog={$form->fields[id_catalog]->value}&".
                    "page={$form->fields[page]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = "Редактирование позиции";
    $pageinfo = "<p class=help'></p>";
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    echo "<span><a href=# onclick='history.back()'>Назад</a></span>";
    // Выводим сообщения об ошибках, если они имеются
    if($error)
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

