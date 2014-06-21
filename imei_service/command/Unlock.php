<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 19:00
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Unlock.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/view/utils/utils.checkIMEI.php" );

class Unlock extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id_catalog = $request->getProperty( 'idc' );
        $id_parent = $request->getProperty( 'idp' );

        if( ! $id_catalog ) {
            $id = 0;
            $decorateCollection = \imei_service\domain\Unlock::find( $id );
            $request->setObject( 'decorateUnlock', $decorateCollection );
            $collection = \imei_service\domain\Unlock::findAll();
            $request->setObject( 'unlock', $collection );

            $type = $request->getProperty( 'type' );
            $email = $request->getProperty( 'email' );
            $imei = $request->getProperty( 'imei' );

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
            $commsManager->make(1)->email( $email, 'imei_service@icloud.com', $imei, null, null, $type, null, null ); // отправляем письмо админу
            $commsManager->make(2)->email( $email, 'imei_service@icloud.com', $imei, null, null, $type, null, null ); // отправляем письмо клиенту

            return self::statuses( 'CMD_UNLOCK_OK' ); // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );

        } else {
            $id = 0;
            $decorateCollection = \imei_service\domain\Unlock::find( $id );
            $request->setObject( 'decorateUnlock', $decorateCollection );
            $factory = \imei_service\mapper\PersistenceFactory::getFactory( 'imei_service\\domain\\Unlock' );
            $unlock_assembler = new \imei_service\mapper\DomainObjectAssembler( $factory );
            $unlock_idobj = new \imei_service\mapper\UnlockIdentityObject( 'id_catalog' );
            $unlock_idobj->eq( $request->getProperty( 'idc' ) )->field( 'hide' )->eq( 'show' );
            $unlock_collection = $unlock_assembler->findOne( $unlock_idobj );
//        $obj->setUnlock( $unlock_collection );
            $request->setObject( 'unlockParent', $unlock_collection );
            return self::statuses( 'CMD_OK' );
        }
    }
}
?>