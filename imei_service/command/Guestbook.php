<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:12
 */

namespace imei_service\command;

use imei_service\command\Command;

class Guestbook extends Command {
    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to Guestbook IMEI-SERVICE");

        return self::statuses( 'CMD_OK' );
    }
}