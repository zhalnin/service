<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31/01/14
 * Time: 15:54
 * To change this template use File | Settings | File Templates.
 */

$dir = "woo/domain";
$dh = opendir( "$dir" );
while( $file = readdir( $dh) ) {
    if( substr( $file, -4 ) == ".php" ) {
        require_once ( "$dir/$file" );
    }
}
