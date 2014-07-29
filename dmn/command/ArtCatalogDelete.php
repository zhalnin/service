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

/**
 * Class CatalogDelete
 * Для удаления блока каталога по id
 * @package dmn\command
 */
class ArtCatalogDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idpar = $request->getProperty( 'idpar' );
        $idc = $request->getProperty( 'idc' );
        if( $idc ) { // если передан id каталога

            \dmn\domain\ArtCatalog::delete( $idc );

            $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idc=$_REQUEST[idc]&idpar=$_REQUEST[idpar]&page=$_GET[page]" ); // перегружаем страничку
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>