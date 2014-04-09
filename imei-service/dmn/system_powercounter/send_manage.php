<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17.06.12
 * Time: 21:08
 * To change this template use File | Settings | File Templates.
 */
ob_start();
// Выставляем уровень обработки ошибок
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
require_once("config.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем блок отображения текста в окне браузера
require_once("../utils/utils.print_page.php");

// Рассылка за один день
if($_GET['freq'] == 1) require_once("send_day.php");
// Рассылка за неделю
if($_GET['freq'] == 7) require_once("send_week.php");
// Рассылка за месяц
if($_GET['freq'] == 30) require_once("send_month.php");
// Если запрос выполнен удачно, осуществляем автоматический переход
// на страницу управления рассылкой
header("Location: send.php");
ob_get_flush();
?>