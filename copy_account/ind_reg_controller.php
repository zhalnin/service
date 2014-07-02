<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/12/13
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */

//namespace account\controller;

require_once("controller/PageController.php");
require_once("database/DataBase.php");


$controller = new \account\controller\RegController();
$controller->process();



//echo "<tt><pre>".print_r($controller->process(),true)."</pre></tt>";

?>