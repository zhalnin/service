<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 15:20
 */

require_once( "dmn/view/ViewHelper.php" );
$request = \dmn\view\VH::getRequest();
//header( "Location:?cmd=News" );
?>

<!DOCTYPE html>
<html>
<head>
    <title>IMEI-SEVICE</title>
</head>
<body>
<?php print 'main.php'; ?>
<table>
    <tr>
        <td><?php print $request->getFeedbackString("</td></tr><tr><td>"); ?></td>
    </tr>
</table>
</body>
</html>