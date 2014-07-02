<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/12/13
 * Time: 14:38
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once("view/ViewHelper.php");

$request = \account\view\VH::getRequest();

?>

<! DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Сброс пароля</title>
</head>
<body>
<div id="maket">
    <div id="header"></div>
    <div id="middle">
        <?php print $request->getFeedbackString(); ?>
        <form method="post" name="form" action="ind_resend_pass_controller.php">
            <fieldset>
                <legend>Сброс пароля</legend>
                <label for="help">Введите, пожалуйста, адрес электронной почты,<br /> который вы указывали при регистрации на нашем сайте<br /><br /></label>
                <label for="email">Email</label><div><input type="text" name="email" /></div>
                <label for="submitted">&nbsp;</label><div><input type="hidden" name="submitted" /></div>
                <label for="submit">&nbsp;</label><div><input type="submit" value="отправить" /></div>
            </fieldset>
        </form>
    </div>
    <div id="rasporka"></div>
</div>
<div id="footer"></div>
</body>
</html>