<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/06/14
 * Time: 17:14
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Udid.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/view/utils/utils.checkUDID.php" );

class Udid extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id = 0;
        $udidCollection = \imei_service\domain\Udid::find( $id );
        $request->setObject( 'udidCollection', $udidCollection );
        $request->addFeedback( "Welcome to UDID" );

        $type = $request->getProperty( 'type' );
        $email = $request->getProperty( 'email' );
        $udid = $request->getProperty( 'udid' );

        if( empty( $email ) ) {
            $request->addFeedback( 'Заполните поле "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkEmail( $email ) == false ) {
            $request->addFeedback( 'Введите корректный адрес "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $udid ) ) {
            $request->addFeedback( 'Заполните поле "UDID"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkUDID( $udid ) == false ) {
            $request->addFeedback( 'Введите корректный номер "UDID"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }

//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";

        $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
        $commsManager->make(1)->email( $email, 'imei_service@icloud.com', null, $udid, null, $type, null, null ); // отправляем письмо админу
        $commsManager->make(2)->email( $email, 'imei_service@icloud.com', null, $udid, null, $type, null, null ); // отправляем письмо клиенту

        return self::statuses( 'CMD_UDID_OK' ); // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
    }
}
?>