<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 19:53
 */
namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
require_once( "dmn/view/ViewHelper.php" );
$request = VH::getRequest();
$cartOrder = $request->getObject( 'cartOrderCollection' );
$cartItems = $request->getObject( 'cartItemsCollection' );
//echo "<tt><pre>".print_r($cartItems, true)."</pre></tt>";
//echo "<tt><pre>".print_r($cartOrder, true)."</pre></tt>";

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
                            <td align=right>Номер заказа</td>
                            <td><?php echo $cartOrder->getId(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Дата</td>
                            <td><?php echo $cartOrder->getCreatedAt(); ?></td>
                         </tr>
                        <tr>
                            <td align=right>Email</td>
                            <td><?php echo $cartOrder->getEmail(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Название</td>
                            <td><?php echo $cartItems->getTitle(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Комментарий</td>
                            <td><?php echo $cartOrder->getData(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Стоимость</td>
                            <td><?php echo $cartItems->getPrice(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Количество</td>
                            <td><?php echo $cartItems->getQty(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>PayPal</td>
                            <td><?php echo $cartOrder->getPaypalTransId(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Статус</td>
                            <td><?php echo $cartOrder->getStatus(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Имя</td>
                            <td><?php echo $cartOrder->getFirstName(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Фамилия</td>
                            <td><?php echo $cartOrder->getLastName(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Страна</td>
                            <td><?php echo $cartOrder->getCountry(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Адрес</td>
                            <td><?php echo $cartOrder->getAddress(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Город</td>
                            <td><?php echo $cartOrder->getCity(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Индекс</td>
                            <td><?php echo $cartOrder->getZipCode(); ?></td>
                        </tr>
                        <tr>
                            <td align=right>Штат</td>
                            <td><?php echo $cartOrder->getState(); ?></td>
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