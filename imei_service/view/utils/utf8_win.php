<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 06/08/14
 * Time: 20:16
 */
namespace imei_service\view;
error_reporting(E_ALL & ~E_NOTICE);

// Преобразование кодировки UTF-8 в Windows-1251
function utf8_win( $str ) {
    $win = array("а","б","в","г","д","е","ё","ж","з","и",
        "й","к","л","м","н","о","п","р","с","т",
        "у","ф","х","ц","ч","ш","щ","ъ","ы","ь",
        "э","ю","я","А","Б","В","Г","Д","Е","Ё",
        "Ж","З","И","Й","К","Л","М","Н","О","П",
        "Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ",
        "Ъ","Ы","Ь","Э","Ю","Я","");
    $utf8 = array("\xD0\xB0","\xD0\xB1","\xD0\xB2","\xD0\xB3","\xD0\xB4",
        "\xD0\xB5","\xD0\x91","\xD0\xB6","\xD0\xB7","\xD0\xB8",
        "\xD0\xB9","\xD0\xBA","\xD0\xBB","\xD0\xBC","\xD0\xBD",
        "\xD0\xBE","\xD0\xBF","\xD1\x80","\xD1\x81","\xD1\x82",
        "\xD1\x83","\xD1\x84","\xD1\x85","\xD1\x86","\xD1\x87",
        "\xD1\x88","\xD1\x89","\xD1\x8A","\xD1\x8B","\xD1\x8C",
        "\xD1\x8D","\xD1\x8E","\xD1\x8F","\xD0\x90","\xD0\x91",
        "\xD0\x92","\xD0\x93","\xD0\x94","\xD0\x95","\xD0\x81",
        "\xD0\x96","\xD0\x97","\xD0\x98","\xD0\x99","\xD0\x9A",
        "\xD0\x9B","\xD0\x9C","\xD0\x9D","\xD0\x9E","\xD0\x9F",
        "\xD0\xA0","\xD0\xA1","\xD0\xA2","\xD0\xA3","\xD0\xA4",
        "\xD0\xA5","\xD0\xA6","\xD0\xA7","\xD0\xA8","\xD0\xA9",
        "\xD0\xAA","\xD0\xAB","\xD0\xAC","\xD0\xAD","\xD0\xAE",
        "\xD0\xAF","+");
    return str_replace(  $utf8, $win, $str );
}
?>