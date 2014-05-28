<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 18:35
 * To change this template use File | Settings | File Templates.
 */
error_reporting( E_ALL & ~E_NOTICE );

try {
    $dir = "imei_service/mapper";
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

