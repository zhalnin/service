<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/04/14
 * Time: 15:44
 * To change this template use File | Settings | File Templates.
 */

try {
    if( isset( $_POST['mode'] ) ) {
        if( ( $_POST['mode'] == 'preview' ) && isset( $_POST['text'] ) ) {

            echo $_POST['text'];
//            $nbsp = preg_replace('|&nbsp;|', '', $_POST['text'] );
//            $amp = preg_replace('|&amp;|', '&', $nbsp );
//            echo $amp;

        } elseif( $_POST['mode'] == 'submit' ) {

    //        $message = htmlspecialchars( stripslashes( $_POST['text'] ) );
//            $nbsp = preg_replace('|&nbsp;|', '', $_POST['text'] );
//            $amp = preg_replace('|&amp;|', '&', $nbsp );
//            echo $amp;
            echo $_POST['text'];
        }

    }
} catch ( Exception $ex ) {
    print $ex->getMessage();
}

?>