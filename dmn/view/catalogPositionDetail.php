<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 19:34
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
require_once( "dmn/view/ViewHelper.php" );
$request = VH::getRequest();
//$cartOrder = $request->getObject( 'cartOrderCollection' );
//$cartItems = $request->getObject( 'cartItemsCollection' );
$catalogPositionDetail = $request->getObject( 'catalogPositionDetail' );
//echo "<tt><pre>".print_r($cartItems, true)."</pre></tt>";
//echo "<tt><pre>".print_r($cartOrder, true)."</pre></tt>";
//echo "<tt><pre>".print_r($catalogPositionDetail, true)."</pre></tt>";

$title = 'Подробная информация';
?>

<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="dmn/view/css/cms.css">
</head>
<body leftmargin="0"
      marginheight="0"
      marginwidth="0"
      rightmargin="0"
      bottommargin="0"
      topmargin="0">
<table width="100%"
       border="0"
       cellpadding="0"
       cellspacing="0"
       height="100%"
       class="text">
    <tr valign="top">
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td width="0">&nbsp;</td>
        <td class=main height=100%>
            <?php
            try {
                ?>
                <table width="100%"
                       class="table"
                       border="0"
                       cellpadding="0"
                       cellspacing="0">
                    <tr class="header" align="center">
                        <td>Параметр</td>
                        <td>Значение</td>
                    </tr>

                    <tr>
                        <td align=right>Название</td>
                        <td><?php echo $catalogPositionDetail->getOperator(); ?></td>
                    </tr>
                    <tr>
                        <td align=right>Стоимость</td>
                        <td><?php echo $catalogPositionDetail->getCost(); ?></td>
                    </tr>
                    <tr>
                        <td align=right>Сроки</td>
                        <td><?php echo $catalogPositionDetail->getTimeconsume(); ?></td>
                    </tr>
                    <tr>
                        <td align=right>Совместимость</td>
                        <td><?php echo $catalogPositionDetail->getCompatible(); ?></td>
                    </tr>
                    <tr>
                        <td align=right>Статус</td>
                        <td><?php if( $catalogPositionDetail->getStatus() == '' ) echo " - "; else echo $catalogPositionDetail->getStatus(); ?></td>
                    </tr>
                    <tr>
                        <td align=right>Валюта</td>
                        <td><?php echo $catalogPositionDetail->getCurrency(); ?></td>
                    </tr>
                    <tr>
                        <td align=right>Дата</td>
                        <td><?php echo $catalogPositionDetail->getPutdate(); ?></td>
                    </tr>
                </table><br><br>
            <?php
            } catch( \dmn\base\AppException $exc) {
                echo $exc->getErrorObject();
            }
            ?>
        </td>
        <td width=10>&nbsp;</td>
    </tr>
    <tr class=authors>
        <td colspan="3"></td>
    </tr>
</table>
</body>
</html>