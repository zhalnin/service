<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/06/14
 * Time: 13:20
 */
try {

foreach ($products  as $product):?>

<div class="product">
    <h3><?php echo $product['title']; ?> - rub <?php echo number_format( $product['price'], 2 ); ?></h3>
    <p><?php echo $product['body']; ?></p>
    <p><a href="index.php?view=add_to_cart&id=<?php echo $product['id']; ?>">add to cart</a></p>
</div>

<?php endforeach;

} catch ( PDOException $ex ) {
print $ex->getMessage();
} catch ( Exception $ex ) {
print $ex->getMessage();
}
?>