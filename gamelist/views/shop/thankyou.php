<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 03/07/14
 * Time: 23:00
 */
if( ! empty( $_POST ) ):
?>

<div id="thankyou">
 <span class="body-txt"><p><b><?php echo $_POST['first_name']; ?> <?php echo $_POST['last_name']; ?></b>, thank you for your order. Please check your email <b><?php echo $_POST['payer_email']; ?></b> for your receipt </p></span>
</div>
<?php endif; ?>