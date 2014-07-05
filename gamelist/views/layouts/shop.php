<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 15:13
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>Gamelist - Bargians for gamers.</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" >
    <link href="stylesheets/cool_style.css" media="screen" rel="Stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper">

    <div id="header">

        <div id="logo">
            <a href="index.php"><img src="images/logo.png" alt="Game list" /></a>
        </div>
        <div id="consoles">
            <img src="images/consoles.png" alt="xbox360 PS3 WII" />
        </div>

        <div id="blurb">
				 <span class="blurb-title">
					Gamelist - Bargains for gamers &trade
				  </span>
				  <span class="blurb-txt">
                        Welcome to Gamelist .. the coolest and cheapest video game list on the Net!<br />
                      If you have any questions feel free to email me <b>imei_service@icloud.com</b>
				  </span>
        </div>


    </div>

    <div id="sidebar">
        <div id="cart">
			   <span id="cartprice">
			        <?php echo $_SESSION['total_items']; ?>  item <br/> <?php echo number_format( $_SESSION['total_price'], 2); ?> руб.
			   </span>
            <div id="checkout">
                <a href="index.php?view=checkout" ><img src="images/checkout-btn.png" alt="checkout" /></a>
            </div>
        </div>
        <img src="images/paypal.png" alt="payments by paypal" id="paypal" />
    </div>


<<<<<<< HEAD
    <div id="main">
=======
<?php include( $_SERVER['DOCUMENT_ROOT'].'/'.'service/gamelist/views/'.$controller.'/'.$view.'.php' );  ?>
<?php //include( $_SERVER['DOCUMENT_ROOT'].'/'.'patterns/GITservice/gamelist/views/'.$controller.'/'.$view.'.php' );  ?>
>>>>>>> FETCH_HEAD


        <?php //include( $_SERVER['DOCUMENT_ROOT'].'/'.'service/gamelist/views/'.$controller.'/'.$view.'.php' );  ?>
        <?php include( $_SERVER['DOCUMENT_ROOT'].'/'.'patterns/GITservice/gamelist/views/'.$controller.'/'.$view.'.php' );  ?>



    </div>

    <div id="footer">
        &copy 2012-2014 alezhal-studio
    </div>

</div>
</body>

</html>

