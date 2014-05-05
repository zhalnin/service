<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 05/05/14
 * Time: 18:58
 */

error_reporting(E_ALL & ~E_NOTICE);

// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Блок управления позициями (show(), hide(), up(), down())
require_once("../utils/utils.position.php");

$_GET['id'] = intval( $_GET['id'] );
$_GET['page'] = intval( $_GET['page'] );

try {
    hide( $_GET['id'], 'system_guestbook', "", "id" );
    header("Location: index.php?id=".$_GET['id']."&page=".$_GET['page']);


} catch (ExceptionMySQL $exc ) {
    require("../utils/exception_mysql.php" );
}
?>