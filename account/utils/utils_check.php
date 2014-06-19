<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/12/13
 * Time: 21:54
 * To change this template use File | Settings | File Templates.
 */

function checkEmail($expr) {
    if( ! preg_match('|^[-a-zA-Z0-9_\.]+@[-a-zA-Z0-9_\.]+\.[a-z]{2,6}$|', $expr ) ) {
        return "Email введен в неверном формате";
    }
}

