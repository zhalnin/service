<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 11:57
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/CarrierCheck.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/view/utils/utils.checkIMEI.php" );
require_once( "imei_service/classes/class.SendMail.php" );



class CarrierCheck extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $id = 0;
        $CarrierCheckCollection = \imei_service\domain\CarrierCheck::find( $id );
        $request->setObject( 'carrierCheckCollection', $CarrierCheckCollection );
        $request->addFeedback( "Welcome to CarrierCheck" );

        $type = $request->getProperty( 'type' );
        $email = $request->getProperty( 'email' );
        $imei = $request->getProperty( 'imei' );
        $email_admin = 'imei_service@icloud.com';

        if( empty( $email ) ) {
            $request->addFeedback( 'Заполните поле "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkEmail( $email ) == false ) {
            $request->addFeedback( 'Введите корректный адрес "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $imei ) ) {
            $request->addFeedback( 'Заполните поле "IMEI"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkIMEI( $imei ) == false ) {
            $request->addFeedback( 'Введите корректный номер "IMEI"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }

//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";

        $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
        $commsManager->make(1)->email( $email_admin, $email, $imei, null, null, $type, null, null ); // отправляем письмо админу
        $commsManager->make(2)->email( $email_admin, $email, $imei, null, null, $type, null, null ); // отправляем письмо клиенту

        return self::statuses( 'CMD_CARRIER_OK' ); // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
    }
}
?>