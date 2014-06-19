<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/06/14
 * Time: 20:27
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );

class Register extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $code_ses = \imei_service\base\SessionRegistry::getSession('code');
//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";
        echo "<tt><pre>".print_r( $code_ses , true ) ."</pre></tt>";
    }
}
?>