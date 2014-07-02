<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 16:38
 */

function addToCart( $id ) {
    if( isset( $_SESSION['cart'][$id] ) ) {
        $_SESSION['cart'][$id]++;
        return true;
    } else {
        $_SESSION['cart'][$id] = 1;
        return true;
    }
    return false;
}

function totalItems( $cart ) {
    $total = 0;
    if( is_array( $cart ) ) {
        foreach ( $cart as $id => $qty ) {
            $total += $qty;
    //    echo "<tt><pre>".print_r( $total , true )."</pre></tt>";
        }
    }
    return $total;
}

function totalPrice( $cart ) {
    $price = '0.00';
    if( is_array( $cart ) ) {
        foreach ( $cart as $id => $qty ) {
            $item = findProduct( $id );
            if( $item ) {
                $price += $item['price'] * $qty;
            }
        }
    }
    return $price;
}

function updateCart() {
    foreach ( $_SESSION['cart'] as $id => $qty ) {
        if( $_POST[$id] == '0' ) {
            unset( $_SESSION['cart'][$id] );
        } else {
            $_SESSION['cart'][$id] = $_POST[$id];
        }
    }
}