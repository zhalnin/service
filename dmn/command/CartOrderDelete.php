<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 17:02
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CartOrder' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );

/**
 * Class CartOrderDelete
 * Для удаления блока заказа по id
 * @package dmn\command
 */

class CartOrderDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $id = $request->getProperty( 'order_id' );
        $items_id = $request->getProperty( 'items_id' );
        if( $id ) { // если передан id_news
            $cartOrder = \dmn\domain\CartOrder::find( $id ); // находим элементы по заданному id
            $orderId = $cartOrder->getId(); // получаем order id
            $cartItems = \dmn\domain\CartItems::findByOrderId( $orderId , $items_id ); // находим элементы по заданному id

            // удаление блока c заказом
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
            $cartOrder->markDeleted(); // отмечаем для удаления
            $cartItems->markDeleted(); // отмечаем для удаления

            $this->reloadPage( 0, "dmn.php?cmd=CartOrder&page=$_GET[page]" ); // перегружаем страничку
            return self::statuses( 'CMD_OK' );

        } else {
            throw new \dmn\base\AppException('Error in CartOrderDelete' );
        }
    }
}
?>