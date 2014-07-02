<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/12/13
 * Time: 22:23
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);


//require_once("base/Registry.php");
require_once("controller/PageController.php");
require_once("database/DataBase.php");

$controller = new \account\controller\ActivationController();
$controller->process();

?>