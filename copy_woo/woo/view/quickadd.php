<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/01/14
 * Time: 19:30
 * To change this template use File | Settings | File Templates.
 */

require_once( "woo/view/ViewHelper.php" );
$request = \woo\view\VH::getRequest();

?>
<html>
<head>
<title>QuickAddVenue</title>
</head>
<body>
<h1>QuickAddVenue</h1>

<table>
    <tr>
        <td>
            <?php print $request->getFeedbackString("</td></tr><tr><td>"); ?>
        </td>
    </tr>
</table>

</body>
</html>