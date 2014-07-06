<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/06/14
 * Time: 20:08
 */

function checkIMEI( $imei ) {
    if( preg_match('|^[039][0-9]{14}$|i', $imei ) ) {
        return true;
    } else {
        return false;
    }
}
?>