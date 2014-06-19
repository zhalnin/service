<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/12/13
 * Time: 00:07
 * To change this template use File | Settings | File Templates.
 */

//namespace account\controller;
error_reporting(E_ALL & ~E_NOTICE);

require_once("view/ViewHelper.php");

$request = \account\view\VH::getRequest();
if( isset( $_COOKIE['login'] ) ) {
    $login = "value=".$_COOKIE['login'];
}
if( isset( $_COOKIE['password'] ) ) {
    $password = "value=".$_COOKIE['password'];
}
?>

<! DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Вход</title>
</head>
<body>
<div id="maket">
    <div id="header"></div>
    <div id="middle">
        <?php print $request->getFeedbackString(); ?>
        <form method="post" name="form" action="ind_controller.php">
            <fieldset>
                <legend>Вход на сайт</legend>
                <label for="login">Логин: </label><div><input type="text" name="login" <?php echo $login; ?>></div>
                <label for="pass">Пароль: </label><div><input type="password" name="pass" <?php echo $password; ?>></div>
                <label for="submitted">&nbsp;</label><div><input type="hidden" name="submitted" value="yes" /></div>
                <label for="auto">Запомнить меня: </label><div><input type="checkbox" name="auto" value="1" /></div>
                <label for="button">&nbsp;</label><div><input type="submit" name="submit" value="войти" /></div>
                <label for="resend_pass">&nbsp;</label><div><a href="ind_resend_pass_controller.php">Забыл логин или пароль</a></div>
                <label for="register">&nbsp;</label><div><a href="ind_reg_controller.php">Зарегистрироваться</a></div>
            </fieldset>
        </form>
    </div>
    <div id="rasporka"></div>
</div>
<div id="footer"></div>
</body>
</html>
