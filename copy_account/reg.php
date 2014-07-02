<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09/12/13
 * Time: 17:46
 * To change this template use File | Settings | File Templates.
 */


error_reporting(E_ALL & ~E_NOTICE);


require_once("view/ViewHelper.php");


    $request = \account\view\VH::getRequest();


//echo $_POST['code'];
//echo "<tt><pre>".print_r($request,true)."</pre></tt>";
?>

<! DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type"content="text/html; charset=utf-8">
    <title>account</title>
</head>
<body>
<div id="maket">
    <div id="header"></div>
    <div id="middle">
        <?php print $request->getFeedbackString();  ?>
<!--        <form name="form" method="post" action="tst_reg.php">-->
        <form name="form" method="post" action="ind_reg_controller.php">
            <fieldset>
                <legend>Register</legend>
                <label for="fio">FIO</label><div><input type="text" name="fio" value="<?php echo $_POST['fio']; ?>"/></div>
                <label for="city">City</label><div><input type="text" name="city" value="<?php echo $_POST['city']; ?>"/></div>
                <label for="email">Email</label><div><input type="text" name="email" value="<?php echo $_POST['email']; ?>"/></div>
                <label for="login">Login</label><div><input type="text" name="login"value="<?php echo $_POST['login']; ?>" /></div>
                <label for="pass">Password</label><div><input type="text" name="pass" /></div>
                <label for="submitted"></label><div><input type="hidden" name="submitted" value="yes" /></div>
                <label for="img">&nbsp;</label><div><img src="capcha/capcha.php" /></div>
                <label for="code">&nbsp;</label><div><input type="text" name="code" /></div>
                <label for="button">&nbsp;</label><div><input type="submit" value="send" /></div>
            </fieldset>
        </form>
    </div>
    <div id="rasporka"></div>
</div>
<div id="footer"></div>
</body>
</html>
