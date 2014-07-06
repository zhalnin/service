<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/06/14
 * Time: 23:26
 */

    function checkEmail( $email ) {
        if( preg_match('|[-a-z0-9_\.]+@[-a-z0-9_\.]+\.[a-z]{2,6}|i', $email ) ) {
            return true;
        } else {
            return false;
        }
    }
?>