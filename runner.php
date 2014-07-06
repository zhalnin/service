<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 19:28
 */

try {
    require_once( "imei_service/controller/Controller.php" );
    require_once( "imei_service/base/Exceptions.php" );


    imei_service\Controller\controller::run();


//    if( ! isset( $_SESSION['cart_imei_service'] ) ) {
//        $_SESSION['cart_imei_service'] = array();
//        $_SESSION['total_items_imei_service'] = 0;
//        $_SESSION['total_price_imei_service'] = '0.00';
//    }

} catch ( \imei_service\base\AppException $ex ) {
   echo $ex->getErrorObject();
} catch ( \imei_service\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>