<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 19:45
 */

require_once( "imei_service/view/ViewHelper.php" );
$request = \imei_service\view\VH::getRequest();

?>

<!DOCTYPE html>
<html>
<head>
    <title>GENERAL IMEI-SEVICE</title>
</head>
<body>
<table>
    <tr>
        <td><?php print $request->getFeedbackString("</td></tr><tr><td>"); ?></td>
    </tr>
</table>
</body>
</html>