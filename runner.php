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


} catch ( \imei_service\base\AppException $ex ) {
   echo $ex->getErrorObject();
} catch ( \imei_service\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>