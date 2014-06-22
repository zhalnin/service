<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/06/14
 * Time: 19:34
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Login.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );



class FLogin extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $email = $_POST['email'];
        $submitted = $_POST['submitted'];
        $findEmail = \imei_service\domain\Login::findEmail( $email ); // проверка email на существование в БД

        if( $submitted !== 'yes' ) { // если форма не отправлена
            return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
        }
        if( empty( $email ) ) {
            $request->addFeedback( 'Заполните поле "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkEmail( $email ) == false ) {
            $request->addFeedback( 'Введите корректный адрес "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( ! is_object( $findEmail ) ) {
            $request->addFeedback( 'Этот "Email" не использовался при регистрации' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        } else {


            $pass_u = 'root'.time();
            $pass = md5( $pass_u );
            $findEmail->setPass($pass);
            $activateLogin = new \imei_service\domain\Login( $findEmail->getId() );

            if( is_object( $activateLogin ) ) {
//                $commsManager = \imei_service\classes\MailConfig::get( 'register' );  // параметр - тип commsManager
//                $commsManager->make(1)->email( $email, 'imei_service@icloud.com', null, null, null, 'register', $login, $activation ); // отправляем письмо админу
//                $commsManager->make(2)->email( $email, 'imei_service@icloud.com', null, null, null, 'register', $login, $activation ); // отправляем письмо клиенту
            }
            return self::statuses( 'CMD_ACTIVATION_OK' );

//            $pass_u = пароль
//            $pass = пароль_в_md5
//            $login = логин
//            UPDATE - обновить таблицу: $pass($findEmail->setPass($pass)) по id

//            SendMail - выслать письмо на указанный email с пароль и логин

//            echo "<tt><pre>".print_r( $findEmail , true ) ."</pre></tt>";
        }

    }

}
?>