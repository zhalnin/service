<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 18:00
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'ArtParagraph' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
require_once( "dmn/domain/ArtParagraphImg.php" );

class ArtParagraphDetail extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        // получаем id_paragraph нужного изображения
        $idph = $request->getProperty( 'idph' ); // id параграфа
        $idp = $request->getProperty( 'idp' ); // id позиции
        $idc = $request->getProperty( 'idc' ); // id каталога
        if( $idph ) { // если передан id
            $paragraphDetail = \dmn\domain\ArtParagraphImg::find( $idph, $idc, $idp );
            if( is_object( $paragraphDetail ) ) {
                $request->setObject( 'paragraphDetail', $paragraphDetail ); // сохраняем объекты для передачи во вьюшку
            }
        } else {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
    }
}
?>