<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 18:36
 */

include( 'base_fns.php' );

$paypal_email = "zhalninpal-facilitator@me.com";
$paypal_currency = 'RUB';
$shipping = 10.00;


function noPaypalTransId( $trans_id ) {

    $sth = getStatement( 'SELECT id FROM orders WHERE paypal_trans_id = ?' );
    $sth->execute( array( $trans_id ) );
    $num_result = $sth->fetch();
    if( $num_result == 0 ) {
        return true;
    }
    return false;
}

function paymentAmountCorrect( $shipping, $params ) {

    $amount = 0.00;

    for( $i=1; $i <= $params['num_cart_items']; $i++ ) {
        $sth = getStatement( 'SELECT price FROM products WHERE id = ?' );

        $sth->execute( array( intval( $params["item_number{$i}"] ) ) );
        if( $sth ) {
            $item_price = $sth->fetch();
            $amount += $item_price['price'] * $params["quantity{$i}"];
        }
    }
    if( ( $amount + $shipping ) == $params['mc_gross'] ) {
        return true;
    } else {
        return false;
    }
}

function createOrder( $params ) {

    $pdo = db_connect();
    $sth = $pdo->prepare( 'INSERT INTO orders (   orders.firstname,
                                                 orders.lastname,
                                                 orders.email,
                                                 orders.country,
                                                 orders.address,
                                                 orders.city,
                                                 orders.zip_code,
                                                 orders.state,
                                                 orders.status,
                                                 orders.amount,
                                                 orders.paypal_trans_id,
                                                 created_at )
                                              VALUES ( ?,?,?,?,?,?,?,?,?,?,?,? )' );

    $sth->execute( array(   $params['first_name'],
        $params['last_name'],
        $params['payer_email'],
        $params['address_country'],
        $params['address_street'],
        $params['address_city'],
        $params['address_zip'],
        $params['address_state'],
        $params['payment_status'],
        $params['mc_gross'],
        $params['txn_id'],
        date('Y-m-d H:i:s') ) );

    if( ! $sth ) {
        return false;
    }

    $order_id = $pdo->lastInsertId();
    for( $i=1; $i <= $params['num_cart_items']; $i++ ) {
        $product = findProduct( $params["item_number{$i}"] );
        $sth = getStatement( 'INSERT INTO items (   order_id,
                                                    product_id,
                                                    title,
                                                    price,
                                                    qty )
                                                VALUES ( ?,?,?,?,? )' );



        $sth->execute( array(   $order_id,
            $product['id'],
            $product['title'],
            $product['price'],
            $params["quantity{$i}"] ) );
        if( ! $sth ) {
            return false;
        }
    }
    return true;
}





//$req = 'cmd=_notify-validate';
//
//foreach ( $_POST as $key => $value ) {
//    $value = urlencode(stripslashes( $value ) );
//    $req .= "&$key=$value";
//}
//
//$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
//$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//$header .= "Content-Length: " . strlen( $req ) . "\r\n\r\n";
//
//$fp = fsockopen( 'www.sandbox.paypal.com', 80, $errno, $errstr, 30 );

$postdata="";
foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
$postdata .= "cmd=_notify-validate";
$curl = curl_init("https://www.sandbox.paypal.com/cgi-bin/webscr");
curl_setopt ($curl, CURLOPT_HEADER, 0);
curl_setopt ($curl, CURLOPT_POST, 1);
curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);
$response = curl_exec ($curl);

file_put_contents('payment.txt',"$response"."\n",FILE_APPEND );

curl_close ($curl);
if ($response != "VERIFIED") die("You should not do that ...");

foreach ( $_POST as $key => $val ) {

    file_put_contents('payment.txt',"$key => $val"."\n",FILE_APPEND );
}


$item_name          = $_POST['item_name'];
$item_number        = $_POST['item_number'];
$payment_status     = $_POST['payment_status'];
$payment_amount     = $_POST['mc_gross'];
$payment_currency   = $_POST['mc_currency'];
$txn_id             = $_POST['txn_id'];
$receiver_email     = $_POST['receiver_email'];
$payer_email        = $_POST['payer_email'];

//if( ! $fp ) {
//// HTTP ERROR
//} else {
//    fputs( $fp, $header . $req );
//    while( ! feof( $fp ) ) {
//        $res = fgets( $fp, 1024 );
//        if( strcmp( $res, "VERIFIED" ) == 0 ) {
            // check the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your Primary PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment

file_put_contents('payment.txt', "$_POST[payment_status]"."\n",FILE_APPEND );

            if( $_POST['payment_status'] == 'Completed'
                && noPaypalTransId( $_POST['txn_id'] )
                && $paypal_email == $_POST['receiver_email']
                && $paypal_currency == $_POST['mc_currency']
                && paymentAmountCorrect( $shipping, $_POST )
            )
            {
                createOrder( $_POST );
            }
//        } else if( strcmp( $res, "INVALID" ) == 0 ) {
//            // log for manual investigation
//
//        }
//    }
//    fclose( $fp );
//}
?>