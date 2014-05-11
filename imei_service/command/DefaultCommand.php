<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 14:26
 */
namespace imei_service\command;

require_once( "imei_service/command/Command.php" );

class DefaultCommand extends Command {
    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to IMEI-SERVICE" );
        return self::statuses('CMD_OK');
    }
}
?>