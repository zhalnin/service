<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/08/14
 * Time: 19:19
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );

/**
 * Class ArtParagraphDelete
 * Для удаления позиции из каталога по id
 * @package dmn\command
 */
class ArtParagraphDelete  extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp  = intval( $request->getProperty( 'idp' ) ); // id позиции
        $idc  = intval( $request->getProperty( 'idc' ) ); // id каталога
        $idph = intval( $request->getProperty( 'idph' ) ); // id параграфа
        $page = intval( $_GET['page'] ); // номер страницы навигации
        if( ! empty( $idp ) &&  ! empty( $idc ) &&  ! empty( $idph ) ) { // если переданы нужные id
            $paragraphImg = \dmn\domain\ArtParagraphImg::find( $idph, $idc, $idp );

            $bigOld = $paragraphImg->getBig();
            $smallOld = $paragraphImg->getSmall();
            if( ! empty( $bigOld ) ) {
                // Удаляем старые изображения
                if( file_exists(  "imei_service/view/".$bigOld ) ) {
                    @unlink( "imei_service/view/".$bigOld );
                }
            }
            if( ! empty( $smallOld ) ){
                // Удаляем старые изображения
                if( file_exists(  "imei_service/view/".$smallOld ) ) {
                    @unlink( "imei_service/view/".$smallOld );
                }
            }
            $paragraphImg->markDeleted(); // отмечаем для удаления


            $paragraph = \dmn\domain\artParagraph::find( $idph, $idc, $idp ); // находим элементы по заданному id_news
//        echo "<tt><pre>".print_r($paragraph, true)."</pre></tt>";
            // удаление параграфа
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
            $paragraph->markDeleted(); // отмечаем для удаления
//
            $this->reloadPage( 0, "dmn.php?cmd=ArtParagraph&idph=$idph&idc=$idc&idp=$idp&page=$page" ); // перегружаем страничку
//            // возвращаем статус и переадресацию на messageSuccess
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>