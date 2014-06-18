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
require_once( "imei_service/view/utils/getIP.php" );
require_once( "imei_service/domain/LoginOshibka.php" );

class Login extends Command {

    protected $error = "";

    function doExecute( \imei_service\controller\Request $request ) {

        $ip = getIP();
        // Удаляем из таблицы IP, которые дольше 3 минут, проверяем наличие соответствия и количество ошибок текущего IP
        $loginOshibka = \imei_service\domain\LoginOshibka::find( $ip );

            if( $loginOshibka->getCol() > 2 ) { // если col - количество неверно введенных раз больше 2
                $this->error = "error"; // присваиваем значение
                // добавляем текст ошибки
                $request->addFeedback( 'Вы неверно ввели логин или пароль три раза подряд. Повторите попытку через 15 минут. ');
                return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
            } else {
                $this->error = "";
            }

        if( $request->getProperty( 'submitted') !== 'yes' ) { // если форма не отправлена
            $this->error = 'error'; // присваиваем значение
            $request->addFeedback( "Заполните форму, чтобы закончить вход" ); // добавляем текст ошибки
            return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
        }



//        echo "<tt><pre>".print_r( $ip , true ) ."</pre></tt>";
//        echo "<tt><pre>".print_r( $loginOshibka, true ) ."</pre></tt>";
    }
} 