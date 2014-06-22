<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/06/14
 * Time: 16:58
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Login.php" );



class Activation extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $lgn    = $_GET['lgn'];
        $cAct   = $_GET['cAct'];
        $findLogin = \imei_service\domain\Login::findLogin( $lgn ); // проверка Логина на существование в БД
//        echo "<tt><pre>".print_r( $findLogin , true ) ."</pre></tt>";

        if( empty( $lgn ) ) {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $cAct ) ) {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( is_object( $findLogin ) ) {
            if( $findLogin->getLogin() !== $lgn ) {
                return self::statuses( 'CMD_ERROR' );
            }
            if( $findLogin->getActivation() !== $cAct ) {
                return self::statuses( 'CMD_ERROR' );
            }
            if( intval( $findLogin->getStatus() ) !== 0 ) {
                $request->addFeedback( "Ваша учетная запись уже активирована" );
                return self::statuses( 'CMD_OK' );
            }
        } else {
            return self::statuses( 'CMD_ERROR' );
        }

        $findLogin->setStatus( 1 ); // обновляем поле в БД status с 0 на 1 - т.е. активируем учетную запись
        $activateLogin = new \imei_service\domain\Login( $findLogin->getId() );
            return self::statuses( 'CMD_ACTIVATION_OK' );

    }
}

//localhost:8888/service/runner.php?cmd=Activation&lgn=zhalnin78&cAct=4008c3cdbb1d8adc2cbf3e1eb44a2e4a
//localhost:8888/service/runner.php?cmd=Activation&lgn=zhalnin&cAct=yes
?>