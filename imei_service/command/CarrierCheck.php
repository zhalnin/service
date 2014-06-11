<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 11:57
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/CarrierCheck.php" );


class CarrierCheck extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $id = 0;
        $CarrierCheckCollection = \imei_service\domain\CarrierCheck::find( $id );
        $request->setObject( 'carrierCheckCollection', $CarrierCheckCollection );
        $request->addFeedback( "Welcome to CarrierCheck" );
        return self::statuses( 'CMD_OK' );
    }
}
?>