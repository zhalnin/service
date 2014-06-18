<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:12
 */

namespace imei_service\command;

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Guestbook.php" );
require_once( "imei_service/view/utils/getIP.php" );
require_once( "imei_service/view/utils/getVerBrowser.php" );

session_start();

class Guestbook extends Command {
    function doExecute( \imei_service\controller\Request $request ) {

        $valid = $request->getProperty( 'valid' );
        $page = $request->getProperty( 'page' );
        if( ! $page ) {
            $page = 1;
        }
        $pagination = \imei_service\domain\Guestbook::paginationMysql( $page );
//        $pagination = \imei_service\domain\Guestbook::findAll();
        $request->setObject('guestbook_pagination', $pagination);

        if( ! empty( $valid ) ) {

            $ip                 = getIP();
            $browser            = getVerBrowser();
            $sid_add_message    = $request->getProperty('sid_add_message');
            $name               = $request->getProperty('name');
            $city               = $request->getProperty('city');
            $email              = $request->getProperty('email');
            $url                = $request->getProperty('url');
            $message            = $request->getProperty('message');
            $answer             = $request->getProperty('answer');
            $putdate            = $request->getProperty('putdate');
            $hide               = $request->getProperty('hide');
            $id_parent          = $request->getProperty('idp');
            $code               = $request->getProperty('code');
            $codeConfirm        = $request->getProperty('codeConfirm');
            $page               = $request->getProperty('page');


            if( $sid_add_message != session_id() ) {
                $error = 'error';
                $request->addFeedback( "Попробуйте отправить форму заново" );
            }
            if( empty( $name ) ) {
                $error = 'error';
                $request->addFeedback( "Необходимо заполнить поле: Имя" );
            }
            if( empty( $email ) ) {
                $error = 'error';
                $request->addFeedback( "Необходимо заполнить поле: E-mail" );
            } elseif ( ! preg_match('|^[-a-z0-9_+.]+\@(?:[-a-z0-9.]+\.)+[a-z]{2,6}$|i', $email ) ) {
                $error = 'error';
                $request->addFeedback( "Введите ваш действительный E-mail" );
            }
            if( $_SESSION['code'] != $code ) {
                $error = 'error';
                $request->addFeedback( "Указанный код с картинки неверный" );
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

            if( empty( $error ) ) {
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
    //                        echo "<tt><pre>".print_r($guestbook_obj, true)."</pre></tt>";
                $request->setObject('guestbook', $guestbook_obj );

                return self::statuses( 'CMD_OK' );

            } else {
                return self::statuses( 'CMD_INSUFFICIENT_DATA' );
            }

        }
    }
}
