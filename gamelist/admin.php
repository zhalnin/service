<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 14:47
 */
try {
    include( 'base_fns.php' );


    $view = empty( $_GET['view'] ) ? 'index' : $_GET['view'];
    $controller = 'admin';

    switch( $view ) {
        case 'index':
            $orders = findOrders();
            break;

    }

//    include( $_SERVER['DOCUMENT_ROOT'].'/'.'service/gamelist/views/layouts/'.$controller.'.php' );
    include( $_SERVER['DOCUMENT_ROOT'].'/'.'patterns/GITservice/gamelist/views/layouts/'.$controller.'.php' );

} catch ( PDOException $ex ) {
    echo $ex->getMessage();
} catch ( Exception $ex ) {
    echo $ex->getMessage();
}