<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 06/07/14
 * Time: 19:56
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/UnlockDetails.php" );
require_once( "imei_service/domain/Unlock.php" );

require_once( "imei_service/base/Registry.php" );


class Cart extends Command {

    function doExecute( \imei_service\controller\Request $request ) {


//        echo $_SESSION['total_items_imei_service'];
//        echo $_SESSION['total_price_imei_service'];
//        echo "<tt><pre>".print_r( $_SESSION['cart_imei_service'], true )."</pre></tt>";

        foreach ( $_SESSION['cart_imei_service'] as $id_catalog => $id_position ) {
            foreach ( $id_position as $position => $qty ) {
                $colCatalog[][$qty] = \imei_service\domain\Unlock::findByCatalog( $id_catalog );
                $colCatalogPosition[][$qty] = \imei_service\domain\UnlockDetails::findByPosAndCat( $position, $id_catalog );
            }
        }

        $request->setObject( 'cartCatalog', $colCatalog );
        $request->setObject( 'cartCatalogPosition', $colCatalogPosition );

        return self::statuses( 'CMD_OK' );
    }
} 