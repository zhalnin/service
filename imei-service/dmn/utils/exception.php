<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 04.04.12
 * Time: 19:03
 * To change this template use File | Settings | File Templates.
 */
// Включаем заголовок страницы
require_once("../utils/top.php");

echo "<p class=help>{$exc->getMessage()}</p>";
echo "<p class=help><a href=# onclick='history.back()'>Вернуться</a></p>";

// Включаем завершение страницы
require_once("../utils/bottom.php");
exit();
?>
