<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31.05.12
 * Time: 23:30
 * To change this template use File | Settings | File Templates.
 */
 
// Помещаем время окончания вычислений в переменную $end_time
$part_time = explode(' ',microtime());
$end_time = $part_time[1].substr($part_time[0],1);
?>
<br><br></td>
        <td width=10%>&nbsp;</td>
    </tr>
    <tr class=authors>
        <td colspan="3">
            Система статистики PowerCounter разработана и поддерживается alezhal-студией.
            <a href="http://cyborg-ws.homeip.net">cyborg-ws.homeip.net</a> Время генерации страницы <?= sprintf("%.2f", $end_time - $begin_time) ?> секунды</td>
    </tr>
</table>
</body>
</html>
<script type="text/javascript">
<!--
    function delete_position(url, ask)
    {
        if(confirm(ask))
        {
            location.href = url;
        }
        return false;
    }
//-->
</script>