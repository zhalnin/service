<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/08/14
 * Time: 20:23
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'ArtUrl' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем изображения параграфа
require_once( 'dmn/domain/ArtParagraphImg.php' );
// Подключаем параграф
require_once( 'dmn/domain/ArtParagraph.php' );

/**
 * Class ArtUrlDelete
 * Для удаления позиции из каталога по id
 * @package dmn\command
 */
class ArtUrlDelete  extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp    = intval( $request->getProperty( 'idp' ) ); // id позиции
        $idc    = intval( $request->getProperty( 'idc' ) ); // id каталога
        $idpar  = intval( $request->getProperty( 'idpar' ) ); // родительский id
        $page   = intval( $_GET['page'] ); // номер страницы навигации
        if( ! empty( $idp ) &&  ! empty( $idc ) ) { // если переданы нужные id

            $paragraphImg = \dmn\domain\ArtParagraphImg::find( $idp, $idc ); // находим изображения, если есть

            if( is_object( $paragraphImg )  ) { // если есть
                foreach ( $paragraphImg as $img ) {
                    $bigOld = $img->getBig();
                    $smallOld = $img->getSmall();
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
                    $img->markDeleted(); // отмечаем для удаления
                }
            }

            // находим параграфы по заданному id, если это статья, а не ссылка
            $paragraph = \dmn\domain\ArtParagraph::find( $idp, $idc );
            if( is_object( $paragraph ) ) {
                foreach ( $paragraph as $line ) {
                    $line->markDeleted(); // отмечаем для удаления
                }
            }

            // находим позицию по заданному id
            $position = \dmn\domain\ArtUrl::find( $idp, $idc );
            // удаление параграфа
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
            if( is_object( $position ) ) {
                $position->markDeleted(); // отмечаем для удаления
            }

//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
            $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idpar=$idpar&idc=$idc&idp=$idp&page=$page" ); // перегружаем страничку
            // возвращаем статус и переадресацию на messageSuccess
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>