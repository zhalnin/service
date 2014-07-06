<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/06/14
 * Time: 16:58
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем командный класс
require_once( "imei_service/command/Command.php" );
// подключаем класс Login
require_once( "imei_service/domain/Login.php" );


/**
 * Class Activation
 * Для активации учетной записи после регистрации
 * @package imei_service\command
 */
class Activation extends Command {

    /**
     * Выполняется в controller для каждой команды command (cmd)
     * @param \imei_service\controller\Request $request
     * @return mixed
     */
    function doExecute( \imei_service\controller\Request $request ) {
        $lgn    = $_GET['lgn']; // параметр логин из ссылки для активации
        $cAct   = $_GET['cAct']; // параметр код активации из ссылки для активации
        $findLogin = \imei_service\domain\Login::findLogin( $lgn ); // проверка Логина на существование в БД

        if( empty( $lgn ) ) { // если отсутствует логин
            // заново показываем форму повторно
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $cAct ) ) { // если отсутствует код активации
            // заново показываем форму повторно
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( is_object( $findLogin ) ) { // если логин присутствует в БД
            if( $findLogin->getLogin() !== $lgn ) { // если логин не равен параметру
                // вызываем скрпт с указанием ошибки activationError
                return self::statuses( 'CMD_ERROR' );
            }
            if( $findLogin->getActivation() !== $cAct ) { // если код активации не равен параметру
                // вызываем скрпт с указанием ошибки activationError
                return self::statuses( 'CMD_ERROR' );
            }
            if( intval( $findLogin->getStatus() ) !== 0 ) { // если учетная запись уже активирована
                $request->addFeedback( "Ваша учетная запись уже активирована" );
                // завершаем успешно activationAlready
                return self::statuses( 'CMD_OK' );
            }
        } else { // если логин отсутствует в БД
            // вызываем скрпт с указанием ошибки activationError
            return self::statuses( 'CMD_ERROR' );
        }

        $findLogin->setStatus( 1 ); // обновляем поле в БД status с 0 на 1 - т.е. активируем учетную запись
        // создаем объект Login с нужным ID и обновляем статус учетной записи
        $activateLogin = new \imei_service\domain\Login( $findLogin->getId() );
            // вызываем activatonSuccess
            return self::statuses( 'CMD_ACTIVATION_OK' );

    }
}

//localhost:8888/service/runner.php?cmd=Activation&lgn=zhalnin78&cAct=4008c3cdbb1d8adc2cbf3e1eb44a2e4a
//localhost:8888/service/runner.php?cmd=Activation&lgn=zhalnin&cAct=yes
?>