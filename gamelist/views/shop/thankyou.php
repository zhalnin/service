<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 03/07/14
 * Time: 23:00
 */
if( ! empty( $_POST ) ):
?>

<p><b><?php echo $_POST['first_name']; ?> <?php echo $_POST['last_name']; ?></b>, thank you for your order. Please check your email <?php echo $_POST['payer_email']; ?> for your receipt </p>
<?php endif; ?>