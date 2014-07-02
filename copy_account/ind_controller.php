<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 14/12/13
 * Time: 15:35
 * To change this template use File | Settings | File Templates.
 */
//namespace account\controller;

require_once("controller/PageController.php");
require_once("database/DataBase.php");

$controller = new \account\controller\IndController();
$controller->process();

//echo "<tt><pre>".print_r($controller->process(),true)."</pre></tt>";
?>