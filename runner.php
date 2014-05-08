<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 19:28
 */
try {
    require_once( "imei_service/controller/Controller.php" );


    imei_service\Controller\controller::run();

} catch ( \imei_service\base\AppException $ex ) {
   echo $ex->getErrorObject();
}

?>