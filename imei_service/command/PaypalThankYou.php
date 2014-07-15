<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/07/14
 * Time: 00:26
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/classes/class.SendMail.php" );

class PaypalThankYou extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
//        echo "<tt><pre>".print_r($_POST, true)."</pre></tt>";
        $type = 'cart_paypal';

        $_SESSION['cart_imei_service'] = array();
        $_SESSION['total_items_imei_service'] = 0;
        $_SESSION['total_price_imei_service'] = 0.00;


        $commsManager = \imei_service\classes\MailConfig::get( 'cart_paypal' );  // параметр - тип commsManager
        $commsManager->make(1)->email( $_POST['receiver_email'], $_POST['payer_email'], null, null, null, $type, null, null, $_POST ); // отправляем письмо админу
        $commsManager->make(2)->email( $_POST['receiver_email'], $_POST['payer_email'], null, null, null, $type, null, null, $_POST ); //

        return self::statuses( 'CMD_OK' );
    }

}
?>