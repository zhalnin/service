<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/04/14
 * Time: 15:44
 * To change this template use File | Settings | File Templates.
 */

try {
    if( isset( $_POST['mode'] ) ) { // если установлен режим mode
        // если режим - это превью и поле text не пустое
        if( ( $_POST['mode'] == 'preview' ) && isset( $_POST['text'] ) ) {
            echo $_POST['text']; // выводим этот тексе
        }
    }
// ловим ошибки
} catch ( Exception $ex ) {
    print $ex->getMessage();
}

?>