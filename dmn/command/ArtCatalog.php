<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 18:50
 */
namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'AZ' ) ) die();
define( 'ArtCatalog', true );
require_once( 'dmn/view/utils/security_mod.php' );
require_once( "dmn/command/Command.php" );
require_once( "dmn/domain/ArtCatalog.php" );
require_once( "dmn/classes/class.PagerMysql.php" );


class ArtCatalog extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $action     = $request->getProperty( 'pact' ); // действие над позицией
        $position   = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $page       = intval( $request->getProperty( 'page' ) ); // номер страницы в постраничной навигации
        $idc        = intval(  $request->getProperty( 'idc' ) ); // id каталога
        $idpar      = intval( $request->getProperty( 'idpar' ) ); // id родительского каталога ( если его нет, то FALSE === 0 )
        $page_link  = 3; // Количество ссылок в постраничной навигации
        $pnumber    = 10; // Количество позиций на странице

//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";

        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке
        if( ! empty( $position ) ) {
            \dmn\domain\ArtCatalog::position( $idc, $position );
            $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idpar={$idpar}&page={$page}" );
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
//                case 'artadd':
//                    return self::statuses( 'CMD_ART_ADD');
//                    break;
//                case 'urladd':
//                    return self::statuses( 'CMD_URL_ADD');
//                    break;

            }
        }

        $artCatalog = new \dmn\classes\PagerMysql('system_menu_catalog',
            "WHERE id_parent=$idpar",
            "ORDER BY pos",
            $pnumber,
            $page_link,
            "&cmd=ArtCatalog&idpar=$idpar");

        // если передан родительский id или он не равен 0
        if( isset( $idpar ) || $idpar != 0 ) {

            $artPosition = new \dmn\classes\PagerMysql( 'system_menu_position',
                                                    " WHERE id_catalog={$idpar}",
                                                    " ORDER BY pos",
                                                    $pnumber,
                                                    $page_link,
                                                    "&cmd=ArtCatalog&idpar={$idpar}" );

        }

        if( is_object( $artCatalog ) ) {
            // Объявляем объект постраничной навигации
            $request->setObject( 'artCatalog', $artCatalog );
//            echo "<tt><pre>".print_r($artCatalog->get_page(), true)."</pre></tt>";
        }

        if( is_object( $artPosition ) ) {
            // Объявляем объект постраничной навигации
            $request->setObject( 'artPosition', $artPosition );
//            echo "<tt><pre>".print_r($artPosition->get_page(), true)."</pre></tt>";
        }
        // возвращаем успешный результат и вызываем вьюшку artCatalog
        return self::statuses( 'CMD_OK' );
    }
}
?>