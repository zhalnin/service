<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.05.12
 * Time: 22:42
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);
// Массив временных интервалов
// "Сегодня"
$time[0]['begin']   = 1;
$time[0]['end']     = 0;
// "Вчера"
$time[1]['begin']   = 2;
$time[1]['end']     = 1;
// "За 7 дней"
$time[2]['begin']   = 7;
$time[2]['end']     = 0;
// "За 30 дней"
$time[3]['begin']   = 30;
$time[3]['end']     = 0;
// "За все время"
$time[4]['begin']   = 0;
$time[4]['end']     = 0;
?>