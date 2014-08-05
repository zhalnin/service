<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/08/14
 * Time: 12:53
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'AZ' ) ) die();
define( 'LoginAdmin', true );
//require_once( 'dmn/view/utils/security_mod.php' );
require_once( "dmn/command/Command.php" );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
require_once( 'dmn/domain/Accounts.php' );

class Login  extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";

        $text = "Поля, отмеченные звездочкой *, являются обязательными к заполнению";
        $form_comment = new \dmn\classes\FieldParagraph($text);

        $loginEnter = new \dmn\classes\FieldText("loginEnter",
            "Логин",
            true,
            $_REQUEST['loginEnter']);
        $passEnter = new \dmn\classes\FieldPassword("passEnter",
            "Пароль",
            true,
            $_REQUEST['passEnter'],
            255,
            41,
            "");
        $autoEnter           = new \dmn\classes\FieldCheckbox("autoEnter",
            "Запомнить меня",
            $_REQUEST['autoEnter']);
        $submitted      = new \dmn\classes\FieldHidden( "submitted",
            true,
            "yes" );
        $form = new \dmn\classes\Form(array( "form_comment" => $form_comment,
                "loginEnter"    => $loginEnter,
                "passEnter"     => $passEnter,
                "autoEnter"     => $autoEnter,
                "submitted"     => $submitted ),
            "Войти",
            "field");




        if( $_POST['submitted'] == 'yes' ) {

//            Проверка логина и пароля
            $error = $form->check(); // сохраняем в переменную массив сообщений об ошибках

            $accounts = \dmn\domain\Accounts::authUser( $form->fields['loginEnter']->value, md5( $form->fields['passEnter']->value ) );
            if( is_null( $accounts ) ) {
                $error[] = "Неверный логин или пароль!";
            }

            if( ! empty( $error ) ) { // если есть ошибки
                if( is_array( $error ) ) { // если это массив
                    foreach ( $error as $er ) { // проходим в цикле
                        $request->addFeedback( $er ); // добавляем сообщение об ошибке
                    }
                }
                $request->setObject('form', $form ); // выводим форму заново
//                echo "<tt><pre>".print_r( $error, true )."</pre></tt>";

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            } else {

                if( $form->fields['autoEnter']->value == 'on' ) {
                    $auto = 1;
                } else {
                    $auto = 0;
                }
                $login = $form->fields['loginEnter']->value;
                $pass = $form->fields['passEnter']->value;

                $accounts->setLastvisit( date( 'Y-m-d H:i:s', time() ) ); //  Обновление поля lastvisit

//            Сохранение в сессию

                \dmn\base\SessionRegistry::setSession( 'logina', $login ); // добавляем в сессию логин
                \dmn\base\SessionRegistry::setSession( 'passa', $pass ); // добавляем в сессию пароль
                \dmn\base\SessionRegistry::setSession( 'uida', $accounts->getId() ); // добавляем в сессию id пользователя
                \dmn\base\SessionRegistry::setSession( 'autoa', 1 ); // добавляем в сессию флаг для автоматического входа

//            Сохранение в куки
                if( $auto === 1 ) { // если был отмечен checkbox при входе "Запомнить меня", то устанавливаем куки

                    setcookie( 'logina', $login, time()+9999999 ); // для логина
                    setcookie( 'passa', md5( $pass ), time()+9999999 ); // для пароля
                    setcookie( 'autoa', 1, time()+9999999 ); // для автоматического входа
                }

//                echo "<tt><pre>".print_r($auto, true)."</pre></tt>";
                $this->reloadPage( 0, "dmn.php?cmd=News" ); // перегружаем страничку
//                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form );
        }
    }
}

?>