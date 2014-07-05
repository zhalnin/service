<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 15:43
 */

//echo "<tt><pre>".print_r( $products, true )."</pre></tt>";

foreach ( $products as $product ):?>
    <div class="game">
                      <span class="game-pack">
                        <img src="images/<?php echo $product['image']; ?>" alt="halo 3" />
                        <a href="index.php?view=add_to_cart&id=<?php echo $product['id']; ?>" ><img src="images/buyme.png" alt="buy me" class="buyme" /></a>
                      </span>
                      <span class="xbox-title">
    <?php echo $product['title']; ?> - <?php echo number_format( $product['price'], 2 ); ?> руб
    </span>
                      <span class="game-txt">
                        <?php echo $product['body']; ?>
                      </span>
                   </div>
<?php endforeach; ?>
