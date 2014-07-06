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
require_once( "imei_service/classes/class.SendMail.php" );




class FLogin extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $email      = $_POST['email'];
        $email_admin = 'imei_service@icloud.com';
        $submitted  = $_POST['submitted'];
        $findEmail  = \imei_service\domain\Login::findEmail( $email ); // проверка email на существование в БД
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

            if( intval( $findEmail->getStatus() ) === 0 ) {
                $request->addFeedback( 'Ваша учетная запись еще не активирована: <a href="?cmd=RActivation">Повторно выслать письмо для активации учетной записи.</a>' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }

            $login          = $findEmail->getLogin();
            $pass_u         = $login.substr( time(), -4 );
            $pass           = md5( $pass_u );
            $type           = $_POST['type'];

            $findEmail->setPass($pass);

            $activateLogin = new \imei_service\domain\Login( $findEmail->getId() );

            if( is_object( $activateLogin ) ) {
                $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
                $commsManager->make(1)->email( $email_admin, $email, null, null, null, $type, $login, $pass_u ); // отправляем письмо админу
                $commsManager->make(2)->email( $email_admin, $email, null, null, null, $type, $login, $pass_u ); // отправляем письмо клиенту
                return self::statuses( 'CMD_OK' );
            }
        }
    }
}
?>