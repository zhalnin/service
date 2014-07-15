<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf8" >

</head>
</html>
<?php
require_once( "imei_service/base/Registry.php" );
require_once( "imei_service/classes/class.Cart.php" );
session_start();
foreach ($_SESSION as $key => $val ) {
    print "$key - $val<br />";
}

function findProduct( $position, $id_catalog ) {
    $pdo = \imei_service\base\DBRegistry::getDB();
    $sth = $pdo->prepare( 'SELECT * FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
    $result = $sth->execute( array( $position, $id_catalog ) );
    if( ! $result ) {
        throw new \PDOException( "Error in class.Cart.php" );
    }
    return $sth->fetch();
}



function paymentAmountCorrect( $shipping, $params ) {

    $amount = 0.00;
    $pdo = \imei_service\base\DBRegistry::getDB();

    for( $i=1; $i <= $params['num_cart_items']; $i++ ) {
        // инициализируем две переменные из строки, типа: 36_2
        list($id_catalog, $position ) = explode( '_', $params["item_number{$i}"] );
        $sth = $pdo->prepare( 'SELECT cost FROM system_position
                                                        WHERE system_position.pos = ?
                                                        AND system_position.id_catalog = ?' );
        $result = $sth->execute( array( $position, $id_catalog ) );
        if( $result ) {
            $item_price = $sth->fetch();
            $amount += $item_price['cost'] * $params["quantity{$i}"];
        }
    }
    $shipping = $amount / 100 * 3.9 + 10;
    if( ( $amount + $shipping ) == 352.87 ) {
        return true;
    } else {
        return false;
    }
}

function noPaypalTransId( $trans_id ) {
    $pdo = \imei_service\base\DBRegistry::getDB();
    $sth = $pdo->prepare( 'SELECT id FROM system_cart_orders WHERE paypal_trans_id = ?' );
    $sth->execute( array( $trans_id ) );
    $num_result = $sth->fetch();
    if( $num_result == 0 ) {
        return true;
    }
    return false;
}



//$product = findProduct(1, 36);
$d =  '09:56:01 Jul 15, 2014 PDT';
$date = new DateTime($d);
echo $date->format('Y-m-d H:i:s');
//echo "<tt><pre>".print_r($product, true)."</pre></tt>";

?>

