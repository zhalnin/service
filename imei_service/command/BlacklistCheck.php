<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 12:29
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/BlacklistCheck.php" );

class BlacklistCheck extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id = 0;
        $blacklistCheckCollection = \imei_service\domain\BlacklistCheck::find( $id );
        $request->setObject( 'blacklistCheckCollection', $blacklistCheckCollection );
        $request->addFeedback( "Welcome to Blacklist Check" );
        return self::statuses( 'CMD_OK' );
    }
}
?>