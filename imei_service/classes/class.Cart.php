<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 06/07/14
 * Time: 15:34
 */

namespace imei_service\classes;
error_reporting( E_ALL & ~E_NOTICE );
session_start();
require_once( "imei_service/base/Registry.php" );

class Cart {
    private static $instance;

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Добавляем в корзину предмет
     * @param $id_catalog - каталог в system_position
     * @param $position - позиция в system_position
     * @return bool
     */
    protected function addToCart( $id_catalog, $position ) {
        if( isset( $_SESSION['cart_imei_service'][$id_catalog][$position] ) ) {
            $_SESSION['cart_imei_service'][$id_catalog][$position]++;
            return true;
        } else {
            $_SESSION['cart_imei_service'][$id_catalog][$position] = 1;
            return true;
        }
        return false;
    }

    /**
     * Подсчитываем количество предметов в корзине
     * по id каталога и id позиции
     * @param $cart - сессия
     * @return int - количество предметов
     */
    protected function totalItems( $cart ) {
        $total = 0;
        if( is_array( $cart ) ) {
            foreach ( $cart as $id_catalog => $position ) {
                foreach ( $position as $qty ) {
                    $total += $qty;
                }
            }
        }
        return $total;
    }

    /**
     * Подсчитываем общую стоимость товаров в корзине
     * @param $cart - сессия
     * @return string - сумма
     */
    protected function totalPrice( $cart ) {
        $price = '0.00';
        if( is_array( $cart ) ) {
            foreach ( $cart as $id_catalog => $catalog ) {
                foreach ( $catalog as $position => $qty ) {
                    $pdo = \imei_service\base\DBRegistry::getDB();
                    $sth = $pdo->prepare( 'SELECT cost FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
                    $result = $sth->execute( array( $position, $id_catalog ) );
                    $item = $sth->fetch();
                    if( $result ) {
                        $price += $item['cost'] * $qty;
                    }
                }
            }
        }
        return $price;
    }

    /**
     * Обновляем колличество предметов в корзине:
     * из вьюшки получаем POST запрос с параметрами
     * парсируем их и обновляем количество
     */
    protected function updateCart() {
//        echo "<tt><pre>".print_r( $_POST, true )."</pre></tt>";
        // из запроса получаем два индекса(8_2), где 8-id_catalog и 2-position в БД, и значение-количество предметов
        foreach ($_POST as $index => $value ) {
            if( preg_match('|[0-9]+|', $value ) ) { // если количество предметов это цифра/число
                // разбиваем наш индекс по "_" и инициализируем переменные
                list($id_catalog, $position ) = explode('_', $index);
                if( $value == 0 ) { // если количество предметов равно 0
                        unset( $_SESSION['cart_imei_service'][$id_catalog][$position] ); // уничтожаем эту позицию в корзине
                    if( empty( $_SESSION['cart_imei_service'][$id_catalog] ) ) { // если по этому каталогу больше нет предметов
                        unset( $_SESSION['cart_imei_service'][$id_catalog] ); // уничтожаем каталог в корзине
                    }
                } else { // если количество в корзине не равно 0
                    $_SESSION['cart_imei_service'][$id_catalog][$position] = $value; // то данную позицию по каталогу обновляем
                }
            }
        }
    }

    static function getTotalPrice( $cart ) {
        return self::instance()->totalPrice( $cart );
    }

    static function getTotalItems( $cart ) {
        return self::instance()->totalItems( $cart );
    }

    static function setAddToCart( $id_catalog, $id_position ) {
        return self::instance()->addToCart( $id_catalog, $id_position );
    }

    static function setUpdateCart() {
        self::instance()->updateCart();
    }
}
?>