<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 18:51
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/command/Command.php" );
require_once( "dmn/domain/CatalogPosition.php" );

class CatalogPosition extends Command {

    function doExecute( \dmn\controller\Request $request ) {

//                echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
        $action     = $request->getProperty( 'pact' ); // действие над позицией
        $position   = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $page       = $request->getProperty( 'page' ); // номер страницы в постраничной навигации
        $id_catalog = $request->getProperty( 'idc' ); // id каталога
        $id_position = $request->getProperty( 'idp' ); // id родительского каталога
        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке новостей
        if( ! empty( $position ) ) {
            \dmn\domain\CatalogPosition::position( $id_position, $position );
            $this->reloadPage( 0, "dmn.php?cmd=CatalogPosition&idp={$id_position}&idc={$id_catalog}&page={$page}" );
        }

        if( ! empty( $action ) ) {
            switch( $action ) {
                case 'add':
                    return self::statuses( 'CMD_ADD');
                    break;
                case 'edit':
                    return self::statuses( 'CMD_EDIT');
                    break;
                case 'del':
                    return self::statuses( 'CMD_DELETE');
                    break;
                case 'detail':
                    return self::statuses( 'CMD_DETAIL');
                    break;
            }
        }


        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию
    }
}
?>