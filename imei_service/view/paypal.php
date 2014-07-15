<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/07/14
 * Time: 23:31
 */
namespace imei_service\view;

error_reporting( E_ALL & ~E_NOTICE );

try {


    // ловим сообщения об ошибках
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>
