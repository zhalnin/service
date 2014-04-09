<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.05.12
 * Time: 21:34
 * To change this template use File | Settings | File Templates.
 */
 
function begin_day_arch($tbl, $tbl_arch, $column = 'putdate')
{

    // Месяц
    if(substr($tbl_arch, -5) == 'month') $interval = '+ INTERVAL 1 MONTH';
    // Неделя
    if(substr($tbl_arch, -4) == 'week') $interval = '+ INTERVAL 1 WEEK';
    // Получаем последнюю дату, которая была заархивирована
    $query = "SELECT UNIX_TIMESTAMP(MAX($column{$interval})) FROM $tbl_arch";
    $last_date = query_result($query);
//    echo "<tt><pre>".print_r($last_date,true)."</pre></tt>";
    if(empty($last_date))
    {
        $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data FROM $tbl";
        $begin_date = query_result($query);

        if(!empty($begin_date))
        {
            // Если запуск первый - берем дату из $tbl
            $last_date = $begin_date;
        }
        else
        {
            // Иначе берем текущие сутки
            $last_date = time();
        }
    }
    return $last_date;
}
?>