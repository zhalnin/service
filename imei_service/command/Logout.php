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
require_once( 'imei_service/domain/Login.php' );

class Logout extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $uid = SessionRegistry::getSession( 'uidu' ); // получаем id пользователя
        $logPassExist = \imei_service\domain\Login::find( $uid ); // проверяем наличие пользователя
        echo "<tt><pre>".print_r($logPassExist, true)."</pre></tt>";
        $logPassExist->setLastvisit( date( 'Y-m-d H:i:s', time() ) ); //  Обновление поля lastvisit
        $logPassExist->setOnline( 0 ); //  Обновление поля онлайн

        setcookie('loginu',"",time() - 3600 );
        setcookie('passu',"",time() - 3600 );
        setcookie('autou',"",time() - 3600 );

//        unset($_SESSION["login"]);
//        unset($_SESSION["pass"]);
        SessionRegistry::setSession('autou',"0");
        SessionRegistry::setSession('loginu',"");
        SessionRegistry::setSession('passu',"");
        SessionRegistry::setSession('uidu',"");
        return self::statuses( 'CMD_OK' );
    }
}
?>