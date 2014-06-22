<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/06/14
 * Time: 16:20
 */

namespace imei_service\command;
use imei_service\base\SessionRegistry;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/view/utils/getIP.php" );
require_once( "imei_service/domain/LoginOshibka.php" );
require_once( "imei_service/domain/Login.php" );

class Login extends Command {

//    protected $error = "";

    function doExecute( \imei_service\controller\Request $request ) {

        $login = $_POST['login'];
        $pass_u = $_POST['pass'];
        $pass = md5( $pass_u );
        $auto = $_POST['auto'];
        $ip = getIP();
        // Удаляем из таблицы IP, которые дольше 3 минут, проверяем наличие соответствия и количество ошибок текущего IP
        $loginOshibka = \imei_service\domain\LoginOshibka::find( $ip );
        $logPassExist = \imei_service\domain\Login::find( array($login, $pass ) );
        $codeActivation = $_POST['cAct'];
        $lgn = $_POST['lgn'];

        if( ! $codeActivation ) {

            if( $request->getProperty( 'submitted') !== 'yes' ) { // если форма не отправлена
    //            $this->error = 'error'; // присваиваем значение
    //            $request->addFeedback( "Заполните форму, чтобы закончить вход" ); // добавляем текст ошибки
                return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
            }
            if( is_object( $loginOshibka ) ) {
                 if( $loginOshibka->getCol() > 2 ) { // если col - количество неверно введенных раз больше 2
    //                 $this->error = "error"; // присваиваем значение
                     // добавляем текст ошибки
                     $request->addFeedback( 'Вы неверно ввели логин или пароль три раза подряд. Повторите попытку через 15 минут. ');
                     return self::statuses('CMD_INSUFFICIENT_DATA' ); // повторно открываем форму (вьюшку с формой)
                 }
            }
            if( empty( $login ) ) {
                $request->addFeedback( 'Введите логин' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( empty( $pass_u ) ) {
                $request->addFeedback( 'Введите пароль' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
            if( ! is_object( $logPassExist ) ) {
                $request->addFeedback( 'Неверный логин или пароль' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            } else {
                if( $logPassExist->getStatus() != 1 ) {
                    $request->addFeedback( 'Ваша учетная запись еще не активирована' );
                    return self::statuses( 'CMD_INSUFFICIENT_DATA' );
                }
            }

            if( isset( $auto ) ) {
                SessionRegistry::setSession( 'login', $login );
                SessionRegistry::setSession( 'pass', $pass );
                SessionRegistry::setSession( 'auto', 1 );

                setcookie( 'auto', 1, time()+9999999 );
                setcookie( 'login', $login, time()+9999999 );
                setcookie( 'pass', $pass_u, time()+9999999 );
            }

            return self::statuses( 'CMD_LOGIN_OK' );
        } else {


//            lgn и cAct используем, чтобы
//            UPDATE system_account SET status=1 WHERE login=$lgn AND activation=$cAct
//        выводим страничку с успешной активацией учетной записи и переадресуем на ?cmd=Login


        }
    }
}
?>