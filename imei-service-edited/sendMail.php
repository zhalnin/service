<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 02.11.12
 * Time: 21:31
 * To change this template use File | Settings | File Templates.
 */
//if(!defined("MAIL")) {
//    header("Location: index.php");
//}
// Подключаем FrameWork
//require_once("config/class.config.php");

error_reporting(E_ALL & ~E_NOTICE);

// Формируем письмо
$body = '
<html>
<head>
<title>Новая заявка</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<meta content="Делаем официальную отвязку от оператора iPhone, проверку по IMEI и на blacklist iPhone" name="Description">';

try
{
    //$email =            "imei_service@icloud.com";
    //$email = "zhalnin78@gmail.com";
    $email = "support@imei-service.ru";
    $email_client =     $_POST['email'];
    $imei =             $_POST['imei'];
    $udid =             $_POST['udid'];
    $type =             $_POST['type'];

//    $type =             'carrier';
//    $email_client =     'zhalninpal@me.com';
//    $imei =             '012345834583234';
//    $udid =             'j3f930rke03kfj0g0443j9s8fh3ndk3k5567k4l3';
//    $operator =         'Официальный анлок от американского оператора';

    require_once("class/class.SendMail.php");


    // Присваиваем значение
    Settings::$COMMSTYPE = $type;
    // Создаем экземпляр дочернего класса CommsManager и создается объект(синглтон), исходя из Settings(абстрактная фабрика)
    $app = MailConfig::getInstance()->getCommsManager();
    // 1 - Send admin
    // 2 - Send client
    // Вызываем метод make() дочернего класса CommsManager и создаем экземпляр класса для отправки email админу(абстрактная фабрика)
    $app->make(1)->email($email,$email_client,$imei,$udid,$type);
    // Вызываем метод make() дочернего класса CommsManager и создаем экземпляр класса для отправки email клиенту(абстрактная фабрика)
    $app->make(2)->email($email,$email_client,$imei,$udid,$type);

    if($type == 'unlock'){
        sleep(3);
        header("Location: success_unlock.php");
    } else {
        sleep(3);
        header("Location: success_check.php");
    }
}
catch(Exception $exc)
{
    echo $exc.message();
}

?>