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
     * @param $id_catalog - каталог
     * @param $id_position - позиция
     * @return bool
     */
    protected function addToCart( $id_catalog, $id_position ) {
        if( isset( $_SESSION['cart_imei_service'][$id_catalog][$id_position] ) ) {
            $_SESSION['cart_imei_service'][$id_catalog][$id_position]++;
            return true;
        } else {
            $_SESSION['cart_imei_service'][$id_catalog][$id_position] = 1;
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
            foreach ( $cart as $id_catalog => $id_position ) {
                foreach ( $id_position as $qty ) {
                    $total += $qty;
                }
            }
        }
        return $total;
    }

    protected function totalPrice( $cart ) {
        $price = '0.00';
        if( is_array( $cart ) ) {
            foreach ( $cart as $id_catalog => $id_position ) {
                foreach ( $id_position as $position => $qty ) {
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

    protected function updateCart() {
//                echo "<tt><pre>".print_r( $_SESSION['cart_imei_service'], true )."</pre></tt>";
//        echo "<tt><pre>".print_r( $_POST, true )."</pre></tt>";
        $idc = $_POST['id_catalog'];
        foreach ( $_SESSION['cart_imei_service'] as $id_catalog => $id_position ) {
            foreach ( $id_position as $position => $qty ) {
                if( $_POST[$position] == '0' ) {
            echo "<tt><pre>".print_r( $_SESSION['cart_imei_service'][$id_catalog][$position], true )."</pre></tt>";
                    unset( $_SESSION['cart_imei_service'][$id_catalog][$position] );
                    if( empty( $_SESSION['cart_imei_service'][$id_catalog] ) ) {
                        unset( $_SESSION['cart_imei_service'][$id_catalog] );
                    }
                } else {
                    echo "<tt><pre>".print_r( $_SESSION['cart_imei_service'][$id_catalog][$position], true )."</pre></tt>";
                    $_SESSION['cart_imei_service'][$id_catalog][$position] = $_POST[$position];
                    $_SESSION['cart_imei_service'][$id_catalog][$position] = $_POST[$position];
                }

            }
        }


        foreach ( $_SESSION['cart_imei_service'] as $id => $qty ) {
            if( $_POST[$id] == '0' ) {
//                unset( $_SESSION['cart_imei_service'][$id] );
            } else {
//                $_SESSION['cart_imei_service'][$id_catalog][$id_position] = $_POST[$id];
//                $_SESSION['cart_imei_service'][$id] = $_POST[$id];
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