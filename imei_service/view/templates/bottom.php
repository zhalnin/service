<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 15:08
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view\templates;

 error_reporting(E_ALL & ~E_NOTICE);
?>



<div id="footer">
    <div id="footer-content">
        <ol id="breadcrumbs">
            <li>
                <p>Designed by alezhal-studio</p>
            </li>
            <li>
                |
            </li>
            <li>
                <p>Copyright © 2012 - 2014 All rights reserved.</p>
            </li>
        </ol>
    </div>
</div><!-- footer-->
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




