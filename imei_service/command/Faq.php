<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 15:14
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Faq.php" );

class Faq extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id_position = $request->getProperty('id_position');
        $id = 0;
        $catalogCollection = \imei_service\domain\Faq::find( $id );
        $request->setObject( 'catalogCollection', $catalogCollection );

        echo "<tt><pre>".print_r( $catalogCollection, true)."</pre></tt>";

        $request->addFeedback( 'Welcome to FAQ' );

    }
}