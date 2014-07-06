<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/05/14
 * Time: 18:45
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;


class NewsPrint extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to NewsPrint IMEI-SERVICE" );
        return self::statuses( 'CMD_OK' );
    }
}
?>