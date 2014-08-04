<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 22:05
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'Users' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
require_once( 'dmn/domain/Accounts.php' );

/**
 * Class UsersDelete
 * Для удаления аккаунта из каталога по id
 * @package dmn\command
 */
class UsersDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $idp = $request->getProperty( 'idp' );
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        if( $idp ) { // если передан id_news

            $accounts = \dmn\domain\Users::find( $idp ); // находим элементы по заданному id_account
//            echo "<tt><pre>".print_r($accounts, true)."</pre></tt>";

            if( is_object( $accounts ) ) {
                // удаление аккаунта
                //            $news->finder()->delete( $news );
                //            \dmn\domain\ObjectWatcher::instance()->performOperations();
                $accounts->markDeleted(); // отмечаем для удаления
            }
        }
        $this->reloadPage( 0, "dmn.php?cmd=Users&page=$_GET[page]" ); // перегружаем страничку
        // возвращаем статус и переадресацию на messageSuccess
        return self::statuses( 'CMD_OK' );
    }
}
?>