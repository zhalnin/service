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

session_start();

class Guestbook extends Command {
    function doExecute( \imei_service\controller\Request $request ) {


//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $valid = $request->getProperty( 'valid' );




            $page = $request->getProperty( 'page' );
            if( ! $page ) {
                $page = 1;
            }
            $pagination = \imei_service\domain\Guestbook::paginationMysql( $page );
    //        $pagination = \imei_service\domain\Guestbook::findAll();
            $request->addFeedback( "Welcome to Guestbook IMEI-SERVICE");

            $guestbook = $request->setObject('guestbook_pagination', $pagination);

//            return self::statuses( 'CMD_OK' );

        if( ! empty( $valid ) ) {

            $sid_add_message = $request->getProperty('sid_add_message');
            $name = $request->getProperty('name');
            $city = $request->getProperty('city');
            $email = $request->getProperty('email');
            $url = $request->getProperty('url');
            $message = $request->getProperty('message');
            $answer = $request->getProperty('answer');
            $putdate = $request->getProperty('putdate');
            $hide = $request->getProperty('hide');
            $id_parent = $request->getProperty('id_parent');
            $ip = $request->getProperty('ip');
            $browser = $request->getProperty('browser');
            $code = $request->getProperty('code');
            $codeConfirm = $request->getProperty('codeConfirm');
            $page = $request->getProperty('page');



            if( empty( $sid_add_message ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Попробуйте отправить форму заново</li>";
            }
            if( empty( $name ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: Имя</li>";
            }
            if( empty( $email ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Необходимо заполнить поле: E-mail</li>";
            } elseif ( ! preg_match('|^[-a-z0-9_+.]+\@(?:[-a-z0-9.]+\.)+[a-z]{2,6}$|i', $email ) ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Введите ваш действительный E-mail</li>";
            }
            if( $_SESSION['code'] != $code ) {
                $valid = "";
                $error .= "<li style='color: rgb(255, 0, 0);'>Указанный код с картинки неверный</li>";
            }
//            if( isset( $id_parent_post ) ) {
//                $id_parent = htmlspecialchars( stripslashes( $id_parent_post ), ENT_QUOTES );
//            }
//            if( isset( $_GET['id_parent'] ) ) {
//                $id_parent = htmlspecialchars( stripslashes( $_GET['id_parent'] ), ENT_QUOTES );
//            }


//            echo "<tt><pre>".print_r($request, true)."</pre></tt>";



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
                print "no errors";
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
//                if( $sendmail === true ) {
                    $to = 'zhalninpal@me.com';
                    $subject = 'Новый пост в адресной книге';
                    $body = "Поступило новое сообщение, которое следует проверить\n";
                    $body .= "От пользователя: $name\n";
                    $body .= "Адрес email: $email\n";
                    $header = "From: zhalnin@mail.com\r\n";
                    $header .= "Reply-to: zhalnin@mail.com \r\n";
                    $header .= "Content-type: text/plane; charset=utf-8\r\n";
//                    mail($to,$subject,$body,$header);
//                }



                return self::statuses( 'CMD_OK' );

            } else {
                echo "<tt><pre>".print_r($error, true)."</pre></tt>";
                print "errors";

            }
        }
    }
}
