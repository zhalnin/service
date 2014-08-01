<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 18:25
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
class CatalogDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp = $request->getProperty( 'idp' );
        $idc = $request->getProperty( 'idc' );
        if( $idc ) { // если передан id каталога

            \dmn\domain\Catalog::delete( $idc ); // рекурсивно удаляем все зависимости каталога

            $this->reloadPage( 0, "dmn.php?cmd=Catalog&idc=$_REQUEST[idc]&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>