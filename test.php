<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31/05/14
 * Time: 14:30
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view\utils;




$_SESSION['test'][1][33]='test';

$first = array(1,2,3,4,5);
$second = array(11,22,33);
foreach ( $_SESSION['test'] as $firs => $secon ) {
    print_r($firs);
    foreach ($secon as $fir => $sec ) {
        print_r($sec);
    }

}

//print $_SESSION['test'][1][33];
?>