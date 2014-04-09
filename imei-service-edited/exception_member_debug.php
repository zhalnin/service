<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);

if(defined("DEBUG"))
{

  echo "<p class=\"help\">Произошла исключительная
        ситуация (ExceptionObject) при обращении
        к несуществующему члену класса ".__CLASS__."</p> ";
  echo "<p class=\"help\">{$exc->getMessage()}</p>";
  echo "<p class=\"help\">Имя несуществующего члена: {$exc->getKey()}<br/>
        ".nl2br($exc->getSQLQuery())."</p>";
  echo "<p class=\"help\">Ошибка в файле {$exc->getFile()}
        в строке {$exc->getLine()}.</p>";
}
else
{
    echo "<HTML><HEAD>
            <meta content='0; URL=exception_member.php' http-equiv='Refresh'>
            </HEAD></HTML>";
    exit();
}
?>