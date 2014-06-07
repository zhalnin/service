<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 15:03
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view\templates;
session_start();
$sid_add_message = session_id();
 error_reporting(E_ALL & ~E_NOTICE);
//require_once("count.php");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?php echo htmlspecialchars($title, ENT_QUOTES); ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta content="width=1024" name="viewport">
    <meta content="<? echo htmlspecialchars($description, ENT_QUOTES); ?>" name="Description">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link href="imei_service/view/css/home-style.css" type="text/css" rel="stylesheet">
    <link href="imei_service/view/css/animatedCSS.css" type="text/css" rel="stylesheet">
    <link href="imei_service/view/css/form.css" type="text/css" rel="stylesheet">
    <link href="imei_service/view/css/style.css" type="text/css" rel="stylesheet">
<!--    <link href="css/wysiwyg.css" type="text/css" rel="stylesheet">-->
    <!--    <link href="css/style_enhanced.css" type="text/css" rel="stylesheet">-->
    <script type="text/javascript" src="imei_service/view/js/AlezhalModules.js"></script>
    <script type="text/javascript" src="imei_service/view/js/helperFunctions.js"></script>
    <script type="text/javascript" src="imei_service/view/js/wysiwyg.js"></script>
    <script type="text/javascript" src="imei_service/view/js/imei_form.js"></script>
    <script type="text/javascript" src="imei_service/view/js/utilities.js"></script>
    <script type="text/javascript" src="imei_service/view/js/lib.js"></script>
    <script type="text/javascript" src="imei_service/view/js/load.js"></script>
    <script type="text/javascript" src="imei_service/view/js/currency.js"></script>
    <script type="text/javascript" src="imei_service/view/js/dragMaster.js"></script>
</head>
<body>


