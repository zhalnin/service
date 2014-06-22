<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/06/14
 * Time: 19:23
 */

namespace imei_service\command;
use \imei_service\base\SessionRegistry;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );

class Logout extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        setcookie('login',"",time() - 3600 );
        setcookie('pass',"",time() - 3600 );
        setcookie('auto',"",time() - 3600 );
        SessionRegistry::setSession('auto',"0");
        return self::statuses( 'CMD_OK' );
    }
}
?>