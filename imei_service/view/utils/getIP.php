<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07/04/14
 * Time: 18:18
 * To change this template use File | Settings | File Templates.
 */
function getIP() {
    if( $ip = getenv('http_client_ip') ) return $ip;
    if( $ip = getenv('http_x_forwarded_for') ) {
        if( $ip == "" || $ip == 'unknown' ) {
            $ip = getenv('remote_addr');
        }
        if( count( $ip ) < 4 ) {
            $ip = "127.0.0.1";
        }
        return $ip;
    }
    if( $ip = getenv('remote_addr') ) {
        if( count( $ip ) < 4 ) {
            $ip = "127.0.0.1";
        }
        return $ip;
    }
}
?>