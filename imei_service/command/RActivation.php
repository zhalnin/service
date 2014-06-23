<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/06/14
 * Time: 12:14
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Login.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/classes/class.SendMail.php" );



class RActivation extends Command {

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

            if( intval( $findEmail->getStatus() ) !== 0 ) {
                $request->addFeedback( 'Ваша учетная запись уже активирована: <a href="?cmd=Login">Вы можете зайти на сайт используя вашу учетную запись.</a>' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }

            $activation     = $findEmail->getActivation();
            $login          = $findEmail->getLogin();
            $type           = $_POST['type'];
            $activateLogin  = new \imei_service\domain\Login( $findEmail->getId() );

            if( is_object( $activateLogin ) ) {
                $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
                $commsManager->make(1)->email( $email, 'imei_service@icloud.com', null, null, null, $type, $login, $activation ); // отправляем письмо админу
                $commsManager->make(2)->email( $email, 'imei_service@icloud.com', null, null, null, $type, $login, $activation ); // отправляем письмо клиенту
                return self::statuses( 'CMD_OK' );
            }
        }
    }
}
?>