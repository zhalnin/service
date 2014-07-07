<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 06/07/14
 * Time: 19:56
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/UnlockDetails.php" );
require_once( "imei_service/domain/Unlock.php" );
require_once( "imei_service/classes/class.Cart.php" );

//require_once( "imei_service/base/Registry.php" );

/**
 * Class Cart
 * Получаем по id каталогу и id позиции наименования предметов
 * из таблиц system_catalog(\imei_service\domain\Unlock)
 * и system_position(\imei_service\domain\UnlockDetails)
 * @package imei_service\command
 */
class Cart extends Command {

    function doExecute( \imei_service\controller\Request $request ) {


//        echo $_SESSION['total_items_imei_service'];
//        echo $_SESSION['total_price_imei_service'];
        echo "<tt><pre>".print_r( $_SESSION['cart_imei_service'], true )."</pre></tt>";
        $action = $request->getProperty( 'act' );
        if( ! empty( $_SESSION['cart_imei_service'] ) ) { // Если корзина не пуста
            if( ! empty( $action ) ) {
//                $add_item = \imei_service\classes\Cart::setAddToCart( $id_catalog, $position );
                \imei_service\classes\Cart::setUpdateCart();
                $_SESSION['total_items_imei_service'] = \imei_service\classes\Cart::getTotalItems( $_SESSION['cart_imei_service'] );
                $_SESSION['total_price_imei_service'] = \imei_service\classes\Cart::getTotalPrice( $_SESSION['cart_imei_service'] );
                $this->reloadPage( 0, "?cmd=Cart" );
            }
            foreach ( $_SESSION['cart_imei_service'] as $id_catalog => $id_position ) { // получаем id каталога и id позиции
                foreach ( $id_position as $position => $qty ) { // из массива с позицией получаем саму позицию и количество предметов по этой позиции
                    // получаем объект по id каталогу из таблицы system_catalog и создаем массив из количества и выборки
                    $colCatalog[][$qty] = \imei_service\domain\Unlock::findByCatalog( $id_catalog );
                    // получаем объект по id каталогу и позиции из таблицы system_position и создаем массив из количества и выборки
                    $colCatalogPosition[][$qty] = \imei_service\domain\UnlockDetails::findByPosAndCat( $position, $id_catalog );
                }
            }

            $request->setObject( 'cartCatalog', $colCatalog ); // Сохраняем в request
            $request->setObject( 'cartCatalogPosition', $colCatalogPosition ); // Сохраняем в request

            return self::statuses( 'CMD_OK' ); // Возвращаем успешный код и переходим во вьюшку cart.php
        }
    }
}
?>