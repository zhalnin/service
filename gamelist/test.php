<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/07/14
 * Time: 17:04
 */

include( 'base_fns.php' );

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


//$params = array( 'num_cart_items'   =>2,
//                'item_number1'      =>1,
//                'quantity1'         =>2,
//                'item_number2'      =>2,
//                'quantity2'         =>2,
//                'mc_gross'          =>178 );

$params = array(    'first_name'        =>'Aleksei',
                    'last_name'         =>'Zhalnin',
                    'payer_email'       =>'zhalninpal-buyer@me.com',
                    'address_country'   =>'Russia',
                    'address_street'    =>'Severniy 16-2-150',
                    'address_city'      =>'St.-Petersburg',
                    'address_zip'       =>194354,
                    'address_state'     =>'Leningrad',
                    'payment_status'    =>'Completed',
                    'mc_gross'          =>178,
                    'txn_id'            =>100,
                    'num_cart_items'    =>2,
                    'item_number1'      =>1,
                    'quantity1'         =>2,
                    'item_number2'      =>2,
                    'quantity2'         =>2
);

createOrder( $params );


//$time = new DateTime();

//echo "<tt><pre>".print_r( date('Y-m-d H:i:s'), true )."</pre></tt>";
//echo "<tt><pre>".print_r( $time->format('Y-m-d H:i:s'), true )."</pre></tt>";
//echo "<tt><pre>".print_r(paymentAmountCorrect(10.00, $params ), true)."</pre></tt>";
?>