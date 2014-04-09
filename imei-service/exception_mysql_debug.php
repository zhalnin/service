<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 14:44
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);

if(defined("DEBUG"))
{
  echo "<p class='help'>Произошла исключительная ситуация
  {ExceptionMySQL} при обращении к СУБД MySQL!</p>";
  echo "<p class='help'>{$exc->getMySQLError()}<br/>
        ".nl2br($exc->getSQLQuery())."</p>";
  echo "<p class='help'>Ошибка в файле {$exc->getFile()}
        в строке {$exc->getLine()}.</p>";
  exit();
}
else
{
  echo "<HTML><HEAD><META http-equiv='Refresh' content='0; URL=exception_mysql.php'></HEAD></HTML>";
  exit();
}
?>