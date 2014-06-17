<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/06/14
 * Time: 16:20
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );

class Login extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

    }
} 