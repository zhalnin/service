<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 14:58
 * To change this template use File | Settings | File Templates.
 */
// Header
require_once("utils/utils.title.php");
$title = "Произошла ошибка в работе сайта!";
$keywords = "Произошла ошибка в работе сайта!";

// Include top template
require_once("templates/top.php");
echo title($title);
?>
<div class="main_txt">Некоторое время сайт будет недоступен, в связи с его обслуживанием.
  Приносим вам свои извинения!</div>
<?php
  // Include bottom template
  require_once("templates/bottom.php");
?>