<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 22:14
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'Users' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );

class UsersDetail extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        // получаем id_news нужного изображения
        $idp = intval( $request->getProperty( 'idp' ) ); // id
        if( $idp ) { // если передан id
            $usersDetail = \dmn\domain\Users::find( $idp );
//        echo "<tt><pre>".print_r($usersDetail, true)."</pre></tt>";
            if( is_object( $usersDetail ) ) {
                $request->setObject( 'usersDetail', $usersDetail ); // сохраняем объекты для передачи во вьюшку
            }
        } else {
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
    }
}
?>