<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 15:03
 * To change this template use File | Settings | File Templates.
 */
session_start();
$sid_add_message = session_id();
 error_reporting(E_ALL & ~E_NOTICE);
require_once("count.php");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES); ?></title>
    <meta content="width=1024" name="viewport">
    <meta content="<? echo htmlspecialchars($description, ENT_QUOTES); ?>" name="Description">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link href="css/home-style.css" type="text/css" rel="stylesheet">
    <link href="css/form.css" type="text/css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
<!--    <link href="css/wysiwyg.css" type="text/css" rel="stylesheet">-->
    <!--    <link href="css/style_enhanced.css" type="text/css" rel="stylesheet">-->
    <script type="text/javascript" src="js/AlezhalModules.js"></script>
    <script type="text/javascript" src="js/helperFunctions.js"></script>
    <script type="text/javascript" src="js/imei_form.js"></script>
    <script type="text/javascript" src="js/utilities.js"></script>
    <script type="text/javascript" src="js/lib.js"></script>
    <script type="text/javascript" src="js/load.js"></script>
    <script type="text/javascript" src="js/currency.js"></script>
    <script type="text/javascript" src="js/wysiwyg.js"></script>
    <script type="text/javascript" src="js/dragMaster.js"></script>

</head>
<body>


