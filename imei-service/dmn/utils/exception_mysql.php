<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 14:50
 * To change this template use File | Settings | File Templates.
 */

/**
 * Error handing caused by
 * query to MySQL
 */
// Set error handling
error_reporting(E_ALL & ~E_NOTICE);
// Include top template
require_once("../utils/top.php");

require_once("../../class/class.ExceptionMySQL.php");

echo "<p class=\"help\">Произошла исключительная
      ситуация (ExceptionMySQL) при обращении
      к СУБД MySQL.</p> ";
echo "<p class=\"help\">{$exc->getMessage()}</p>";
echo "<p class=\"help\">{$exc->getMySQLError()}<br/>
      ".nl2br($exc->getSQLQuery())."</p>";
echo "<p class=\"help\">Ошибка в файле {$exc->getFile()}
      в строке {$exc->getLine()}.</p>";
// Include bottom template
require_once("../utils/bottom.php");
exit();
?>