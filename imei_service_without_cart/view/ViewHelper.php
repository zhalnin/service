<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 15:27
 */

namespace imei_service\view;

class VH {
    static function getRequest() {
        return \imei_service\base\RequestRegistry::getRequest();
    }
}
?>