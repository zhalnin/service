<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/06/14
 * Time: 20:19
 */
function checkUDID( $udid ) {
    if( preg_match('|^[a-z0-9]{40}$|i', $udid ) ) {
        return true;
    } else {
        return false;
    }
}
?>