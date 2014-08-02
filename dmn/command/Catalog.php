<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 21:09
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'AZ' ) ) die();
define( 'Catalog', true );

require_once( "dmn/command/Command.php" );
require_once( "dmn/domain/Catalog.php" );
require_once( "dmn/classes/class.PagerMysql.php" );


class Catalog extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $action     = $request->getProperty( 'pact' ); // действие над позицией
        $position   = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $page       = $request->getProperty( 'page' ); // номер страницы в постраничной навигации
        $id_catalog = $request->getProperty( 'idc' ); // id каталога
        $idp        = intval( $request->getProperty( 'idp' ) ); // id родительского каталога
        $page_link  = 3; // Количество ссылок в постраничной навигации
        $pnumber    = 10; // Количество позиций на странице


        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке новостей
        if( ! empty( $position ) ) {
            \dmn\domain\Catalog::position( $id_catalog, $position );
            $this->reloadPage( 0, "dmn.php?cmd=Catalog&idp={$idp}&page={$page}" );
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

        // Объявляеи объект постраничной навигации
        $catalog = new \dmn\classes\PagerMysql('system_catalog',
                                            " WHERE id_parent=$idp",
                                            " ORDER BY pos",
                                            $pnumber,
                                            $page_link,
                                            "&cmd=Catalog&idp=$idp");
        if( is_object( $catalog ) ) {
            $request->setObject( 'catalog', $catalog );
        }


        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию
    }
}
?>