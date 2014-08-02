<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 19:38
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CartOrder' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );

class CartOrderDetail extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        // получаем id_news редактируемой новости
        $id = $request->getProperty( 'order_id' );
        $items_id = $request->getProperty( 'items_id' );
        if( $id ) { // если передан id
            $cartOrder = \dmn\domain\CartOrder::find( $id ); // находим элементы по заданному id
            $orderId = $cartOrder->getId(); // получаем order id
            $cartItems = \dmn\domain\CartItems::findByOrderId( $orderId, $items_id ); // находим элементы по заданному id

            $request->setObject( 'cartOrderCollection', $cartOrder ); // сохраняем объекты для передачи во вьюшку
            $request->setObject( 'cartItemsCollection', $cartItems ); // сохраняем объекты для передачи во вьюшку
        } else {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
    }
}
?>