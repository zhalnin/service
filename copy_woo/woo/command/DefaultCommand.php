<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01/01/14
 * Time: 21:52
 * To change this template use File | Settings | File Templates.
 */

namespace woo\command;

require_once( "woo/command/Command.php" );

class DefaultCommand extends Command {

    function doExecute( \woo\controller\Request $request ) {
        $request->addFeedback( "Welcome to Woo" );
//        return self::statuses('CMD_ERROR');
        return self::statuses('CMD_OK');
    }
}

?>
