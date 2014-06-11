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

require_once( "imei_service/command/Command.php" );

class Faq extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $request->addFeedback( 'Welcome to FAQ' );

    }
}