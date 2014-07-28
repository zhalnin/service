<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 18:50
 */
namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/command/Command.php" );
require_once( "dmn/domain/ArtCatalog.php" );

class ArtCatalog extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $action     = $request->getProperty( 'pact' ); // действие над позицией
        $position   = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $page       = $request->getProperty( 'page' ); // номер страницы в постраничной навигации
        $id_catalog = $request->getProperty( 'idc' ); // id каталога
        $id_parent  = $request->getProperty( 'idp' ); // id родительского каталога

//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";

        // если не передан родительский id или он равен 0
        if( ! isset( $id_parent ) || $id_parent == 0  ) {
            // в зависимости от действия вызываем метод с
            // определенными параметрами для выполнения действия над
            // позицией в блоке
            if( ! empty( $position ) ) {
                \dmn\domain\ArtCatalog::position( $id_catalog, $position );
                $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idp={$id_parent}&page={$page}" );
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
                }
            }
            // возвращаем успешный результат и вызываем вьюшку artCatalog
            return self::statuses( 'CMD_OK' );
        } else { // если родительский id больше 0, то возвращаем вьюшку posCatalog
            // в зависимости от действия вызываем метод с
            // определенными параметрами для выполнения действия над
            // позицией в блоке
            if( ! empty( $position ) ) {
                \dmn\domain\ArtCatalog::position( $id_catalog, $position );
                $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idp={$id_parent}&page={$page}" );
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
                    case 'artadd':
                        return self::statuses( 'CMD_ART_ADD');
                        break;
                    case 'urladd':
                        return self::statuses( 'CMD_URL_ADD');
                        break;

                }
            }

            return self::statuses('CMD_POS_OK'); // передаем статус выполнения и далее смотрим переадресацию

        }
    }
}
?>