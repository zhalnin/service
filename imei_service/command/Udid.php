<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/06/14
 * Time: 17:14
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Udid.php" );

class Udid extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id = 0;
        $udidCollection = \imei_service\domain\Udid::find( $id );
        $request->setObject( 'udidCollection', $udidCollection );
        $request->addFeedback( "Welcome to UDID" );
        return self::statuses( 'CMD_OK' );
    }
}
?>