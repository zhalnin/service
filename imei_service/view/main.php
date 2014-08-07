<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 15:20
 */
error_reporting( E_ALL & ~E_NOTICE );

// подключаем помощник для вьюшки
require_once( "imei_service/view/ViewHelper.php" );

// получаем объект request
$request = \imei_service\view\VH::getRequest();
// переадресация на страницу с новостями - главная
//header( "Location:?cmd=News" );
?>

<!DOCTYPE html>
<html>
<head>
    <title>IMEI-SEVICE</title>
</head>
<body>
<table>
    <tr>
        <td><?php print $request->getFeedbackString("</td></tr><tr><td>"); ?></td>
    </tr>
</table>
</body>
</html>