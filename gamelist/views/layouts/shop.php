<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 15:13
 */
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gamelist</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>Gamelist</h1>

<div class="cart">
    <p><b>Shopping cart</b></p>
    <p><?php print_r( $_SESSION['cart']); ?></p>
    <p><?php echo $_SESSION['total_items']; ?> items</p>
    <p><?php echo number_format( $_SESSION['total_price'], 2); ?> rub</p>
    <p><a href="index.php?view=checkout" >checkout</a></p>
</div>

<hr />

<?php //include( $_SERVER['DOCUMENT_ROOT'].'/'.'service/gamelist/views/'.$controller.'/'.$view.'.php' );  ?>
<?php include( $_SERVER['DOCUMENT_ROOT'].'/'.'patterns/GITservice/gamelist/views/'.$controller.'/'.$view.'.php' );  ?>

</body>
</html>