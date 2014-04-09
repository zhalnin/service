<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31.05.12
 * Time: 16:53
 * To change this template use File | Settings | File Templates.
 */
function where_interval($begin = 1, $end = 0, $tbl = '')
{
    $begin  = intval($begin);
    $end    = intval($end);
    if(!empty($tbl)) $tbl = "$tbl.";

    if($begin == 1 && $end == 0)
    {
        return " WHERE {$tbl}putdate LIKE CONCAT(DATE_FORMAT(NOW(), '%Y-%m-%d'), '%') ";
    }
    else
    {
        if($end != 0) $tmp1 = "{$tbl}putdate <= DATE_FORMAT(NOW(), '%Y-%m-%d') - INTERVAL '".($end - 1)."' DAY";
        else $tmp1 = "{$tbl}putdate <= DATE_FORMAT(NOW(), '%Y-%m-%d') - INTERVAL '$end' DAY";
        if($begin == 0) $tmp2 = "";
        else $tmp2 = " AND {$tbl}putdate >= DATE_FORMAT(NOW(), '%Y-%m-%d') - INTERVAL '".($begin - 1)."' DAY";

        return " WHERE $tmp1 $tmp2 ";
    }
}
?>