<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 13:39
 * To change this template use File | Settings | File Templates.
 */
 
function __autoload($class_name) {
  include '../../class/class.' . $class_name . '.php';
}
?>