<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 17:51
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);

require_once( "dmn/view/ViewHelper.php" );
$request = VH::getRequest();
$newsDetail = $request->getObject( 'newsDetail' );
$filename = "imei_service/view/".$newsDetail->getUrlpict();
$size = getimagesize($filename);
//echo "<tt><pre>".print_r($newsDetail->getBig(), true)."</pre></tt>";

?>

<html>
<head>
    <meta content="text/html; charset=utf8" http-equiv="content-type">
    <title>Просмотр фотографии</title>

    <style>
        table { font-size: 12px; font-family: Arial, Helvetica, sans-serif; background-color: #F3F3F3 }
    </style>
</head>
<body marginheight="0"
      marginwidth="0"
      rightmargin="0"
      leftmargin="0"
      topmargin="0">
<table height="100%"
       cellpadding="0"
       cellspacing="0"
       width="100%"
       border="1">
    <tr>
        <td height="100%" valign="middle" align="center">
            <div style="position: absolute; z-index: 2; width: 100%; bottom: 5px
        align="center">
            <input class=button type=submit value=Закрыть
                   onclick="self.close();"></div>
            <div style="position: relative; top: 0px; left: 0px">
                <img src="<?php echo $filename;?>" border="0" <?php echo $size[3] ?> >
            </div>
        </td>
    </tr>
</table>

</body>
</html>