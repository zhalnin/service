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
        <table id="items">
            <thead>
                <th>Item</th>
                <th>Item Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </thead>
            <tbody>
            <?php foreach( $_SESSION['cart'] as $id => $qty ):
                        $product = findProduct( $id );
//                echo "<tt><pre> second INSERT - ".print_r( $product , true )."</pre></tt>";
                ?>
                <tr>
                    <td><?php echo $product['title']; ?></td>
                    <td><?php echo number_format( $product['price'], 2); ?> rub </td>
                    <td><input type="text" size="2" maxlength="2" name="<?php echo $id; ?>" value="<?php echo $qty; ?>" /></td>
                    <td><?php echo number_format( $product['price'] * $qty, 2); ?> rub </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p><input type="submit" name="update" value="update" /></p>
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
        <input type="hidden" name="return" value="http://zhalnin.tmweb/gamelist/index.php" >
        <input type="hidden" name="cancel_return" value="http://zhalnin.tmweb/gamelist/" >
        <input type="hidden" name="notify_url" value="http://zhalnin.tmweb/gamelist/paypal.php" >
        <input type="submit" name="pay now" value="pay" >
    </form>



<?php
} else {
    echo '<p>your cart is empty ... <a href="index.php">continue shopping</a>';
}
?>