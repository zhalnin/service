<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 19:43
 */

namespace imei_service\command;


require_once( "imei_service/command/Command.php" );
require_once( "imei_service/base/Registry.php" );

class News extends Command {
    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to NEWS IMEI-SERVICE" );


        return self::statuses('CMD_OK');
    }
}
?>