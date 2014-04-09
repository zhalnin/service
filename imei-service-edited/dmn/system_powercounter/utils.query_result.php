<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.05.12
 * Time: 21:44
 * To change this template use File | Settings | File Templates.
 */
 
function query_result($query)
{
    $tot = mysql_query($query);
    if(!$tot)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                            "Ошибка выполнения запроса");
    }
    if(mysql_num_rows($tot))
    {
        return @mysql_result($tot, 0);
    }
    else
    {
        return false;
    }
}
?>