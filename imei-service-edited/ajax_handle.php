<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/04/14
 * Time: 15:44
 * To change this template use File | Settings | File Templates.
 */

if( ( $_POST['mode'] == 'preview' ) && isset( $_POST['text'] ) ) {

    echo $_POST['text'];

} elseif( $_POST['mode'] == 'submit' ) {
    echo "Добавим к БД";
    echo "<br />";
    echo "ok";
}

?>