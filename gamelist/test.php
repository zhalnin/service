<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 18:36
 */

include( 'base_fns.php' );

file_put_contents('payment.txt','start payment'."\n",FILE_APPEND );

$paypal_email = "zhalninpal-facilitator@me.com";
$paypal_currency = 'RUB';
$shipping = 10.00;

function noPaypalTransId( $trans_id ) {

    file_put_contents('payment.txt','noPaypalTransId()'."\n",FILE_APPEND );

    $sth = getStatement( 'SELECT id FROM orders WHERE paypal_trans_id = ?' );
    $sth->execute( array( $trans_id ) );
    $num_result = $sth->fetch();
    if( $num_result == 0 ) {
        return true;
    }
    return false;
}

function paymentAmountCorrect( $shipping, $params ) {

    file_put_contents('payment.txt','paymentAmountCorrect()'."\n",FILE_APPEND );

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





$req = 'cmd=_notify-validate';

foreach ( $_POST as $key => $value ) {
    $value = urlencode(stripslashes( $value ) );
    $req .= "&$key=$value";
}

file_put_contents('payment.txt',"$req"."\n",FILE_APPEND );

$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen( $req ) . "\r\n\r\n";

$fp = fsockopen( 'www.sandbox.paypal.com', 80, $errno, $errstr, 30 );

$item_name          = $_POST['item_name'];
$item_number        = $_POST['item_number'];
$payment_status     = $_POST['payment_status'];
$payment_amount     = $_POST['mc_gross'];
$payment_currency   = $_POST['mc_currency'];
$txn_id             = $_POST['txn_id'];
$receiver_email     = $_POST['receiver_email'];
$payer_email        = $_POST['payer_email'];

if( ! $fp ) {
// HTTP ERROR
    file_put_contents('payment.txt','HTTP ERROR'."\n",FILE_APPEND );
} else {
    fputs( $fp, $header . $req );
    while( ! feof( $fp ) ) {
        $res = fgets( $fp, 1024 );
        if( strcmp( $res, "VERIFIED" ) == 0 ) {

            file_put_contents('payment.txt','VERIFIED'."\n",FILE_APPEND );

            // check the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your Primary PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment
            if( $_POST['payment_status'] == 'Completed'
                && noPaypalTransId( $_POST['txn_id'] )
                && $paypal_email == $_POST['receiver_email']
                && $paypal_currency == $_POST['mc_currency']
                && paymentAmountCorrect( $shipping, $_POST )
            ) {

                file_put_contents('payment.txt','Completed'."\n",FILE_APPEND );

                $to = 'zhalninpal@me.com';
                $subject = 'Payment';
                $message = 'Hello '.$req;
                $headers = 'From: zhalninpal-facilitator@me.com'."\r\n";
                $headers .= 'Reply-To: zhalninpal-facilitator@me.com'."\r\n";
                $headers .= 'X-Mailer: PHP/'.phpversion();
                mail( $to, $subject, $message, $headers );
                //createOrder( $_POST );
            } else {
                file_put_contents('payment.txt','NOT Completed'."\n",FILE_APPEND );
            }
                } else if( strcmp( $res, "INVALID" ) == 0 ) {
                    // log for manual investigation
            file_put_contents('payment.txt','INVALID'."\n",FILE_APPEND );

                }
            }
            fclose( $fp );
        }
        ?>


cmd=_notify-validate
&mc_gross=262.00
&protection_eligibility=Ineligible
&address_status=unconfirmed
&item_number1=2
&tax=0.00
&item_number2=1
&payer_id=U8BFYER8AL2GA
&address_street=%CC%C9%C3%C1+%F0%C5%D2%D7%C1%D1%2C+%C4%CF%CD+1%2C+%CB%D7%C1%D2%D4%C9%D2%C1+2
&payment_date=01%3A29%3A26+Jul+03%2C+2014+PDT
&payment_status=Pending
&charset=KOI8_R
&address_zip=127001
&mc_shipping=10.00
&mc_handling=0.00
&first_name=Aleksander
&mc_fee=20.22
&address_country_code=RU
&address_name=Pushkin+Aleksander
&notify_version=3.8
&custom=
&payer_status=verified
&business=zhalninpal-facilitator%40me.com
&address_country=Russia
&num_cart_items=2
&mc_handling1=0.00
&mc_handling2=0.00
&address_city=%ED%CF%D3%CB%D7%C1
&verify_sign=AX95uNfAnJtpsikTzAUpiuXkEkxdAVWxb.QbcnXze-SltGPclQ7dXfzi
&payer_email=zhalninpal-buyer%40me.com
&mc_shipping1=10.00
&mc_shipping2=0.00
&tax1=0.00
&tax2=0.00
&txn_id=728380123P720841T
&payment_type=instant
&last_name=Pushkin
&address_state=%ED%CF%D3%CB%D7%C1
&item_name1=Devil
&receiver_email=zhalninpal-facilitator%40me.com
&item_name2=God
&payment_fee=
&quantity1=3
&quantity2=3
&receiver_id=3999C624CN5BQ
&pending_reason=paymentreview
&txn_type=cart
&mc_gross_1=142.00
&mc_currency=RUB
&mc_gross_2=120.00
&residence_country=RU
&test_ipn=1
&transaction_subject=
&payment_gross=
&ipn_track_id=41562de6aa180


VERIFIED
mc_gross => 262.00
protection_eligibility => Ineligible
address_status => unconfirmed
item_number1 => 2
tax => 0.00
item_number2 => 1
payer_id => U8BFYER8AL2GA
address_street => ���� ������, ��� 1, �������� 2
payment_date => 02:26:38 Jul 03, 2014 PDT
payment_status => Pending
charset => KOI8_R
address_zip => 127001
mc_shipping => 10.00
mc_handling => 0.00
first_name => Aleksander
mc_fee => 20.22
address_country_code => RU
address_name => Pushkin Aleksander
notify_version => 3.8
custom =>
payer_status => verified
business => zhalninpal-facilitator@me.com
address_country => Russia
num_cart_items => 2
mc_handling1 => 0.00
mc_handling2 => 0.00
address_city => ������
verify_sign => A0NQkwcK5B7IR-M-9xgYeGAVimNQA5qZWJScmWgIvah3RIWIA3N.yFk0
payer_email => zhalninpal-buyer@me.com
mc_shipping1 => 10.00
mc_shipping2 => 0.00
tax1 => 0.00
tax2 => 0.00
txn_id => 8UU10473LD5132738
payment_type => instant
last_name => Pushkin
address_state => ������
item_name1 => Devil
receiver_email => zhalninpal-facilitator@me.com
item_name2 => God
payment_fee =>
quantity1 => 3
quantity2 => 3
receiver_id => 3999C624CN5BQ
pending_reason => paymentreview
txn_type => cart
mc_gross_1 => 142.00
mc_currency => RUB
mc_gross_2 => 120.00
residence_country => RU
test_ipn => 1
transaction_subject =>
payment_gross =>
ipn_track_id => b982412f3ee9

