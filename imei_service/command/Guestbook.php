<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:12
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Guestbook.php" );
require_once( "imei_service/view/utils/getIP.php" );
require_once( "imei_service/view/utils/getVerBrowser.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/domain/Login.php" );


use imei_service\base\SessionRegistry;

class Guestbook extends Command {
    function doExecute( \imei_service\controller\Request $request ) {








        $enter = SessionRegistry::getSession('auto');
        $login = SessionRegistry::getSession('login');
        $pass = SessionRegistry::getSession('pass');










        $valid = $request->getProperty( 'valid' );
        $page = $request->getProperty( 'page' );
        if( ! $page ) {
            $page = 1;
        }
        $pagination = \imei_service\domain\Guestbook::paginationMysql( $page );
//        $pagination = \imei_service\domain\Guestbook::findAll();
        $request->setObject('guestbook_pagination', $pagination);

        if( ! empty( $valid ) ) {

            if( ! empty( $login ) && ! empty( $pass ) ) {
                $logPassExist = \imei_service\domain\Login::find( array($login, $pass ) );
                if( is_object( $logPassExist ) ) {
                    $name = $logPassExist->getFio();
                    $city = $logPassExist->getCity();
                    $email = $logPassExist->getEmail();
                }
            } else {
                $name               = $request->getProperty('name');
                $city               = $request->getProperty('city');
                $email              = $request->getProperty('email');
                $url                = $request->getProperty('url');
            }

            $ip                 = getIP();
            $browser            = getVerBrowser();
            $sid_add_message    = $request->getProperty('sid_add_message');
            $message            = $request->getProperty('message');
            $answer             = $request->getProperty('answer');
            $putdate            = $request->getProperty('putdate');
            $hide               = $request->getProperty('hide');
            $id_parent          = $request->getProperty('idp');
            $code               = $request->getProperty('code');
            $codeConfirm        = $request->getProperty('codeConfirm');
            $page               = $request->getProperty('page');


            if( $sid_add_message != session_id() ) {
                $request->addFeedback( 'Заполните необходимые поля еще раз' );
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }

            if( empty( $login ) && empty( $pass ) ) {
                if( empty( $name ) ) {
                    $request->addFeedback( 'Необходимо заполнить поле: "Имя"' );
                    return self::statuses( 'CMD_INSUFFICIENT_DATA' );
                }
                if( empty( $email ) ) {
                    $request->addFeedback( 'Заполните поле "Email"' );
                    return self::statuses( 'CMD_INSUFFICIENT_DATA' );
                } elseif ( ! preg_match('|^[-a-z0-9_+.]+\@(?:[-a-z0-9.]+\.)+[a-z]{2,6}$|i', $email ) ) {
                    $request->addFeedback( "Введите корректный email" );
                    return self::statuses( 'CMD_INSUFFICIENT_DATA' );
                }
                if( $_SESSION['code'] != $code ) {
                    $request->addFeedback( "Неверно введен защитный код" );
                    return self::statuses( 'CMD_INSUFFICIENT_DATA' );
                }
            }
            if( empty( $id_parent )  ) {
                $id_parent = 0;
            }
            if( empty( $answer ) ) {
                $answer = '-';
            }
            if( empty( $hide ) ) {
                $hide = 'show';
            }
            if( empty( $putdate ) ) {
                $time = new \DateTime;
                $putdate = $time->format('Y-m-d H:i:s');
            }
            if( empty( $page ) ) {
                $page = 1;
            }

            // создается объект в domain, если успешно - в control отрабатывает \domain\ObjectWatcher->performOperations()
            $guestbook_obj = new \imei_service\domain\Guestbook( null,
                                                                $name,
                                                                $city,
                                                                $email,
                                                                $url,
                                                                $message,
                                                                $answer,
                                                                $putdate,
                                                                $hide,
                                                                $id_parent,
                                                                $ip,
                                                                $browser );
            $request->setObject('guestbook', $guestbook_obj );

            // Закомментированные строки будут работать, если в xml в блок с Guestbook добавить вызов вьюшки по статусу
            // на данный момент сабмитится форма по JS и просто открываем скрипт с успешным постингом
            // Если учетные данные добавлены успешно ( будет создан объект, если не будет добавлена, то объект не будет создан )
            if( is_object( $guestbook_obj ) ) {
                $commsManager = \imei_service\classes\MailConfig::get( 'guestbook' );  // параметр - тип commsManager
                $commsManager->make(1)->email( $email, 'imei_service@icloud.com', null, null, null, 'guestbook', $name, null ); // отправляем письмо админу
                $commsManager->make(2)->email( $email, 'imei_service@icloud.com', null, null, null, 'guestbook', $name, null ); // отправляем письмо клиенту
            }
            // если данные добавлены, то по возвращаемому статусу открываем addMessageSuccess.php
            return self::statuses( 'CMD_GUESTBOOK_OK' );
        }
    }
}
?>