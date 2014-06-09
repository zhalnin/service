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

        $id_catalog = $request->getProperty( 'id_catalog' );
        $id_parent = $request->getProperty( 'id_parent' );

        if( ! $id_catalog ) {
            $id = 0;
            $decorateCollection = \imei_service\domain\Unlock::find( $id );
            $request->setObject( 'decorateUnlock', $decorateCollection );

            $collection = \imei_service\domain\Unlock::findAll();
            $request->setObject( 'unlock', $collection );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//            echo "<tt><pre>".print_r( $collection, true )."</pre></tt>";
        } else {
            $id = 0;
            $decorateCollection = \imei_service\domain\Unlock::find( $id );
            $request->setObject( 'decorateUnlock', $decorateCollection );
            echo "<tt><pre>".print_r( $id_catalog, true )."</pre></tt>";
            return self::statuses( 'CMD_OK' );
        }
    }
}

?>