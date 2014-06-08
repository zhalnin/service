<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 19:00
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Unlock.php" );

class Unlock extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id_parent = $request->getProperty( 'id_parent' );

        if( ! $id_parent ) {
            $id = 0;
            $decorateCollection = \imei_service\domain\Unlock::find();
            $request->setObject( 'decorateUnlock', $decorateCollection );

            $collection = \imei_service\domain\Unlock::findAll();
            $request->setObject( 'unlock', $collection );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//            echo "<tt><pre>".print_r( $collection, true )."</pre></tt>";
        } else {
            $collection = \imei_service\domain\Unlock::find( $id_parent );
            $request->setObject( 'unlockDetails', $collection );
            return self::statuses( 'CMD_OK' );
//            echo "<tt><pre>".print_r( $collection, true )."</pre></tt>";
        }
    }
}

?>