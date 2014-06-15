<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 8/4/13
 * Time: 3:20 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Mail {


    function getStyle() {
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

    function email($email,$email_client,$imei,$udid,$type){
        $style = parent::getStyle();

        switch($type){
            case 'udid':
                $subject = "Заявка на регистрацию UDID";
                $subject_detail = "Для аппарата с UDID - $udid";
                break;
            case 'unlock':
                $subject = "Заявка на официальный анлок";
                $subject_detail = "Для аппарата с IMEI - $imei";
                break;
            case 'carrier':
                $subject = "Заявка на проверку iPhone по IMEI";
                $subject_detail = "Для аппарата с IMEI - $imei";
                break;
            case 'blacklist':
                $subject = "Заявка на blacklist iPhone";
                $subject_detail = "Для аппарата с IMEI - $imei";
                break;
        }
        // Заявка на регистрацию UDID
        // Заявка на официальный анок
        // Заявка на проверку iPhone по IMEI
        // Заявка на проверку iPhone на blacklist

        // Формируем письмо
        $body = "
            <html>
                <head>
                <title>Новая заявка</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                <meta content=\"Делаем официальную отвязку от оператора iPhone, проверку по IMEI и на blacklist iPhone\" name=\"Description\">

                <body>
                    $style
                    <div id=\"container\">
                         <div id=\"tp\"></div>
                         <div id=\"mid\">
                             <h1>Поступила новая заявка</h1>
                             <div class=\"main_txt\">
                                <p>Была сделана $subject </p>
                                <p>$subject_detail</p>
                                <p> Ждем подтверждения с <a href=\"mailto:$email_client\">$email_client</a> </p>
                             </div>
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
    function email($email,$email_client,$imei,$udid,$type){
        $style = parent::getStyle();
        // Формируем письмо
        $top = "
            <html>
                <head>
                <title>Новая заявка</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                <meta content=\"Делаем официальную отвязку от оператора iPhone, проверку по IMEI и на blacklist iPhone\" name=\"Description\">

                <body>
                $style
                    <div id=\"container\">
                        <div id=\"tp\"></div>
                        <div id=\"mid\">
                         <h1>Ваша заявка принята!</h1>
                         <h3>Благодарим вас за использование нашего сервиса!</h3>";

        
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
                                        <li>+7(921)7451508(Северо-Западный Мегафон)</li>
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
                $subject = "Заявка на регистрацию UDID";
               
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
                $subject = "Заявка на официальный анлок";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на официальный анлок </p>
                             <p> Мы уточним возможность официального анлока для аппарата с IMEI - $imei и вышлем вам письмо
                             с указанием стоимости, сроков работы и реквизиты для оплаты</p>";
                $body = $top . $middle . $footer;
                break;
            case 'carrier':
                $subject = "Заявка на проверку iPhone по IMEI";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на проверку iPhone по IMEI </p>
                             <h3>Если вы не оставляли заявку на сайте , то просто проигнорируйте это сообщение!</h3>
                             <p>Вам следует оплатить услугу проверки iPhone для IMEI - $imei </p>
                             <p>Которая вам обойдется в 30 рублей. Оплатить вы можете любым из следующих способов:</p>";
                $body = $top . $middle . $bottom . $footer;
                break;
            case 'blacklist':
                $subject = "Заявка на blacklist iPhone";
                $middle = "<div class=\"main_txt\">
                             <p> Ваш адрес электронной почты $email_client был указан в заявке на проверку iPhone на blacklist </p>
                             <h3>Если вы не оставляли заявку на сайте , то просто проигнорируйте это сообщение!</h3>
                             <p>Вам следует оплатить услугу проверки iPhone для IMEI - $imei </p>
                             <p>Которая вам обойдется в 70 рублей. Оплатить вы можете любым из следующих способов:</p>";
                $body = $top . $middle . $bottom . $footer;
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
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);

    }
}
class BlacklistClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);

    }
}


/**
 * Class CarrierAdmin and CarrierClient
 * Отправляем письма об услуге Carrier - проверка на оператора
 */
class CarrierAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);
    }
}
class CarrierClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);
    }
}


/**
 * Class CarrierAdmin and CarrierClient
 * Отправляем письма об услуге Carrier - проверка на оператора
 */
class UnlockAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);

    }
}
class UnlockClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);
    }
}


/**
 * Class CarrierAdmin and CarrierClient
 * Отправляем письма об услуге Carrier - проверка на оператора
 */
class UdidAdmin extends AdminMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);
    }
}
class UdidClient extends ClientMail{
    function email($email,$email_client,$imei,$udid,$type){

        parent::email($email,$email_client,$imei,$udid,$type);
    }
}





/**
 * Class CommsManager
 */
abstract class CommsManager {
    const ADMIN = 1;
    const CLIENT = 2;

    abstract function make($flag_int);

}

/**
 * Class BlacklistCommsManager
 * для выбора, кому отправить письмо по услуге черного списка
 */
class BlacklistCommsManager extends CommsManager {

    function make($flag_int){
        switch($flag_int){
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

    function make($flag_int){
        switch($flag_int){
            case self::ADMIN:
                return new CarrierAdmin();
            case self::CLIENT:
                return new CarrierClient();
        }
    }
}


/**
 * Class UnlockCommsManager
 * для выбора, кому отправить письмо по услуге проверки на оператора
 */
class UnlockCommsManager extends CommsManager {

    function make($flag_int){
        switch($flag_int){
            case self::ADMIN:
                return new UnlockAdmin();
            case self::CLIENT:
                return new UnlockClient();
        }
    }
}


/**
 * Class UdidCommsManager
 * для выбора, кому отправить письмо по услуге проверки на оператора
 */
class UdidCommsManager extends CommsManager {

    function make($flag_int){
        switch($flag_int){
            case self::ADMIN:
                return new UdidAdmin();
            case self::CLIENT:
                return new UdidClient();
        }
    }
}




/**
 * Class Settings
 * Файл настроек
 */
class Settings {
    static $COMMSTYPE = 'udid';
}


/**
 * Class MailConfig
 * Выбираем CommsManager для вида услуг
 */
class MailConfig {
    private static $instance;
    private $commsManager;

    private function __construct(){
        $this->init();
    }

    private function init(){
        switch(Settings::$COMMSTYPE){
            case 'blacklist':
                $this->commsManager = new BlacklistCommsManager();
                break;
            case 'carrier':
                $this->commsManager = new CarrierCommsManager();
                break;
            case 'unlock':
                $this->commsManager = new UnlockCommsManager();
                break;
            case 'udid':
                $this->commsManager = new UdidCommsManager();
                break;
        }
    }

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getCommsManager(){
        return $this->commsManager;
    }
}
?>