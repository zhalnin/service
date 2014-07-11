<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/06/14
 * Time: 17:14
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Udid.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/view/utils/utils.checkUDID.php" );
require_once( "imei_service/domain/Unlock.php" );
require_once( "imei_service/classes/class.Cart.php" );

class Udid extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $id = 0;
        $udidCollection = \imei_service\domain\Udid::find( $id );
        $request->setObject( 'udidCollection', $udidCollection );
        $request->addFeedback( "Welcome to UDID" );
//        unset($_SESSION['total_items_imei_service']);
//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";
        $type               = $request->getProperty( 'type' );
        $email              = $request->getProperty( 'email' );
        $udid               = $request->getProperty( 'udid' );
        $email_admin        = 'imei_service@icloud.com';
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
            $this->reloadPage( 0, "?cmd=Udid&idc={$id_catalog}&idp={$id_parent}" ); // перегружаем страничку

        }
        return self::statuses( 'CMD_OK' );// возвращаем успешный статус






//        if( empty( $email ) ) {
//            $request->addFeedback( 'Заполните поле "Email"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( checkEmail( $email ) == false ) {
//            $request->addFeedback( 'Введите корректный адрес "Email"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( empty( $udid ) ) {
//            $request->addFeedback( 'Заполните поле "UDID"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }
//        if( checkUDID( $udid ) == false ) {
//            $request->addFeedback( 'Введите корректный номер "UDID"' );
//            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
//        }

//        echo "<tt><pre>".print_r( $request , true ) ."</pre></tt>";

//        $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
//        $commsManager->make(1)->email( $email_admin, $email, null, $udid, null, $type, null, null ); // отправляем письмо админу
//        $commsManager->make(2)->email( $email_admin, $email, null, $udid, null, $type, null, null ); // отправляем письмо клиенту

//        return self::statuses( 'CMD_UDID_OK' ); // возвращаем успешный статус и вызываем страницу с поздравлением и уведомлением, что будет письмо с активацией
    }
}
?>