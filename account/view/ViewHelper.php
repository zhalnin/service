<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/12/13
 * Time: 18:38
 * To change this template use File | Settings | File Templates.
 */

namespace account\view;

error_reporting(E_ALL & ~E_NOTICE);

//require_once("base/Registry.php");

class VH {
    static function getRequest() {
//        echo "VH2";
//        echo "<tt><pre>".print_r(\account\base\RequestRegistry::getRequest(),true)."</pre></tt>";
        return \account\base\RequestRegistry::getRequest();
    }
}