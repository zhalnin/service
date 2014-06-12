<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 15:07
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/FaqPosition.php" );

class FaqPosition extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $request->addFeedback( "Welcome to faqPosition" );

        $collection = \imei_service\domain\FaqPosition::find( $request->getProperty( 'id_position' ) );
        $request->setObject( 'faqPosition', $collection );
//        echo "<tt><pre>".print_r($collection, true)."</pre></tt>";
        return self::statuses( 'CMD_OK' );

    }
}
?>