<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/06/14
 * Time: 20:27
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/domain/Login.php" );


class Register extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $fio = $request->getProperty( 'fio' );
        $city = $request->getProperty( 'city' );
        $email = $request->getProperty( 'email' );
        $login = $request->getProperty( 'login' );
        $pass = $request->getProperty( 'pass' );
        $repass = $request->getProperty( 'repass' );
        $code = $request->getProperty( 'code' );
        $recode = $_SESSION['code'];
        $submitted = $request->getProperty( 'submitted' );

            $findEmail = \imei_service\domain\Login::findEmail( $email ); // проверка email на существование в БД
            $findLogin = \imei_service\domain\Login::findLogin( $login ); // проверка Логина на существование в БД

            if( $submitted !== 'yes' ) { // если форма не отправлена
                return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
            }
            if( empty( $fio ) ) {
                $request->addFeedback( 'Заполните поле "Имя"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( empty( $city ) ) {
                $request->addFeedback( 'Заполните поле "Город"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( empty( $email ) ) {
                $request->addFeedback( 'Заполните поле "Email"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( checkEmail( $email ) == false ) {
                $request->addFeedback( 'Введите корректный адрес "Email"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( is_object( $findEmail ) ) {
                $request->addFeedback( 'Этот "Email" уже использовался при регистрации' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( empty( $login ) ) {
                $request->addFeedback( 'Заполните поле "Логин"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( is_object( $findLogin ) ) {
                $request->addFeedback( 'Этот "Логин" уже использовался при регистрации' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( empty( $pass ) ) {
                $request->addFeedback( 'Заполните поле "Пароль"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( empty( $repass ) ) {
                $request->addFeedback( 'Заполните поле "Подтвердить Пароль"' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( $pass !== $repass ) {
                $request->addFeedback( 'Пароли не совпадают' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( $code !== $recode ) {
                $request->addFeedback( 'Не верно введен защитный код' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }

            $activation = md5( $email.time() );
            $pass = md5( $pass );

    //        добавляем в БД
            $login_obj = new \imei_service\domain\Login( null, $fio, $city, $email, $login, $pass, $activation, 0 );





    //        if( is_object( $login_obj ) ) {
    ////            отправить письмо для активации
    //            $to = $email;
    //            $subject = "Подтверждение активации";
    //            $message = "Для активации учетной записи пройдите, пожалуйста, по ссылке
    // (serverName() ) - http://cyborg-ws.homeip.net:8888/talking/account/activation.php ?cmd=Register&lgn=".$login."&cAct=".$activation;
    //            $header = "Content-type:text/plane; charset=utf-8";
    //            mail($to,$subject,$message,$header);
    //        }




            return self::statuses( 'CMD_REGISTER_OK' );

//        echo "<tt><pre> код ".print_r( preg_match( '|[a-z0-9_-\.]+@[a-z0-9_-\.]+\.[a-z]{2,6}|i', $email ) , true ) ."</pre></tt>";
//        echo "<tt><pre>".print_r( $login_obj , true ) ."</pre></tt>";
    }
}
?>