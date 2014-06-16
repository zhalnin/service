<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:32
 */

namespace dmn\view;

class VH {
    static function getRequest() {
        return \dmn\base\RequestRegistry::getRequest();
    }
}
?>