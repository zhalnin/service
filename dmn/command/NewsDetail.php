<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 17:41
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'News' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );

class NewsDetail extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        // получаем id_news нужного изображения
        $idn = $request->getProperty( 'idn' ); // id новости
        if( $idn ) { // если передан id
            $newsDetail = \dmn\domain\News::find( $idn );
            if( is_object( $newsDetail ) ) {
//        echo "<tt><pre>".print_r($newsDetail, true)."</pre></tt>";
                $request->setObject( 'newsDetail', $newsDetail ); // сохраняем объекты для передачи во вьюшку
            }
        } else {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
    }
}
?>