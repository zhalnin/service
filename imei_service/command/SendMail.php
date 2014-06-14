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

class SendMail extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $request->addFeedback( 'Welcome to SendMail' );
        $type           = $request->getProperty( 'type' );
        $email          = "support@imei-service.ru";
        $email_client   = $request->getProperty( 'email' );
        $imei           = $request->getProperty( 'imei' );
        $udid           = $request->getProperty( 'udid' );
        $operator       = $request->getProperty( 'operator' );

        $manager = \imei_service\classes\MailConfig::get( $type );

        $manager->make( 1 )->email($email, $email_client, $imei, $udid, $operator, $type );
        $manager->make( 2 )->email($email, $email_client, $imei, $udid, $operator, $type );

//                    echo "<tt><pre><--- start --->\r\n".print_r( $manager, true)."\r\n<--- end ---></pre></tt>";
//        echo "<tt><pre><--- start --->\r\n".print_r( $type, true)."\r\n<--- end ---></pre></tt>";

            return self::statuses( 'CMD_OK' );
    }
}
?>