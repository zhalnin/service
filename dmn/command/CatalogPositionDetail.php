<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 21:05
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CatalogPosition' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );

class CatalogPositionDetail extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        // получаем id_news редактируемой новости
        $idp = $request->getProperty( 'idp' );
        if( $idp ) { // если передан id
            $catalogPosition = \dmn\domain\CatalogPosition::findDetail( $idp ); // находим элементы по заданному id
            $request->setObject( 'catalogPositionDetail', $catalogPosition ); // сохраняем объекты для передачи во вьюшку
        } else {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
    }
}
?>