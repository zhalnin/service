<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 8/4/13
 * Time: 3:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\classes;
error_reporting( E_ALL & ~E_NOTICE );

require_once("imei_service/base/Registry.php" );
require_once( "imei_service/view/utils/getNameServer.php" );



abstract class Mail {


    protected function getStyle() {
        return "<style type='text/css'>
                    * {
            margin: 0;
            padding: 0;
        }
                    li {
            /*list-style-image: url(../dataimg/optdot_prd.gif);*/
            list-style-type: none;
                    }
                    .main_txt {
            font-family: Tahoma;
                        /*font-size: 11px;*/
                        text-align: justify;
                        /*text-indent: 50px;*/
                        /*color: #000000;*/
                        color: #666666;
                        font-size: 1em;
                        line-height: 1.5em;
                        padding-top: 5px;
                        padding-bottom: 5px;

                        margin-left: 30px;
                        margin-right: 30px;
                    }

                    .main_txt_lnk {
            color: #B20000;
            text-decoration: none;
                    }

                    .main_txt_lnk:hover {
            color: #00023E;
        }
                    #tp {
                        /*background-color: #ededed;
                        width: 648px;
                        height: 122px;
                        background-image: url(\"http://imei-service.ru/images/letter/top_apple.png\");*/
                    }
                    #mid {
                        background-color: #f1f1f1;
                        width: 630px;
                        margin-left: 9px;
                        text-align: center;
                        padding-bottom: 100px;
                        color: #000000;
                    }

                    #btm {
                       /* background-color: #ededed;
                        width: 630px;
                        height: 21px;
                        background-image: url(\"http://imei-service.ru/images/letter/btm.gif\");
                        margin-left: 9px;*/
                    }
                    h1 {
                        margin-bottom: 30px;
                    }

                    p {
                        margin-bottom: 18px;
                    }
                    .footer_txt {
                        font-family: Tahoma;
                        /*font-size: 11px;*/
                        text-align: justify;
                        text-indent: 20px;
                        /*color: #000000;*/
                        color: #666666;
                        font-size: 0.8em;
                        line-height: 1.5em;
                        padding-top: 5px;
                        padding-bottom: 5px;
                        margin-left: 50px;
                    }
                    .nested {
                        margin-left: 20px;
                    }
                    .nested li {
                        list-style-type: disc;
                    }
                    a {
                        color: #0088CC;
                        cursor: pointer;
                        text-decoration: none;
                    }
                    #container {
                        width: 650px;
                        margin: 30 auto;
                    }

                </style>";
    }
}


/**
 * Class AdminMail and ClientMail
 * для формирования отправки письма
 */
abstract class AdminMail extends Mail{
    protected $style;

    function email($email,$email_client,$imei=null,$udid=null,$operator=null,$type, $login=null, $activation=null ){
        $style = parent::getStyle();

        // Заявка на регистрацию UDID
        // Заявка на официальный анок
        // Заявка на проверку iPhone по IMEI
        // Заявка на проверку iPhone на blacklist

        if( ! is_null( $operator ) ) {
            $operatorDetails = "<p>Заявка отправлена из раздела '{$operator}'</p>";
        }

        // Формируем письмо
        $top = "
            <html>
                <head>
                <title>Обновление на сайте</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                <meta content=\"Делаем официальную отвязку от оператора iPhone, проверку по IMEI и на blacklist iPhone\" name=\"Description\">

                <body>
                    $style
                    <div id=\"container\">
                         <div id=\"tp\"></div>
                         <div id=\"mid\">
                             <h1>Обновление на сайте</h1>";

        $footer = "         <div id=\"slice\"></div>
                        </div>

                        <div id=\"btm\"></div>

                        <div class=\"footer_txt\">
                            <p>
                                <br/>
                                ------------------------------<br/>
                                <br/>
                                <br/>
                                <b>site:</b> <a href=\"http://imei-service.ru\">imei-service.ru</a><br/>
                                <b>email:</b> <a href=\"mailto:imei_service@icloud.com\">imei_service@icloud.com</a><br/>
                                <b>Skype:</b> <a href=\"skype:zhalnin78?add\">zhalnin78</a><br/>
                                <b>phone:</b> <a href=\"#\">+7(921)7451508</a><br/><br/>
                                <b><i>Алексей</i></b><br/>
                            </p>
                        </div>
                    </div>
                </body>
            </html>";

            switch($type){
                case 'udid':
                    $subject = "Поступила заявка на регистрацию UDID";
                    $subject_detail = "Для аппарата с UDID - $udid";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Ждем подтверждения платежа с <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'unlock':
                    $subject = "Поступила заявка на официальный анлок";
                    $subject_detail = "Для аппарата с IMEI - $imei";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                $operatorDetails
                                <p> Ждем подтверждения платежа с <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'carrier':
                    $subject = "Поступила заявка на проверку iPhone по IMEI";
                    $subject_detail = "Для аппарата с IMEI - $imei";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Ждем подтверждения платежа с <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'blacklist':
                    $subject = "Поступила заявка на blacklist iPhone";
                    $subject_detail = "Для аппарата с IMEI - $imei";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Ждем подтверждения платежа с <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'guestbook':
                    $subject = "Новый пост в гостевой книге";
                    $subject_detail = "";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Новое сообщение от пользователя $login с адресом email: <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'register':
                    $subject = "Новая регистрация на сайте imei-service.ru";
                    $subject_detail = "";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Новая регистрация от пользователя $login с адресом email: <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'flogin':
                    $subject = "Новый запрос на восстановление пароля";
                    $subject_detail = "";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Новый запрос на восстановление пароля от пользователя $login с адресом email: <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
                case 'ractivation':
                    $subject = "Новый запрос на повторную отправку кода активации";
                    $subject_detail = "";
                    $middle = "<div class=\"main_txt\">
                                <p>$subject</p>
                                <p>$subject_detail</p>
                                <p> Новый запрос от пользователя $login с адресом email: <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>";
                    $body = $top . $middle . $footer;
                    break;
            }


        $header = 'From: support@imei-service.ru' . "\r\n";
        $header .= 'Reply-To: support@imei-service.ru' . "\r\n";
        $header .= "Content-Type: text/html; charset=utf-8\r\n";
        $header .= "\r\n";
//        print "$body";
        @mail("$email",
            "=?utf-8?B?".base64_encode($subject)."?=",
            $body,
            $header);

    }
}
abstract class ClientMail extends Mail {
    protected $style;

    function email($email,$email_client,$imei=null,$udid=null,$operator=null,$type, $login=null, $activation ){
        $style = parent::getStyle();
        $nameServer = \imei_service\view\utils\getNameServer();
        // Формируем письмо
        $top = "
            <html>
                <head>
                <title>Обновление на сайте</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                <meta content=\"Делаем официальную отвязку от оператора iPhone, проверку по IMEI и на blacklist iPhone\" name=\"Description\">

                <body>
                $style
                    <div id=\"container\">
                        <div id=\"tp\"></div>
                        <div id=\"mid\">
                         <h1>Ваша заявка принята!</h1>
                         <h3>Благодарим вас за посещение нашего сайта!</h3>";

        
        $bottom =       "
                            <ul>
                                <li><ins>Webmoney</ins>
                                    <ul class=\"nested\">
                                        <li>R210243604114</li>
                                        <li>U224827413926</li>
                                        <li>Z231606126103</li>
                                    </ul>
                                </li>
                                <li><ins>yandex money</ins>
                                    <ul class=\"nested\">
                                        <li>410011463324480</li>
                                    </ul>
                                </li>
                                <li><ins>qiwi</ins>
                                    <ul class=\"nested\">
                                        <li>+79217451508</li>
                                    </ul>
                                </li>
                                <li><ins>paypal</ins>
                                    <ul class=\"nested\">
                                        <li>zhalninpal@me.com</li>
                                    </ul>
                                </li>
                                <li><ins>пополнить мобильный счет</ins>
                                    <ul class=\"nested\">
                                        <li>+7(921)745-15-08(Северо-Западный Мегафон)</li>
                                    </ul>
                                </li>
                            </ul>
                            <br/>
                            <p>Комиссии по переводу денежных средств оплачиваете Вы. <br />После оплаты обязательно ответьте на email: <a href=\"mailto:$email\">$email</a> с указанием способа оплаты, подтверждением (скан чека, номер транзакции)</p><br/><br/>";
        $footer =
                        "</div>
                        <div id=\"slice\"></div>
                    </div>

                        <div id=\"btm\"></div>
                        <div class=\"footer_txt\">
                            <p>
                                <br/>
                                ------------------------------<br/>
                                <br/>
                                <br/>
                                <b>site:</b> <a href=\"http://imei-service.ru\">imei-service.ru</a><br/>
                                <b>email:</b> <a href=\"mailto:imei_service@icloud.com\">imei_service@icloud.com</a><br/>
                                <b>Skype:</b> <a href=\"skype:zhalnin78?add\">zhalnin78</a><br/>
                                <b>phone:</b> <a href=\"#\">+7(921)7451508</a><br/><br/>
                                <b><i>Алексей</i></b><br/>
                            </p>
                        </div>
                    </div>
                </body>
            </html>";

        switch($type){
            case 'udid':
                $subject = "Регистрация UDID";
               
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на регистрацю UDID </p>
                             <h3>Если вы не оставляли заявку на сайте , то просто проигнорируйте это сообщение!</h3>
                             <p>Вам следует оплатить услугу регистрации аппарата в аккаунте разработчика для UDID - $udid </p>
                             <p>Которая вам обойдется в 200 рублей, <br/>
                             регистрация займет несколько минут. <br />
                             Оплатить вы можете любым из следующих способов:</p>";
                $body = $top . $middle . $bottom . $footer;
                break;
            case 'unlock':
                $subject = "Официальный анлок";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на официальный анлок </p>
                             <p> Мы уточним возможность официального анлока для аппарата с IMEI - $imei и вышлем вам письмо
                             с указанием стоимости, сроков работы и реквизиты для оплаты</p>";
                $body = $top . $middle . $footer;
                break;
            case 'carrier':
                $subject = "Проверка iPhone по IMEI";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на проверку iPhone по IMEI </p>
                             <h3>Если вы не оставляли заявку на сайте , то просто проигнорируйте это сообщение!</h3>
                             <p>Вам следует оплатить услугу проверки iPhone для IMEI - $imei </p>
                             <p>Которая вам обойдется в 30 рублей. Оплатить вы можете любым из следующих способов:</p>";
                $body = $top . $middle . $bottom . $footer;
                break;
            case 'blacklist':
                $subject = "Проверка iPhone на blacklist";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на проверку iPhone на blacklist </p>
                             <h3> Если вы не оставляли заявку на сайте , то просто проигнорируйте это сообщение!</h3>
                             <p> Вам следует оплатить услугу проверки iPhone для IMEI - $imei </p>
                             <p> Которая вам обойдется в 70 рублей. Оплатить вы можете любым из следующих способов:</p>";
                $body = $top . $middle . $bottom . $footer;
                break;
            case 'guestbook':
                $subject = "Пост в гостевой книге";
                $middle = "<div class=\"main_txt\">
                             <p> Уважаемый, $login, спасибо за потраченное время, чтобы оставить свое сообщение в Гостевой книге </p>
                             <p> Ваш адрес электронной почты $email_client был указан при отправке сообщения </p>
                             <h3> Ваш адрес электронной почты останется скрыт, нужен только для подтверждения вашей гуманности </h3>
                             <p> Хотелось бы напомнить, что в гостевой книге вы можете также задать вопрос, </p>
                             <p> который будет укладываться в контекст нашего сайта.</p>
                             <p> Постараемся подсказать вам решение вашей проблемы! </p>";
                $body = $top . $middle . $footer;
                break;
            case 'register':
                $subject = "Подтверждение регистрации на сайте imei-service.ru";
                $middle = "<div class=\"main_txt\">
                             <p> Спасибо, что нашли время зарегистрироваться на нашем сайте </p>
                             <p> Ваш адрес электронной почты $email_client был указан при регистрации на сайте imei-servcie.ru </p>
                             <h3> Если вы не регистрировались на сайте , то просто проигнорируйте это сообщение! </h3>
                             <p> Для завершения регистрации и активации учетной записи пройдите, пожалуйста, по ссылке: </p>
                             <p> $nameServer?cmd=Activation&lgn=$login&cAct=$activation</p>
                             <p> После успешной активации вашей учетной записи вы можете зайти на сайт под своим именем! </p>";
                $body = $top . $middle . $footer;
                break;
            case 'flogin':
                $subject = "Повторная отправка логина и пароля на сайте imei-service.ru";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш запрос на восстановление пароля на сайте imei-server.ru был успешно выполнен!</p>
                             <p> Ваш адрес электронной почты $email_client был указан при запросе восстановления пароля на сайте imei-servcie.ru </p>
                             <h3> Если вы не регистрировались на сайте , то просто проигнорируйте это сообщение! </h3>
                             <p><ul>
                                <li>Ваш логин: $login</li>
                                <li>Ваш пароль: $activation</li>
                             </ul></p>
                             <p> Используя ваш логин: $login и пароль: $activation вы можете зайти на сайт: </p>
                             <p><a href=\"$nameServer?cmd=Login\">Войти на сайт</a> под своим именем</p>";
                $body = $top . $middle . $footer;
                break;
            case 'ractivation':
                $subject = "Повторная отправка кода активации регистрации на сайте imei-service.ru";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш запрос на повторную отправку кода активации регистрации на сайте imei-server.ru был успешно выполнен! </p>
                             <p> Ваш адрес электронной почты $email_client был указан при запросе повторной отправки кода активации регистрации на сайте imei-servcie.ru </p>
                             <h3> Если вы не регистрировались на сайте , то просто проигнорируйте это сообщение! </h3>
                             <p> Для завершения регистрации и активации учетной записи пройдите, пожалуйста, по ссылке: </p>
                             <p> $nameServer?cmd=Activation&lgn=$login&cAct=$activation</p>
                             <p> После успешной активации вашей учетной записи вы можете зайти на сайт под своим именем! </p>";
                $body = $top . $middle . $footer;
                break;

        }
        $header = 'From: support@imei-service.ru' . "\r\n";
        $header .= 'Reply-To: support@imei-service.ru' . "\r\n";
        $header .= "Content-Type: text/html; charset=utf-8\r\n";
        $header .= "\r\n";
//        print $body;
        @mail("$email_client",
            "=?utf-8?B?".base64_encode($subject)."?=",
            $body,
            $header);
    }
}





/**
 * Class BlacklistAdmin and BlacklistClient
 * Отправляем письма об услуге Blacklist - проверка на черный список
 */
class BlacklistAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );

    }
}
class BlacklistClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );

    }
}


/**
 * Class CarrierAdmin and CarrierClient
 * Отправляем письма об услуге Carrier - проверка на оператора
 */
class CarrierAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}
class CarrierClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}


/**
 * Class CarrierAdmin and CarrierClient
 * Отправляем письма об услуге Carrier - проверка на оператора
 */
class UnlockAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );

    }
}
class UnlockClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}


/**
 * Class CarrierAdmin and CarrierClient
 * Отправляем письма об услуге Carrier - проверка на оператора
 */
class UdidAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}
class UdidClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}

/**
 * Class GuestbookAdmin and GuestbookClient
 * Отправляем письма об услуге Guestbook - пост в гостевой книге
 */
class GuestbookAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}
class GuestbookClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}


/**
 * Class RegisterAdmin and RegisterClient
 * Отправляем письма необходимости активации учетной записи и уведомлении о таковой  - регистрация на сайте
 */
class RegisterAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}
class RegisterClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}


/**
 * Class RegisterAdmin and RegisterClient
 * Отправляем письма необходимости активации учетной записи и уведомлении о таковой  - регистрация на сайте
 */
class FLoginAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}
class FLoginClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}


/**
 * Class RegisterAdmin and RegisterClient
 * Отправляем письма необходимости активации учетной записи и уведомлении о таковой  - регистрация на сайте
 */
class RActivationAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login=null, $activation=null ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}
class RActivationClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation ){

        parent::email($email,$email_client,$imei,$udid,$operator,$type, $login, $activation );
    }
}




/**
 * Class CommsManager
 */
abstract class CommsManager {
    const ADMIN = 1;
    const CLIENT = 2;

    abstract function make( $type );

}

/**
 * Class BlacklistCommsManager
 * для выбора, кому отправить письмо по услуге черного списка
 */
class BlacklistCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new BlacklistAdmin();
            case self::CLIENT:
                return new BlacklistClient();
        }
    }
}

/**
 * Class CarrierCommsManager
 * для выбора, кому отправить письмо по услуге проверки на оператора
 */
class CarrierCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new CarrierAdmin();
            case self::CLIENT:
                return new CarrierClient();
        }
    }
}


/**
 * Class UnlockCommsManager
 * для выбора, кому отправить письмо по услуге официального анлока
 */
class UnlockCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new UnlockAdmin();
            case self::CLIENT:
                return new UnlockClient();
        }
    }
}


/**
 * Class UdidCommsManager
 * для выбора, кому отправить письмо по услуге регистрации UDID
 */
class UdidCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new UdidAdmin();
            case self::CLIENT:
                return new UdidClient();
        }
    }
}


/**
 * Class GuestCommsManager
 * для выбора, кому отправить письмо после публикации сообщения в гостевой книге
 */
class GuestbookCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new GuestbookAdmin();
            case self::CLIENT:
                return new GuestbookClient();
        }
    }
}


/**
 * Class RegisterCommsManager
 * для выбора, кому отправить письмо после регистрации
 */
class RegisterCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new RegisterAdmin();
            case self::CLIENT:
                return new RegisterClient();
        }
    }
}


/**
 * Class FLoginCommsManager
 * для повторной отправки логина и пароля
 */
class FLoginCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new FLoginAdmin();
            case self::CLIENT:
                return new FLoginClient();
        }
    }
}


/**
 * Class RActivationCommsManager
 * для повторной отправки кода активации учетной записи
 */
class RActivationCommsManager extends CommsManager {

    function make( $type ){
        switch( $type ){
            case self::ADMIN:
                return new RActivationAdmin();
            case self::CLIENT:
                return new RActivationClient();
        }
    }
}




/**
 * Class MailConfig
 * Выбираем CommsManager для вида услуг
 */
class MailConfig {
    private static $instance;



    private function __construct(){}

    private static function instance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function getCommsManager( $type ) {

        switch( $type ){
            case 'blacklist':
                return new BlacklistCommsManager();
                break;
            case 'carrier':
                return new CarrierCommsManager();
                break;
            case 'unlock':
                return new UnlockCommsManager();
                break;
            case 'udid':
                return new UdidCommsManager();
                break;
            case 'guestbook':
                return new GuestbookCommsManager();
                break;
            case 'register':
                return new RegisterCommsManager();
                break;
            case 'flogin':
                return new FLoginCommsManager();
                break;
            case 'ractivation':
                return new RActivationCommsManager();
                break;
        }
    }

    static function get( $type ) {
        return self::instance()->getCommsManager( $type );
    }
}
?>