<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 17:09
 */



?>

<h2>Checkout</h2>

<?php
if( $_SESSION['cart'] ) {
?>
    <form action="index.php?view=update_cart" method="post">
        <div id="items-box"	>

            <table id="items" summary="Full view of your basket including update options.">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Item Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach( $_SESSION['cart'] as $id => $qty ):
                    $product = findProduct( $id );
//                echo "<tt><pre> second INSERT - ".print_r( $product , true )."</pre></tt>";
                    ?>
                    <tr>
                        <td><?php echo $product['title']; ?></td>
                        <td class="price"><?php echo number_format( $product['price'], 2); ?> rub </td>
                        <td><input type="text" size="2" maxlength="2" name="<?php echo $id; ?>" value="<?php echo $qty; ?>" class="qty" /></td>
                        <td class="price"><?php echo number_format( $product['price'] * $qty, 2); ?> rub </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><input type="image" src="images/update.png" name="update" value="update" /></td>
                    <td><b>Subtotal:</b> <?php echo number_format( $_SESSION['total_price'], 2 ); ?>
                        <br /><b>Shipping:</b> <?php echo number_format( $shipping, 2 ); ?>
                        <br /><b>Grand Total:</b> <?php echo number_format( $_SESSION['total_price'] + $shipping, 2 ); ?></td>
                </tr>
                </tbody>
            </table>

        </div>
        <p></p>
    </form>

    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_cart" >
        <input type="hidden" name="upload" value="1" >
        <input type="hidden" name="business" value="zhalninpal-facilitator@me.com" >

        <?php
        $i = 1;
        foreach ( $_SESSION['cart'] as $id => $qty ):
            $product = findProduct( $id );
        ?>

        <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product['title']; ?>" >
        <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $product['id']; ?>" >
        <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $product['price']; ?>" >
        <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $qty; ?>" >

            <?php
            $i++;
            endforeach;
            ?>

        <input type="hidden" name="currency_code" value="RUB" >
        <input type="hidden" name="lc" value="RUS" >
        <input type="hidden" name="rm" value="2" >
        <input type="hidden" name="shipping_1" value="<?php echo $shipping; ?>" >
        <input type="hidden" name="return" value="http://zhalnin.tmweb.ru/gamelist/index.php?view=thankyou" >
        <input type="hidden" name="cancel_return" value="http://zhalnin.tmweb.ru/gamelist/" >
        <input type="hidden" name="notify_url" value="http://zhalnin.tmweb.ru/gamelist/paypal.php" >
        <input type="image" src="images/paynow.png" name="pay now" value="pay" class="pay-button" />
    </form>



<?php
} else {
    echo '<p>your cart is empty ... <a href="index.php">continue shopping</a>';
}
?>