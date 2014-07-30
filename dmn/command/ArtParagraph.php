<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 23:09
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/command/Command.php" );
require_once( "dmn/classes/class.PagerMysql.php" );
require_once( "dmn/domain/ArtArt.php" );
require_once( "dmn/domain/ArtCatalog.php" );
require_once( "dmn/domain/ArtParagraphImg.php" );


class ArtParagraph  extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $action    = $request->getProperty( 'pact' ); // действие над позицией
        $position  = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $page      = $request->getProperty( 'page' ); // номер страницы в постраничной навигации
        $idp       = intval( $request->getProperty( 'idp' ) );
        $idc       = intval( $request->getProperty( 'idc' ) );
        $idpar     = intval( $request->getProperty( 'idpar') ); // id родительского каталога ( если его нет, то FALSE === 0 )
        $page_link = 3; // Количество ссылок в постраничной навигации
        $pnumber   = 10; // Количество элементов на странице

//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке
        if( ! empty( $position ) ) {
            \dmn\domain\ArtArt::position( $idp, $position );
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
            }
        }

        // получаем информацию о текущем каталоге
        $catalog = \dmn\domain\ArtCatalog::find( $idc );
        if( is_object( $catalog ) ) {
            $request->setObject( 'catalog', $catalog );
        }

        // получаем информацию по текущей позиции
        $position = \dmn\domain\ArtArt::find( $idp );
        if( is_object( $position ) ) {
            $request->setObject( 'position', $position );
        }

        // Объявляем объект постраничной навигации
        $paragraph = new \dmn\classes\PagerMysql( 'system_menu_paragraph',
            "WHERE id_position = $idp AND
                                id_catalog = $idc",
                                "ORDER BY pos",
                                $pnumber,
                                $page_link,
                                "&idp=$idp&".
                                "idc=$idc");

        if( is_object( $paragraph ) ) {
            $request->setObject( 'paragraph', $paragraph );
        }
//        echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($position, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($paragraph->get_page(), true)."</pre></tt>";
        // возвращаем успешный результат и вызываем вьюшку
        return self::statuses( 'CMD_OK' );
    }
}
?>