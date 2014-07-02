<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/06/14
 * Time: 13:13
 */
try {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>Gamelist - Bargians for gamers.</title>
<!--    <link href="stylesheets/application.css" media="screen" rel="Stylesheet" type="text/css" />-->
</head>

<body>
<h1>Gamelist</h1>

<div class="cart">
    <p><b>ShoppingCart</b></p>
    <p><?php print_r( $_SESSION['cart']); ?></p>
    <p><?php echo $_SESSION['total_items']; ?> items</p>
    <p> rub <?php echo number_format( $_SESSION['total_price'], 2 ); ?></p>
    <p><a href="index.php?view=checkout">checkout</a></p>
</div>

<hr />

<?php include( $_SERVER['DOCUMENT_ROOT'].'/'.'patterns/talking/gamelist/views/'.$controller.'/'.$view.'.php' ); ?>

</body>

</html>

<?php
} catch ( PDOException $ex ) {
    print $ex->getMessage();
} catch ( Exception $ex ) {
    print $ex->getMessage();
}
?>