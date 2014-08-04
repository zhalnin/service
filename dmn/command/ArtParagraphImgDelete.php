<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/08/14
 * Time: 19:39
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );

/**
 * Class ArtParagraphImgDelete
 * Для удаления позиции из каталога по id
 * @package dmn\command
 */
class ArtParagraphImgDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp    = intval( $request->getProperty( 'idp' ) );
        $idc    = intval( $request->getProperty( 'idpc' ) );
        $idph   = intval( $request->getProperty( 'idph' ) );
        $page   = intval( $_GET['page'] );
//        echo "<tt><pre>".print_r( $request, true)."</pre></tt>";
        if( $idp ) { // если передан id_news
            $paragraphImg = \dmn\domain\ArtParagraphImg::find( $idph, $idc, $idp ); // находим элементы по заданному id_news
            // удаление блока изображений параграфа
//            $news->finder()->delete( $news );
//            \dmn\domain\ObjectWatcher::instance()->performOperations();
            $paragraphImg->markDeleted();

            $this->reloadPage( 0, "dmn.php?cmd=ArtParagraph&idph=$idph&idc=$idc&idp=$idp&page=$page" ); // перегружаем страничку
            // возвращаем статус и переадресацию на messageSuccess
            return self::statuses( 'CMD_OK' );

        }
    }
}
?>