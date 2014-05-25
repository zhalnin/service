<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 6/14/13
 * Time: 1:31 PM
 * To change this template use File | Settings | File Templates.
 */

require("woo/controller/Controller.php");
header("Content-type:text/html; charset=utf-8");
try{
    // Start our application
    \woo\controller\Controller::run();
} catch (\woo\base\AppException $e){
    echo $e;
} catch ( PDOException $e ) {
    echo $e;
}

//echo "<tt><pre>".print_r(, true)."</pre>";
?>
