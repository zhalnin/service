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
require_once( "imei_service/domain/Unlock.php" );
require_once( "imei_service/classes/class.Cart.php" );


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
        $email_admin = 'imei_service@icloud.com';
        $id_catalog         = $request->getProperty( 'idc' );
        $id_parent          = $request->getProperty( 'idp' );
        $position           = $request->getProperty( 'pos' );
        $action             = $request->getProperty( 'act' );
        $sid_add_message    = $request->getProperty('sid_add_message'); // получаем id сессии

        $id = 0;
        $decorateCollection = \imei_service\domain\Unlock::find( $id ); // находим коллекцию по id в таблице
        $request->setObject( 'decorateUnlock', $decorateCollection ); // сохраняем коллекцию в объект Request
        // получаем объект фабрику с различными методами
        $factory = \imei_service\mapper\PersistenceFactory::getFactory( 'imei_service\\domain\\Unlock' );
        // передаем нашу фабрику в объект для работы с базой данных
        $unlock_assembler = new \imei_service\mapper\DomainObjectAssembler( $factory );
        // шаблон для создания условного оператора для запроса к БД
        $unlock_idobj = new \imei_service\mapper\UnlockIdentityObject( 'id_catalog' );
        $unlock_idobj->eq( $request->getProperty( 'idc' ) )->field( 'hide' )->eq( 'show' );
        // собственно сам запрос, который вернет коллекцию, по ней пройдем foreach с помощью класса Iterator
        $unlock_collection = $unlock_assembler->findOne( $unlock_idobj );
//        echo "<tt><pre>".print_r( $unlock_collection , true ) ."</pre></tt>";
//        $obj->setUnlock( $unlock_collection );
        $request->setObject( 'unlockParent', $unlock_collection ); // сохраняем коллекцию в объект Request

        if( ! empty( $action ) ) { // если передано действие add_to_cart
            // добавляем предмет в корзину
            $add_item = \imei_service\classes\Cart::setAddToCart( $id_catalog, $position );
            // подсчитываем общее количество
            $_SESSION['total_items_imei_service'] = \imei_service\classes\Cart::getTotalItems( $_SESSION['cart_imei_service'] );
            // подсчитываем общую сумму
            $_SESSION['total_price_imei_service'] = \imei_service\classes\Cart::getTotalPrice( $_SESSION['cart_imei_service'] );
            $this->reloadPage( 0, "?cmd=BlacklistCheck&idc={$id_catalog}&idp={$id_parent}" ); // перегружаем страничку

        }
        return self::statuses( 'CMD_OK' );// возвращаем успешный статус

//        if( empty( $email ) ) { // если параметр email отсутствует
//            $request->addFeedback( 'Заполните поле "Email"' );
//            // заново показываем форму повторно
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( checkEmail( $email ) == false ) { // если email не прошел проверку
//            $request->addFeedback( 'Введите корректный адрес "Email"' );
//            // заново показываем форму повторно
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( empty( $imei ) ) { // если параметр IMEI отсутствует
//            $request->addFeedback( 'Заполните поле "IMEI"' );
//            // заново показываем форму повторно
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( checkIMEI( $imei ) == false ) { // если IMEI не прошел проверку
//            $request->addFeedback( 'Введите корректный номер "IMEI"' );
//            // заново показываем форму повторно
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//
//        // в классе MailConfig находим подходящий commsManager по типу
//        $commsManager = \imei_service\classes\MailConfig::get( $type );
//        // отправляем письмо админу
//        $commsManager->make(1)->email( $email_admin, $email, $imei, null, null, $type, null, null );
//        // отправляем письмо клиенту
//        $commsManager->make(2)->email( $email_admin, $email, $imei, null, null, $type, null, null );
//
//        // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
//        return self::statuses( 'CMD_BLACKLIST_OK' );
    }
}
?>