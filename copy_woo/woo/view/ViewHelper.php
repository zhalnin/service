<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01/01/14
 * Time: 23:01
 * To change this template use File | Settings | File Templates.
 */

namespace woo\view;

/**
 * Class VH
 * Help to return Request in any view
 * @package woo\view
 */
class VH {
    static function getRequest() {
        return \woo\base\RequestRegistry::getRequest();
    }
}

?>