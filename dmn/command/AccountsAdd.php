<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 15:12
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'Accounts' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
require_once( 'dmn/view/utils/password.php' );
require_once( 'dmn/domain/Accounts.php' );


class AccountsAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $passExample = \dmn\view\utils\generatePassword();
        $name = new \dmn\classes\FieldTextEnglish("name",
            "Имя пользователя",
            true,
            $_POST['name']);
        $pass = new \dmn\classes\FieldPassword("pass",
            "Пароль",
            true,
            $_POST['pass'],
            255,
            41,
            "",
            "Например, $passExample");
        $passag = new \dmn\classes\FieldPassword("passag",
            "Повтор пароля",
            true,
            $_POST['passag'],
            255,
            41,
            "",
            "Например, $passExample");
        $page = new \dmn\classes\FieldHiddenInt("page",
            false,
            $_REQUEST['page']);
        $submitted      = new \dmn\classes\FieldHidden( "submitted",
            true,
            "yes" );
        $form = new \dmn\classes\Form(array("name"      => $name,
                                            "pass"      => $pass,
                                            "passag"    => $passag,
                                            "page"      => $page,
                                            "submitted" => $submitted ),
                                        "Добавить",
                                        "field");

        if( $_POST['submitted'] == 'yes' ) {
            if($form->fields['pass']->value != $form->fields['passag']->value) {
                $error[] = "Пароли не равны";
            }
            $error = $form->check(); // сохраняем в переменную массив сообщений об ошибках

            $accountsCount = \dmn\domain\Accounts::findCountPos( $form->fields['name']->value );
            if( is_array( $accountsCount ) ) {
                if( $accountsCount['count'] >= 1 ) {
                    $error[] = "Пользователь с именем
                        <b>{$form->fields['name']->value}</b> уже
                        зарегистрирован";
                }
            }
//            echo "<tt><pre>".print_r( is_array( $accountsCount ), true )."</pre></tt>";

            if( ! empty( $error ) ) { // если есть ошибки
                if( is_array( $error ) ) { // если это массив
                    foreach ( $error as $er ) { // проходим в цикле
                        $request->addFeedback( $er ); // добавляем сообщение об ошибке
                    }
                }
                $request->setObject('form', $form ); // выводим форму заново

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            } else {

                // получаем объект News без id - значит будет INSERT
                $accountObj = new \dmn\domain\Accounts();

                // устанавливаем имя
                $accountObj->setName( $form->fields['name']->value );
                // устанавливаем пароль
                $accountObj->setPass( md5( $form->fields['pass']->value ) );

                $this->reloadPage( 0, "dmn.php?cmd=Accounts" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form );
        }

    }

} 