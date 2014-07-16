<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/07/14
 * Time: 23:12
 */
error_reporting( E_ALL & ~E_NOTICE );

try {
    $dir = "dmn/mapper";
    if( $dh = opendir( $dir ) ) {
        while( false !== ($file = readdir( $dh ) ) ) {
            if( substr( $file, -4 ) === '.php' ) {
                require_once( "$dir/$file" );
            }
        }
    } else {
        throw new Exception("Error in opendir");
    }

} catch ( Exception $ex ) {
    echo "{$ex->getMessage()}";
}

