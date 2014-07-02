<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/12/13
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */

/**
 * From Global variable construct server address
 * @return string
 */
function serverName() {
    $server_uri = preg_replace('|[^\/]*\.php(?:\?.*)?|','',$_SERVER['REQUEST_URI'] );
    $server_name = $_SERVER['SERVER_NAME'];
    if( ! empty( $_SERVER['SERVER_PORT'] ) ) {
        $port = ":".$_SERVER['SERVER_PORT'];
    } else {
        $port = "";
    }

    $server = $server_name.$port.$server_uri;
    return $server;
}