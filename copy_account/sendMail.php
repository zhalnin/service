<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17/12/13
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */


error_reporting(E_ALL & ~E_NOTICE);
require_once("base/Registry.php");
require_once("database/DataBase.php");



try {
    if( isset( $_GET['email'] ) ) {
        $email = stripslashes($_GET['email']);
        $email = htmlspecialchars($email);
        $selectC = "SELECT COUNT(*) FROM account WHERE email='{$email}' AND status=0";
        $select = "SELECT * FROM account WHERE email='{$email}' AND status=0 LIMIT 1";
    } else if ( isset ( $_GET['login']) && isset( $_GET['pass'] ) ) {
        $login = stripslashes($_GET['login']);
        $login = htmlspecialchars($login);
        $pass = stripslashes( md5( $_GET['pass'] ) );
        $pass= htmlspecialchars($pass);
        $selectC = "SELECT COUNT(*) FROM account WHERE login='{$login}' AND pass='{$pass}' AND status=0";
        $select = "SELECT * FROM account WHERE login='{$login}' AND pass='{$pass}' AND status=0 LIMIT 1";
    } else {
        exit("File not found");
    }

        $DBH = \account\base\DataBaseRegistry::getDB();
        $STHC = $DBH->query($selectC);
        if( $STHC->fetchColumn() > 0 ) {
            $STH = $DBH->query( $select );
            while ( $row = $STH->fetch() ){
                $to = $row['email'];
                $login = $row['login'];
                $activation = $row['activation'];
                $subject = "Подтверждение активации";
                $message = "Это повторное письмо для активации учетной записи пройдите, пожалуйста, по ссылке
        http://cyborg-ws.homeip.net:8888/talking/account/activation.php?login=".$login."&code=".$activation;
                $header = "Content-type:text/plane; charset=utf-8";

                mail($to,$subject,$message,$header);

                header("Location: sucess_reg.php");
            }
        } else {
            exit("User not found");
        }

} catch (Exception $e ) {
    echo $e->getMessage();
} catch ( PDOException $e ) {
    echo $e->getMessage();
}

?>