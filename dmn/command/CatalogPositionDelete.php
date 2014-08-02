<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 14:11
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CatalogPosition' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );

/**
 * Class CatalogPositionDelete
 * Для удаления позиции из каталога по id
 * @package dmn\command
 */
class CatalogPositionDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp = $request->getProperty( 'idp' );
        if( $idp ) { // если передан id_news
            $catalogPosition = \dmn\domain\CatalogPosition::findDetail( $idp ); // находим элементы по заданному id_news
//        echo "<tt><pre>".print_r($catalogPosition, true)."</pre></tt>";
            // удаление блока новостей
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
            $catalogPosition->markDeleted(); // отмечаем для удаления

            $this->reloadPage( 0, "dmn.php?cmd=CatalogPosition&idc=$_REQUEST[idc]&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
            // возвращаем статус и переадресацию на messageSuccess
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>