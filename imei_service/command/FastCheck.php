<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 13:57
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );


class FastCheck extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $request->addFeedback( "Welcome to FastCheck" );
        return self::statuses( 'CMD_OK' );
    }
}
?>