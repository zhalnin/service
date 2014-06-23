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

    function doExecute( \imei_service\controller\Request $request ) {

        $login = $_POST['login']; // получаем логин из формы
        $pass_u = $_POST['pass']; // получаем пароль из формы
        $pass = md5( $pass_u ); // шифруем пароль для добавления в БД
        $auto = $_POST['auto']; // получаем значение чекбокса "Запомнить меня" из формы
        $ip = getIP(); // запустив функцию, получаем IP адрес посетителя
        // Удаляем из таблицы IP, которые дольше 3 минут, проверяем наличие соответствия и количество ошибок текущего IP
        $loginOshibka = \imei_service\domain\LoginOshibka::find( $ip );
        $logPassExist = \imei_service\domain\Login::find( array($login, $pass ) ); // проверяем наличие логина и пароля

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
            $request->addFeedback( 'Введите логин' ); // поле логина пустое
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $pass_u ) ) {
            $request->addFeedback( 'Введите пароль' ); // поле пароля пустое
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( ! is_object( $logPassExist ) ) { // если вход не успешный, то в таблицу ошибок добавляем 1
            $request->addFeedback( 'Неверный логин или пароль' );

            if( !is_object( $loginOshibka ) ) {  // если гость с данного IP еще не ошибался, то таблица в БД пустая
                $insertOshibka = new \imei_service\domain\LoginOshibka();  // создаем объект для (INSERT) добавления новых данных об ошибке в таблицу БД
                $insertOshibka->setIp( $ip ); // добавляем IP
                $insertOshibka->setDate( date( 'Y-m-d H:i:s' ) ); // добавляем текущую дату и время
                $insertOshibka->setCol( 1 ); // добавляем одну ошибку
            } else { // если хотя бы одна ошибка уже есть в таблице БД
                $count = $loginOshibka->getCol(); // получаем количество ошибок в таблице БД
                $count++; // добавляем 1 к общему числу ошибок в таблице БД
                $loginOshibka->setCol( $count ); // добавляем количество ошибок к объекту
                $insertOshibka = new \imei_service\domain\LoginOshibka( $loginOshibka->getId() ); // получаем id записи в таблице БД и на его основе делаем запрос (UPDATE) с обновлением нового значения ошибок
            }
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        } else {
            if( $logPassExist->getStatus() != 1 ) { // если учетная запись не активирована (status=0)
                $request->addFeedback( 'Ваша учетная запись еще не активирована: <a href="?RActivation">Повторно выслать письмо для активации учетной записи.</a>' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }
        }

        SessionRegistry::setSession( 'login', $login ); // добавляем в сессию логин
        SessionRegistry::setSession( 'pass', $pass ); // добавляем в сессию пароль
        SessionRegistry::setSession( 'auto', 1 ); // добавляем в сессию флаг для автоматического входа

        if( isset( $auto ) ) { // если был отмечен checkbox при входе "Запомнить меня", то устанавливаем куки

            setcookie( 'login', $login, time()+9999999 ); // для логина
            setcookie( 'pass', $pass_u, time()+9999999 ); // для пароля
            setcookie( 'auto', 1, time()+9999999 ); // для автоматического входа
        }
        return self::statuses( 'CMD_LOGIN_OK' );
    }
}
?>