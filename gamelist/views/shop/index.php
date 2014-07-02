<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 15:43
 */

//echo "<tt><pre>".print_r( $products, true )."</pre></tt>";
foreach ( $products as $product ):?>
    <div class="product">
        <h3><?php echo $product['title']; ?> - rub <?php echo number_format( $product['price'], 2 ); ?></h3>
        <p><?php echo $product['body']; ?></p>
        <p><a href="index.php?view=add_to_cart&id=<?php echo $product['id']; ?>" >add_to_cart</a></p>
    </div>
<?php endforeach; ?>
