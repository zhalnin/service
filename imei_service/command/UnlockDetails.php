<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 23:55
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

//require_once( "imei_service/command/Command.php" );
//require_once( "imei_service/base/Registry.php" );
require_once( "imei_service/domain/UnlockDetails.php" );


class UnlockDetails extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to unlockDetails" );

        $collection = \imei_service\domain\UnlockDetails::findAll( $request->getProperty( 'id_catalog' ) );
        $request->setObject( 'unlockDetails', $collection );

//        echo "<tt><pre>".print_r( $unlock_collection, true)."</pre></tt>";

        return self::statuses( 'CMD_OK' );
    }
}
?>