<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 22:43
 */
error_reporting( E_ALL & ~E_NOTICE );

try {
    $dir = 'dmn/classes';
    if( $dh = opendir( $dir ) ) {
        while( ( $file = readdir( $dh ) ) != false ) {
            if( substr( $file, -4 ) === '.php' ) {
                require_once( "$dir/$file" );
            }
        }
    } else {
        throw new Exception( "Error in opendir - classes.php" );
    }
} catch( Exception $ex ) {
    echo "{$ex->getMessage()}";
}
?>