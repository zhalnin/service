<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 17:35
 */

namespace imei_service\view\utils;

/**
 * Переводим русские буквы в транслит
 * @param $str
 * @param $from
 * @param $to
 * @return mixed
 */
function encodestring($st) {
    // Replace single symbol.
    $st= mb_strtr($st,"абвгдезийклмнопрстуфхъы",
        "abvgdezijklmnoprstufh#y");
    $st= mb_strtr($st,"АБВГДEЗИЙКЛМНОПРСТУФХЪЫ",
        "ABVGDEZIJKLMNOPRSTUFH#Y");
    // Replace multiple symbol.
    $st=strtr($st,
        array(
            "ж"=>"zh","ц"=>"ts","ч"=>"ch","ш"=>"sh",
            "щ"=>"shch","ь"=>"'","ю"=>"yu","я"=>"ya",
            "Ж"=>"ZH","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH",
            "Щ"=>"SHCH","Ь"=>"'","Ю"=>"YU","Я"=>"YA",
            "э"=>"je", "Э"=>"JE", "ё"=>"jo", "Ё"=>"JO"

        )
    );
//    echo "<tt><pre>".print_r(gettype($st), true)."</pre></tt>";
    // Return result.
    return $st;

}
function mb_strtr($str, $from, $to) {
    return str_replace(mb_str_split($from), mb_str_split($to), $str);
}
function mb_str_split($str) {
    return preg_split('~~u', $str, null, PREG_SPLIT_NO_EMPTY);
}
?>