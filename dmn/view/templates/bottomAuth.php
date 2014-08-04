<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/08/14
 * Time: 14:19
 */
 error_reporting(E_ALL & ~E_NOTICE);
?>
</div> <!-- end of tableform -->
</div>
<br/><br/></td>
<td width=10%>&nbsp;</td>
</tr>
<tr class=authors>
    <td colspan="3">
        CMS выполнена и поддерживается "alezhal-studio"
        <a href="#">alezhal</a></td></tr>
</table>


</body>
</html>
<script language='JavaScript1.1' type='text/javascript'>
    <!--
    /**
     * Удаление позиции
     * @param url
     * @param ask
     * @returns {boolean}
     */
    function delete_position( url, ask ) {
        if( confirm( ask ) ) {
            location.href=url;
        }
        return false;
    }

    /**
     * Для детального просмотра позиции
     * @param url
     * @param width
     * @param height
     */
    function show_detail(url,width,height) {
        var a;
        var b;
        var url;
        vidWindowWidth = width;
        vidWindowHeight = height;
        a = ( screen.height-vidWindowHeight )/5;
        b = ( screen.width-vidWindowWidth )/2;
        features = "top=" + a + ",left=" + b +
            ",width=" + vidWindowWidth +
            ",height=" + vidWindowHeight +
            ",toolbar=no,menubar=no,location=no" +
            ",directories=no,scrollbars=yes,resizable=no";
        window.open( url,'',features,true );
    }

    function show_img(id_position, width, height) {
        var a;
        var b;
        var url;
        vidWindowWidth = width;
        vidWindowHeight = height;
        a = (screen.height-vidWindowHeight)/5;
        b = (screen.width-vidWindowWidth)/2;
        features = "top="+a +",left="+b+
            ",width="+vidWindowWidth+
            ",height="+vidWindowHeight+
            ",toolbar=no,menubar=no,location=no,"+
            "directories=no,scrollbars=no,resizable=no";
        url = "../../show.php?id_position="+id_position;
        window.open(url,'',features,true);
    }
    //-->
</script>