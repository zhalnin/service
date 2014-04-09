<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 15:24
 * To change this template use File | Settings | File Templates.
 */

/**
 * Error handing caused by
 * an appeal to non-existent object
 */
// Set error handling
error_reporting(E_ALL & ~E_NOTICE);
// Include top template
require_once("utils/top.php");

echo "<p class=\"help\">Произошла исключительная
      ситуация (ExceptionObject) при обращении
      к несуществующему объекту.</p> ";
echo "<p class=\"help\">{$exc->getMessage()}</p>";
echo "<p class=\"help\">Несуществующий объект: {$exc->getKey()}<br/>
      ".nl2br($exc->getSQLQuery())."</p>";
echo "<p class=\"help\">Ошибка в файле {$exc->getFile()}
      в строке {$exc->getLine()}.</p>";
// Include bottom template
require_once("utils/bottom.php");
exit();
?>