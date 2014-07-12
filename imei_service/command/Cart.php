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

        $action = $request->getProperty( 'act' ); // получаем название действия
//        echo "<tt><pre>".print_r( $action , true )."</pre></tt>";
        if( ! empty( $_SESSION['cart_imei_service'] ) ) { // Если корзина не пуста
            // получаем id каталога и массив с позициями и количеством предметов в каждом
            foreach ( $_SESSION['cart_imei_service'] as $id_catalog => $positions ) {
                // из массива с позицией получаем саму позицию и количество предметов по этой позиции
                foreach ( $positions as $position => $qty ) {
                    if( preg_match('|[0-9]+|', $qty ) ) { // если количество является цифрой/числом
                        // получаем объект по id каталогу из таблицы system_catalog и создаем массив из количества и выборки
                        $colCatalog[][$qty] = \imei_service\domain\Unlock::findByCatalog( $id_catalog );
                        // получаем объект по id каталогу и позиции из таблицы system_position и создаем массив из количества и выборки
                        $colCatalogPosition[][$qty] = \imei_service\domain\UnlockDetails::findByPosAndCat( $position, $id_catalog );
                    }
                }
            }

            if( ! empty( $action ) ) { // если передано действие update
                if( $action == 'update' ) {
                    \imei_service\classes\Cart::setUpdateCart(); // обновляем количество в корзине
                    // подсчитываем общее количество
                    $_SESSION['total_items_imei_service'] = \imei_service\classes\Cart::getTotalItems( $_SESSION['cart_imei_service'] );
                    // подсчитываем общую сумму
                    $_SESSION['total_price_imei_service'] = \imei_service\classes\Cart::getTotalPrice( $_SESSION['cart_imei_service'] );
                    $this->reloadPage( 0, "?cmd=Cart" ); // перегружаем страничку
                }
            }
            $request->setObject( 'cartCatalog', $colCatalog ); // Сохраняем в request
            $request->setObject( 'cartCatalogPosition', $colCatalogPosition ); // Сохраняем в request

             return self::statuses( 'CMD_OK' ); // Возвращаем успешный код и переходим во вьюшку cart.php
        }
    }
}
?>