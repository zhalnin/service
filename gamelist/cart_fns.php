<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/06/14
 * Time: 13:42
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

function updateCart() {
    foreach( $_SESSION['cart'] as $id => $qty ) {
        if( $_POST[$id] == '0' ) {
            unset( $_SESSION['cart'][$id] );
        } else {
            $_SESSION['cart'][$id] = $_POST[$id];
        }

    }
}

function totalItems( $cart ) {
    $num_items = 0;
    if( is_array( $cart ) ) {
        foreach ( $cart as $id => $qty ) {
            $num_items += $qty;
        }
    return $num_items;
    }
}

function totalPrice( $cart ) {
    $price = '0.00';
    $connection = db_connect();
    if( is_array( $cart ) ){
        foreach ( $cart as $id => $qty ) {
            $query = "SELECT price FROM products WHERE products.id = ?";
            $sth = $connection->prepare( $query );
            $sth->execute( array( $id ) );
            if( $sth ) {
                $items_price = $sth->fetch();
                $price += $items_price['price'] * $qty;
            }
        }
    }
//                echo "<tt><pre>".print_r($price, true)."</pre></tt>";
    return $price;
}


?>