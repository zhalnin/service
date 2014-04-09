<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31.05.12
 * Time: 19:54
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);

// Помещаем время старта в переменную $begin_time
$part_time = explode(' ',microtime());
$begin_time = $part_time[1].substr($part_time[0],1);

// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Архивируем информацию
require_once("archive.php");
// Выполнение запроса
require_once("utils.query_result.php");
// Управление объемом базы данных
require_once("utils.database.php");

$namepage = "Система администрирования";
?>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="../utils/cms.css">
</head>
<body leftmargin="0" marginheight="0" rightmargin="0" bottommargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
    <tr valign="top">
        <td colspan="3">
<table class=topmenu border=0>
     <tr>
        <td width=5%>&nbsp;</td>
        <td>
            <h1 class=title><?php echo $namepage; ?></h1>
<?php
    // Выводим дату начала регистрации данных и число прошедших с начала регистрации
    // дней - определяем дату в таблице $tbl_ip и $tbl_arch_hits
    $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data
                FROM $tbl_ip";
    $date_ip = query_result($query);
    $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data
                FROM $tbl_arch_hits";
    $date_arch = query_result($query);
    if(empty($date_ip) && empty($date_arch)) $date = time();
    else
    {
        if(empty($date_ip)) $date = $date_arch;
        else if(empty($date_ip)) $date = $date_ip;
        else
        {
            if($date_ip < $date_arch) $date = $date_ip;
            else $date = $date_arch;
        }
    }
    if(empty($date)) $date = time();
    // Извлекаем объем базы данных
    $value = get_value_database();
    echo "<div>";
    printf("Система работает: <b> %d </b> дн.", ceil((time()-$date)/3600/24));
    echo " Объем базы данных: <b><a href=database.php title='Управление объемом базы данных'>".
            valuesize($value)."</a></b>";
    echo "</div>";
?>
        </td>
        <td>
            <a href="../index.php" title="Вернуться на страницу администрирования сайта">Администрирование</b></a>&nbsp;&nbsp;
            <a href="../../index.php" title="Вернуться на головную страницу сайта">Вернуться на сайт</b></a>&nbsp;&nbsp;
        </td>
    </tr>
</table>
        </td>
    </tr>
    <tr valign=top>
        <td class=menu>
<?php
    include "menu.php";
?>
        </td>
        <td class=main height=100%>
            <h1 class=namepage><?php echo $title ?>&nbsp;&nbsp;</h1>
            <?php echo "<p class=help>$pageinfo</p>"; ?><br>
        

</body>
</html>
