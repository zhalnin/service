<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 23:55
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/UnlockDetails.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/view/utils/utils.checkIMEI.php" );


class UnlockDetails extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
//        $request->addFeedback( "Welcome to unlockDetails" );

        $collection = \imei_service\domain\UnlockDetails::findAll( $request->getProperty( 'idc' ) );
        $request->setObject( 'unlockDetails', $collection );

//        $type = $request->getProperty( 'type' );
//        $email = $request->getProperty( 'email' );
//        $imei = $request->getProperty( 'imei' );
//        $operator = $request->getProperty( 'operator' );
//        $email_admin = 'imei_service@icloud.com';
//
//        if( empty( $email ) ) {
//            $request->addFeedback( 'Заполните поле "Email"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( checkEmail( $email ) == false ) {
//            $request->addFeedback( 'Введите корректный адрес "Email"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( empty( $imei ) ) {
//            $request->addFeedback( 'Заполните поле "IMEI"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( checkIMEI( $imei ) == false ) {
//            $request->addFeedback( 'Введите корректный номер "IMEI"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }

//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";

//        $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
//        $commsManager->make(1)->email( $email_admin, $email, $imei, null, $operator, $type, null, null, null ); // отправляем письмо админу
//        $commsManager->make(2)->email( $email_admin, $email, $imei, null, $operator, $type, null, null, null ); // отправляем письмо клиенту

//        return self::statuses( 'CMD_UNLOCK_OK' ); // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
    }
}
?>