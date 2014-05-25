<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 15/02/14
 * Time: 23:43
 * To change this template use File | Settings | File Templates.
 */


require_once( "woo/view/ViewHelper.php" );
$request = \woo\view\VH::getRequest();
//echo "<tt><pre>".print_r($request, true)."</pre></tt>";

?>
<html>
<head>
    <title>Woo - error</title>
</head>
<body>
<table>
    <tr>
        <td><h1>Произошла непредвиденная ошибка</h1></td>
    </tr>
</table>
</body>
</html>