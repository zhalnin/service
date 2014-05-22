<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 23:32
 */

namespace imei_service\command;


class Contacts extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to Contacts" );

        return self::statuses( "CMD_OK" );
    }
}

?>