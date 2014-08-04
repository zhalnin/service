<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 19:58
 */

namespace dmn\command;
//require_once( 'dmn/view/utils/security_mod.php' );
require_once( "dmn/command/Command.php" );

class DefaultCommand extends Command {
    function doExecute( \dmn\controller\Request $request ) {
        $request->addFeedback( "Welcome to IMEI-SERVICE" );
        return self::statuses('CMD_OK');
    }
}
?>