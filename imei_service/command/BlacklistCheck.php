<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 12:29
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем BlacklistCheck
require_once( "imei_service/domain/BlacklistCheck.php" );
// подключаем класс для отправки email
require_once( "imei_service/classes/class.SendMail.php" );
// подключаем проверку email на валидность
require_once( "imei_service/view/utils/utils.checkEmail.php" );
// подключаем проверку IMEI на валидность
require_once( "imei_service/view/utils/utils.checkIMEI.php" );


/**
 * Class BlacklistCheck
 * Для проверки IMEI на blacklist
 * @package imei_service\command
 */
class BlacklistCheck extends Command {

    /**
     * Выполняется в controller для каждой команды command (cmd)
     * @param \imei_service\controller\Request $request
     * @return mixed
     */
    function doExecute( \imei_service\controller\Request $request ) {

        // id для поиска в БД
        $id = 0;
        // коллекция - запрос в БД по id
        $blacklistCheckCollection = \imei_service\domain\BlacklistCheck::find( $id );
        // добавляем коллекцию в объект Request
        $request->setObject( 'blacklistCheckCollection', $blacklistCheckCollection );
        // добавляем строку
        $request->addFeedback( "Welcome to Blacklist Check" );

        $type = $request->getProperty( 'type' ); // получаем параметр type
        $email = $request->getProperty( 'email' ); // получаем параметр email
        $imei = $request->getProperty( 'imei' ); // получаем параметр IMEI

        if( empty( $email ) ) { // если параметр email отсутствует
            $request->addFeedback( 'Заполните поле "Email"' );
            // заново показываем форму повторно
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkEmail( $email ) == false ) { // если email не прошел проверку
            $request->addFeedback( 'Введите корректный адрес "Email"' );
            // заново показываем форму повторно
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $imei ) ) { // если параметр IMEI отсутствует
            $request->addFeedback( 'Заполните поле "IMEI"' );
            // заново показываем форму повторно
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkIMEI( $imei ) == false ) { // если IMEI не прошел проверку
            $request->addFeedback( 'Введите корректный номер "IMEI"' );
            // заново показываем форму повторно
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }

        // в классе MailConfig находим подходящий commsManager по типу
        $commsManager = \imei_service\classes\MailConfig::get( $type );
        // отправляем письмо админу
        $commsManager->make(1)->email( $email, 'imei_service@icloud.com', $imei, null, null, $type, null, null );
        // отправляем письмо клиенту
        $commsManager->make(2)->email( $email, 'imei_service@icloud.com', $imei, null, null, $type, null, null );

        // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
        return self::statuses( 'CMD_BLACKLIST_OK' );
    }
}
?>