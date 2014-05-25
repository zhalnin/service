<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01/01/14
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */

require_once( "woo/view/ViewHelper.php" );
$request = \woo\view\VH::getRequest();
//echo "<tt><pre>".print_r($request, true)."</pre></tt>";

?>
<html>
<head>
    <title>Woo</title>
</head>
<body>
<table>
    <tr>
        <td><?php print $request->getFeedbackString("</td></tr><tr><td>"); ?></td>
    </tr>
</table>
</body>
</html>