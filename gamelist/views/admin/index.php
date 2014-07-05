<h1>Gamelist Orders</h1>
<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 15:43
 */


//echo "<tt><pre>".print_r( $products, true )."</pre></tt>";
foreach ( $orders as $order ):?>
    <div class="order">
        <h2>Order ID: ( <?php echo $order['id']; ?> ) created at: <?php echo $order['created_at']; ?></h2>
        <h3><?php echo $order['firstname']; ?> <?php echo $order['lastname']; ?> - rub <?php echo number_format( $order['amount'], 2 ); ?></h3>
        <h4><?php echo $order['email']; ?></h4>
        <p><b>Status: </b> <?php echo $order['status']; ?>, <b>Paypal Trans ID: </b> <?php echo $order['paypal_trans_id']; ?></p>
        <address><?php echo $order['address']; ?> <?php echo $order['city']; ?> <?php echo $order['state']; ?> <?php echo $order['zip_code']; ?> <?php echo $order['country']; ?></address>
    </div>

    <?php $items = findItems( $order['id'] ); ?>

    <?php foreach ( $items as $item ): ?>
        <p><?php echo $item['title']; ?> X <?php echo $item['qty']; ?></p>
    <?php endforeach; ?>

    <hr />
<?php endforeach; ?>
