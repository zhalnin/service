<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 11:20
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем изображения параграфа
require_once( 'dmn/domain/ArtParagraphImg.php' );
// Подключаем параграф
require_once( 'dmn/domain/ArtParagraph.php' );
// Подключаем позиции
require_once( 'dmn/domain/ArtUrl.php' );

/**
 * Class CatalogDelete
 * Для удаления блока каталога по id
 * @package dmn\command
 */
class ArtCatalogDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idpar  = intval( $request->getProperty( 'idpar' ) ); // родительский id каталога
        $idc    = intval( $request->getProperty( 'idc' ) ); // id подкаталога
        $page   = intval( $request->getProperty( 'page' ) ); // страница в навигации
        if( $idc ) { // если передан id каталога


            $paragraphImg = \dmn\domain\ArtParagraphImg::findParent( $idc ); // находим изображения, если есть

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
            $paragraph = \dmn\domain\ArtParagraph::findParent( $idc );
            if( is_object( $paragraph ) ) {
                foreach ( $paragraph as $line ) {
                    $line->markDeleted(); // отмечаем для удаления
                }
            }

            // находим позицию по заданному id
            $position = \dmn\domain\ArtUrl::findParent( $idc );
            // удаление параграфа
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
            if( is_object( $position ) ) {
                foreach ( $position as $line ) {
                    $line->markDeleted(); // отмечаем для удаления
                }
            }

            // находим подкаталоги по id
            $subCatalog = \dmn\domain\ArtCatalog::findParent( $idc );
            if( is_object( $subCatalog ) ) { // если есть
                foreach ( $subCatalog as $line ) {
                    $line->markDeleted(); // отмечаем для удаления
                }
            }

            // находим каталог по id
            $catalog = \dmn\domain\ArtCatalog::findCatalog( $idc );
            if( is_object( $catalog ) ) { // если есть
                foreach ( $catalog as $cat ) {
                    $cat->markDeleted(); // отмечаем для удаления
                }
            }
//            echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";

            $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idc=$idc&idpar=$idpar&page=$page" ); // перегружаем страничку
//             возвращаем статус и переадресацию на messageSuccess
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>