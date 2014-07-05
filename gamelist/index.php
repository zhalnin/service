<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 14:47
 */
try {

    session_start();

    include( 'base_fns.php' );
    include( 'cart_fns.php' );

    if( ! isset( $_SESSION['cart'] ) ) {
        $_SESSION['cart'] = array();
        $_SESSION['total_items'] = 0;
        $_SESSION['total_price'] = '0.00';
    }

    $view = empty( $_GET['view'] ) ? 'index' : $_GET['view'];
    $controller = 'shop';

    switch( $view ) {
        case 'index':
            $products = findProducts();
            break;
        case 'add_to_cart':
            $id = $_GET['id'];
            $add_item = addToCart( $id );
            $_SESSION['total_items'] = totalItems( $_SESSION['cart'] );
            $_SESSION['total_price'] = totalPrice( $_SESSION['cart'] );
            header( 'Location: index.php' );
            break;
        case 'update_cart':
            updateCart();
            $_SESSION['total_items'] = totalItems( $_SESSION['cart'] );
            $_SESSION['total_price'] = totalPrice( $_SESSION['cart'] );
            header( 'Location: index.php?view=checkout' );
            break;
        case 'checkout':
            $shipping = 10.00;
            break;
        case 'thankyou':
            $_SESSION['cart'] = array();
            $_SESSION['total_items'] = 0;
            $_SESSION['total_price'] = 0.00;
            break;

    }

    include( $_SERVER['DOCUMENT_ROOT'].'/'.'service/gamelist/views/layouts/'.$controller.'.php' );
//    include( $_SERVER['DOCUMENT_ROOT'].'/'.'patterns/GITservice/gamelist/views/layouts/'.$controller.'.php' );

} catch ( PDOException $ex ) {
    echo $ex->getMessage();
} catch ( Exception $ex ) {
    echo $ex->getMessage();
}