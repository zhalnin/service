<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07/04/14
 * Time: 16:51
 * To change this template use File | Settings | File Templates.
 */

function getVerBrowser() {
    $browser = getenv('HTTP_USER_AGENT');
    preg_match("/(MSIE|OPR|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $browser, $browser_info);
    list($agent, $version) = $browser_info;
    if( preg_match('|Safari|', $browser) ) {
        if( preg_match('|Version|', $browser ) ) {
            $version = preg_match('|Version/(.*) |', $browser, $arr );
            return "Safari ver. {$arr[1]}";
        } elseif( preg_match('|OPR|', $browser ) ) {
            $version = preg_match('|OPR/(.*)|', $browser, $arr );
            return "Opera ver. {$arr[1]}";
        } else {
            $version = preg_match('|Chrome/(.*) |', $browser, $arr );
            return "Chrome ver. {$arr[1]}";
        }
    } elseif( preg_match('|Firefox|', $browser ) ) {
        $version = preg_match('|Firefox/(.*)|', $browser, $arr );
        return "Firefox ver. {$arr[1]}";
    } elseif( preg_match('|Opera|', $browser ) ) {
        $version = preg_match('|Version/(.*)|', $browser, $arr );
        return "Opera ver. {$arr[1]}";
    } elseif( preg_match('|MSIE|', $browser ) ) {
        $version = preg_match('|MSIE ([\d\.]*)|i', $browser, $arr );
        return "Internet Explorer ver. {$arr[1]}";
    }
    return $agent." ver. ".$version;
}


?>