<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 19:24
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'Users' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
require_once( 'dmn/view/utils/password.php' );
require_once( 'dmn/domain/Users.php' );


class UsersAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $page = intval( $request->getProperty( 'page' ) );
        $passExample = \dmn\view\utils\generatePassword();
        // Отмечен флажок block
        if(empty($_POST)) $_REQUEST['block'] = false;

        $text = "Поля, отмеченные звездочкой *, являются обязательными к заполнению";
        $form_comment = new \dmn\classes\FieldParagraph($text);
        $fio = new \dmn\classes\FieldText("fio",
            "ФИО",
            false,
            $_REQUEST['fio']);
        $city = new \dmn\classes\FieldText("city",
            "Город",
            false,
            $_REQUEST['city']);
        $email = new \dmn\classes\FieldTextEmail("email",
            "E-mail",
            true,
            $_REQUEST['email']);
        $url = new \dmn\classes\FieldText("url",
            "Сайт",
            false,
            $_REQUEST['url']);
        $login = new \dmn\classes\FieldText("login",
            "Ник",
            true,
            $_REQUEST['login']);
        $activation = new \dmn\classes\FieldText("activation",
            "Код активации",
            false,
            $_REQUEST['activation']);
        $status = new \dmn\classes\FieldCheckbox("status",
            "Активировать",
            $_REQUEST['status']);
        $pass = new \dmn\classes\FieldPassword("pass",
            "Пароль",
            true,
            $_REQUEST['pass'],
            255,
            41,
            "",
            "Например: $passExample");
        $passagain = new \dmn\classes\FieldPassword("passagain",
            "Повтор",
            true,
            $_REQUEST['passagain'],
            255,
            41,
            "",
            "Например: $passExample");
        $putdate = new \dmn\classes\FieldDatetime("putdate",
            "Дата регистрации",
            $_REQUEST['putdate']);
        $lastvisit  = new \dmn\classes\FieldDatetime("lastvisit",
            "Дата последнего визита",
            $_REQUEST['lastvisit']);
        $block = new \dmn\classes\FieldCheckbox("block",
            "Блокировать",
            $_REQUEST['block']);
        $page = new \dmn\classes\FieldHiddenInt("page",
            false,
            $_REQUEST['page']);
        $submitted      = new \dmn\classes\FieldHidden( "submitted",
            true,
            "yes" );
        $form = new \dmn\classes\Form(array( "form_comment" => $form_comment,
                                            "fio"           => $fio,
                                            "city"          => $city,
                                            "email"         => $email,
                                            "url"           => $url,
                                            "login"         => $login,
                                            "activation"    => $activation,
                                            "status"        => $status,
                                            "pass"          => $pass,
                                            "passagain"     => $passagain,
                                            "putdate"       => $putdate,
                                            "lastvisit"     => $lastvisit,
                                            "block"         => $block,
                                            "page"          => $page,
                                            "submitted"     => $submitted ),
                                        "Добавить",
                                        "field");

        if( $_POST['submitted'] == 'yes' ) {
            if($form->fields['pass']->value != $form->fields['passagain']->value) {
                $error[] = "Пароли не равны";
            }
            $error = $form->check(); // сохраняем в переменную массив сообщений об ошибках

            $accountsCount = \dmn\domain\Users::findCountPos( $form->fields['login']->value );
            if( is_array( $accountsCount ) ) {
                if( $accountsCount['count'] >= 1 ) {
                    $error[] = "Пользователь с именем
                        <b>{$form->fields['login']->value}</b> уже
                        зарегистрирован";
                }
            }

            $emailCount = \dmn\domain\Users::findCountPos( $form->fields['email']->value );
            if( is_array( $emailCount ) ) {
                if( $emailCount['count'] >= 1 ) {
                    $error[] = "Пользователь с таким email:
                        <b>{$form->fields['email']->value}</b> уже
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

                // Выясняем, скрыта или открыта дректория
                if($form->fields['block']->value) {
                    $blockUnblock = "block";
                } else {
                    $blockUnblock = "unblock";
                }

                // Выясняем, скрыта или открыта дректория
                if($form->fields['status']->value) {
                    $statusUn = "1";
                } else {
                    $statusUn = "0";
                }

                // получаем объект Users без id - значит будет INSERT
                $userObj = new \dmn\domain\Users();

                // устанавливаем имя
                $userObj->setFio( $form->fields['fio']->value );
                // устанавливаем город
                $userObj->setCity( $form->fields['city']->value );
                // устанавливаем email
                $userObj->setEmail( $form->fields['email']->value );
                // устанавливаем сайт
                $userObj->setUrl( $form->fields['url']->value );
                // устанавливаем логин
                $userObj->setLogin( $form->fields['login']->value );
                // устанавливаем код активации
                $userObj->setActivation( $form->fields['activation']->value );
                // устанавливаем статус
                $userObj->setStatus( $statusUn );
                // устанавливаем пароль
                $userObj->setPass( md5( $form->fields['pass']->value ) );
                // устанавливаем дату регистрации
                $userObj->setPutdate( $form->fields['putdate']->getMysqlFormat() );
                // устанавливаем дату последнего визита
                $userObj->setLastvisit( $form->fields['lastvisit']->getMysqlFormat() );
                // устанавливаем флаг блокировки
                $userObj->setBlock( $blockUnblock);
//
//                echo "<tt><pre>".print_r($form, true)."</pre></tt>";
                $this->reloadPage( 0, "dmn.php?cmd=Users&page=" ); // перегружаем страничку
//                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form );
        }

    }

}