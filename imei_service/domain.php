<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/05/14
 * Time: 22:29
 */

$dir = "imei_service/domain";
$dh = opendir( "$dir" );
while( $file = readdir( $dh ) ) {
    if( substr( $file, -4 ) == ".php" ) {
        require_once( "$dir/$file" );
    }
}

?>