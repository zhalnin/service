<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 18:51
 */
namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'AZ' ) ) die();
define( 'CatalogPosition', true );
require_once( 'dmn/view/utils/security_mod.php' );

require_once( "dmn/command/Command.php" );
require_once( "dmn/domain/CatalogPosition.php" );
require_once( "dmn/classes/class.PagerMysql.php" );


class CatalogPosition extends Command {

    function doExecute( \dmn\controller\Request $request ) {

//                echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
        $action         = $request->getProperty( 'pact' ); // действие над позицией
        $position       = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $page           = $request->getProperty( 'page' ); // номер страницы в постраничной навигации
        $idc            = $request->getProperty( 'idc' ); // id каталога
        $idp            = intval( $request->getProperty( 'idp' ) ); // id родительского каталога
        $page_link      = 3; // Количество ссылок в постраничной навигации
        $pnumber        = 10; // Количество позиций на странице
        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке новостей
        if( ! empty( $position ) ) {
            \dmn\domain\CatalogPosition::position( $idp, $position );
            $this->reloadPage( 0, "dmn.php?cmd=CatalogPosition&idp={$idp}&idc={$idc}&page={$page}" );
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
        $catalogPosition = new \dmn\classes\PagerMysql('system_position',
                                                        "WHERE id_catalog=$idc",
                                                        "ORDER BY pos",
                                                        $pnumber,
                                                        $page_link,
                                                        "&cmd=Catalog&idc=$idc");

        if( is_object( $catalogPosition ) ) {
            $request->setObject( 'catalogPosition', $catalogPosition );
        }

//        echo "<tt><pre>".print_r($catalogPosition->get_page(), true)."</pre></tt>";
        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию
    }
}
?>