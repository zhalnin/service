<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 13/06/14
 * Time: 16:45
 */

namespace imei_service\command;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/classes/class.SendMail.php" );

/**
 * Class SendMail
 * Позволяет по переданным параметрам скомпоновать письма админу и клиенту.
 * Этот класс выполняет действия при отправке формы после проверки JS через
 * добавление атрибуту формы параметра ?cmd=SendMail
 * Обработку проводит командный класс \command\SendMail
 * Такие, как Guestbook и Register
 * @package imei_service\command
 */
class SendMail extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $request->addFeedback( 'Welcome to SendMail' );
        $type           = $request->getProperty( 'type' );
        $email_admin    = "imei_service@icloud.com";
        $email_client   = $request->getProperty( 'email' );
        $imei           = $request->getProperty( 'imei' );
        $udid           = $request->getProperty( 'udid' );
        $operator       = $request->getProperty( 'operator' );

        $manager = \imei_service\classes\MailConfig::get( $type );

        switch( $type ) {
            case 'unlock':
                $manager->make( 1 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                $manager->make( 2 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                sleep( 3 );
                return self::statuses( 'CMD_UNLOCK_OK' );
                break;
            case 'udid':
                $manager->make( 1 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                $manager->make( 2 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                sleep( 3 );
                return self::statuses( 'CMD_UDID_OK' );
                break;
            case 'guestbook':
                $manager->make( 1 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                sleep( 3 );
                return self::statuses( 'CMD_GUESTBOOK_OK' );
                break;
            case 'carrier':
                $manager->make( 1 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                $manager->make( 2 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                sleep( 3 );
                return self::statuses( 'CMD_CARRIER_OK' );
                break;
            case 'blacklist':
                $manager->make( 1 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                $manager->make( 2 )->email($email_admin, $email_client, $imei, $udid, $operator, $type, null, null   );
                sleep( 3 );
                return self::statuses( 'CMD_BLACKLIST_OK' );
                break;
        }
            return self::statuses( 'CMD_OK' );
    }
}
?>