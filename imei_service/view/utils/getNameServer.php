<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 17:20
 */
namespace imei_service\view\utils;

function getNameServer() {
    $name = $_SERVER['SERVER_NAME'];
    if( stripos( $name, 'www' ) === 0 ) {
        $name = substr_replace( $name, '', 0, 4 );
    }

    // Для дебаггинга
//    if( ! empty( $_SERVER['SERVER_PORT'] ) ) {
//        $port = ':'.$_SERVER['SERVER_PORT'];
//    } else {
//        $port = '';
//    }
    $path = $_SERVER['PHP_SELF'];
    preg_match('|(.*)(?:\/.*\.php)|i', $path, $ar);
//    return "http://".$name.$port.$ar[1]."/";
    return "http://".$name.$ar[1]."/";
    // конец


//    return $name;
}

function getNameServerWithExt() {
    $name = $_SERVER['SERVER_NAME'];
    if( stripos( $name, 'www' ) === 0 ) {
        $name = substr_replace( $name, '', 0, 4 );
    }

    // Для дебаггинга
//    if( ! empty( $_SERVER['SERVER_PORT'] ) ) {
//        $port = ':'.$_SERVER['SERVER_PORT'];
//    } else {
//        $port = '';
//    }
    $path = $_SERVER['PHP_SELF'];
    return "http://".$name.$path;
//    return "http://".$name.$port.$path;
    // конец


//    return $name;
}

?>