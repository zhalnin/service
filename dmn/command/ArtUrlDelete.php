<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/08/14
 * Time: 20:23
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
require_once( 'dmn/domain/ArtParagraphImg.php' );
require_once( 'dmn/domain/ArtParagraph.php' );

/**
 * Class ArtUrlDelete
 * Для удаления позиции из каталога по id
 * @package dmn\command
 */
class ArtUrlDelete  extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp  = intval( $request->getProperty( 'idp' ) ); // id позиции
        $idc  = intval( $request->getProperty( 'idc' ) ); // id каталога
        $page = intval( $_GET['page'] ); // номер страницы навигации
        if( ! empty( $idp ) &&  ! empty( $idc ) ) { // если переданы нужные id

            $paragraphImg = \dmn\domain\ArtParagraphImg::find( $idp, $idc ); // находим изображения, если есть

            if( is_object( $paragraphImg )  ) { // если есть
                foreach ( $paragraphImg as $img ) {
                    $bigOld = $img->getBig();
                    $smallOld = $img->getSmall();
                    if( ! empty( $bigOld ) ) {
                        // Удаляем старые изображения
                        if( file_exists(  "imei_service/view/".$bigOld ) ) {
//                            @unlink( "imei_service/view/".$bigOld );
                        }
                    }
                    if( ! empty( $smallOld ) ){
                        // Удаляем старые изображения
                        if( file_exists(  "imei_service/view/".$smallOld ) ) {
//                            @unlink( "imei_service/view/".$smallOld );
                        }
                    }
                    $img->markDeleted();
                }
            }


            echo "<tt><pre>".print_r( $img, true)."</pre></tt>";


            $paragraph = \dmn\domain\artParagraph::find( $idp, $idc ); // находим элементы по заданному id_news
            // удаление параграфа
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
        echo "<tt><pre>".print_r($paragraph, true)."</pre></tt>";
            $paragraph->markDeleted();
//


//
//
//
//            $paragraph = \dmn\domain\artParagraph::find( $idph, $idc, $idp ); // находим элементы по заданному id_news
//        echo "<tt><pre>".print_r($paragraph, true)."</pre></tt>";
//            // удаление параграфа
////            $news->finder()->delete( $news );
////            \dmn\domain\ObjectWatcher::instance()->performOperations();
//            $paragraph->markDeleted();
////
//            $this->reloadPage( 0, "dmn.php?cmd=ArtParagraph&idph=$idph&idc=$idc&idp=$idp&page=$page" ); // перегружаем страничку
////            // возвращаем статус и переадресацию на messageSuccess
//            return self::statuses( 'CMD_OK' );

        }
    }
}
?>