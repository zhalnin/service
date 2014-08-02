<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31.03.12
 * Time: 23:59
 * To change this template use File | Settings | File Templates.
 */

namespace dmn\view\utils;
error_reporting( E_ALL & ~E_NOTICE );

/////////////////////////////////////////////////////////////
// Функция генерирует пароль,
// $number - количество символов в пароле
//////////////////////////////////////////////////////////

function generatePassword($number = 10) {
    $arr = array('a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','q','r','s',
        't','u','v','w','x','y','z',
        'A','B','C','D','E','F',
        'G','H','I','J','K','L',
        'M','N','O','P','Q','R','S',
        'T','U','V','W','X','Y','Z',
        '1','2','3','4','5','6',
        '7','8','9','0','_');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++) {
        // Вычисляем случайный индекс массива
        $index = rand( 0, count($arr) - 1 );
        $pass .= $arr[$index];
    }
    return $pass;
}
?>