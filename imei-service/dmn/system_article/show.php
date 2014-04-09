<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/6/13
 * Time: 6:25 PM
 * To change this template use File | Settings | File Templates.
 */
require_once("../../class/class.Database.php");
Database::getInstance();

//require_once("../../config/config.php");

error_reporting(E_ALL & ~E_NOTICE);
header("Content-Type: text/html; charset=UTF-8");
$_GET['id_position'] = intval($_GET['id_position']);
$_GET['id_catalog'] = intval($_GET['id_catalog']);
$_GET['id_paragraph'] = intval($_GET['id_paragraph']);
try
{

    $query = "SELECT * FROM $tbl_paragraph_image
            WHERE id_position = $_GET[id_position] AND
                  id_catalog = $_GET[id_catalog] AND
                  id_paragraph = $_GET[id_paragraph]";
    $img = mysql_query($query);
    if(!$img)
    {
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка извлечения
            изображения");
    }
    $image = mysql_fetch_array($img);
    $filename = "../../".$image['big'];
//$filename = $_GET['img'];

    $size = getimagesize($filename);

}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
?>
<html>
<head>
    <meta content="0" http-equiv="imagetoolbar">
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
            Дождитесь загрузки изображения
            <div style="position: relative; top: 0px; left: 0px">
                <img src="<? echo $filename;?>" border="0" <?= $size[3] ?>>
            </div>
        </td>
    </tr>
</table>
<div style="position: absolute; z-index: 2; width: 100%; botton: 5px
        align="center">
<input class=button type=submit value=Закрыть
       onclick="self.close();"></div>
</body>
</html>
