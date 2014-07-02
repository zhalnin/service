<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 14/12/13
 * Time: 21:53
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
require_once( 'base/Registry.php' );
//require_once( 'utils/utils_server_name.php');
ob_start();
header("Content-type:text/html; charset=utf8");

//$server = serverName();
$login = \account\base\SessionRegistry::getSession('login');
$auto = \account\base\SessionRegistry::getSession('auto');

//
//echo "<tt><pre>".print_r($_SESSION,true)."</pre></tt>";
//

if( $auto == 1 ) {
    echo "<br />Здравствуйте, $login , мы рады вас видеть на нашем сайте!<br />";
?>
    <a href="ind_logout_controller.php">Выход</a>
<?php
} else {
    header('Location: ind_controller.php' );
}
ob_get_flush();
?>

