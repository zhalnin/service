<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/12/13
 * Time: 22:15
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);



//require_once ("database/DataBase.php");
require_once("view/ViewHelper.php");

try {
    $request = \account\view\VH::getRequest();
    $DBH = \account\base\DataBaseRegistry::getDB();

//    echo "<tt><pre>".print_r($request,true)."</pre></tt>";

    $fio        = stripslashes( $_POST['fio'] );
    $fio        = htmlspecialchars($fio);
    $city       = stripslashes( $_POST['city'] );
    $city       = htmlspecialchars($city);
    $email      = stripslashes( $_POST['email'] );
    $email      = htmlspecialchars($email);
    $login      = stripslashes( $_POST['login'] );
    $login      = htmlspecialchars($login);
    $pass       = stripslashes( $_POST['pass'] );
    $pass       = htmlspecialchars($pass);
    $pass       = md5( ( $pass ) );
    $activation = md5( $email.time() );
    $code       = $activation;

    $insertQ = "INSERT INTO account (fio,city,email,login,pass,activation) VALUES (:fio,:city,:email,:login,:pass,:activation)";
    $insertV = array(
        'fio'           =>$fio,
        'city'          =>$city,
        'email'         =>$email,
        'login'         =>$login,
        'pass'          =>$pass,
        'activation'    =>$activation
    );
    $STH = $DBH->prepare($insertQ);
    $res = $STH->execute($insertV);
    // отправить письмо регистрирующемуся с ссылкой на активацию аккаунта


    if( $res ) {
        $to = $email;
        $subject = "Подтверждение активации";
        $message = "Для активации учетной записи пройдите, пожалуйста, по ссылке
        http://cyborg-ws.homeip.net:8888/talking/account/activation.php?login=".$login."&code=".$activation;
        $header = "Content-type:text/plane; charset=utf-8";

//        echo "mail to";
        mail($to,$subject,$message,$header);

        header("Location: sucess_reg.php");


    }
} catch (PDOException $e ) {
    echo $e->getMessage();
} catch( Exception $e ) {
    echo $e->getMessage();
}



?>