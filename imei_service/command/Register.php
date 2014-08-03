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
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/view/utils/getNameServer.php" );
require_once( "imei_service/domain/Login.php" );


class Register extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $fio        = $_POST['fio'];
        $city       = $_POST['city'];
        $email      = $_POST['email'];
        $url        = $_POST['url'];
        $login      = $_POST['login'];
        $pass       = $_POST['pass'];
        $repass     = $_POST['repass'];
        $code       = $_POST['code'];
        $putdate    = $_POST['putdate'];
        $lastvisit  = $_POST['lastvisit'];
        $recode     = $_SESSION['code'];
        $submitted  = $_POST['submitted'];
        $email_admin = 'imei_service@icloud.com';

        $findEmail = \imei_service\domain\Login::findEmail( $email ); // проверка email на существование в БД
        $findLogin = \imei_service\domain\Login::findLogin( $login ); // проверка Логина на существование в БД

        if( $submitted !== 'yes' ) { // если форма не отправлена
            return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
        }
        if( empty( $fio ) ) {
            $request->addFeedback( 'Заполните поле "Имя"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
//        if( empty( $city ) ) {
//            $request->addFeedback( 'Заполните поле "Город"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
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
//        if( empty( $url ) ) {
//            $request->addFeedback( 'Заполните поле "Ваш сайт"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
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
            $request->addFeedback( 'Неверно введен защитный код' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }

        $activation = md5( $email.time() ); // хэш для кода активации по email
        $pass = md5( $pass ); // хэш пароля для INSERT

        // создается объект в domain, если успешно - в control отрабатывает \domain\ObjectWatcher->performOperations()
        $login_obj = new \imei_service\domain\Login( null,
                                                    $fio,
                                                    $city,
                                                    $email,
                                                    $url,
                                                    $login,
                                                    $pass,
                                                    $activation,
                                                    0,
                                                    $putdate,
                                                    $lastvisit,
                                                      'unblock' );

        // Если учетные данные добавлены успешно ( будет создан объект, если не будет добавлена, то объект не будет создан )
        if( is_object( $login_obj ) ) {
            $commsManager = \imei_service\classes\MailConfig::get( 'register' );  // параметр - тип commsManager
            $commsManager->make(1)->email( $email_admin, $email, null, null, null, 'register', $login, $activation, null ); // отправляем письмо админу
            $commsManager->make(2)->email( $email_admin, $email, null, null, null, 'register', $login, $activation, null ); // отправляем письмо клиенту
            return self::statuses( 'CMD_REGISTER_OK' ); // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
        }
//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";
    }
}
?>