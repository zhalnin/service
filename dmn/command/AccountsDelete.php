<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 16:12
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'Accounts' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
require_once( 'dmn/domain/Accounts.php' );

/**
 * Class AccountsDelete
 * Для удаления аккаунта из каталога по id
 * @package dmn\command
 */
class AccountsDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $ida = $request->getProperty( 'ida' );
        if( $ida ) { // если передан id_news

            $accounts = \dmn\domain\Accounts::find( $ida ); // находим элементы по заданному id_account
//        echo "<tt><pre>".print_r($accountCount, true)."</pre></tt>";
            $accountCount = \dmn\domain\Accounts::findCountPos(); // находим общее количество учеток
            if( is_array( $accountCount ) ) {
               if( $accountCount['count'] > 1 ) { // если больше одного
                    // удаление аккаунта
        //            $news->finder()->delete( $news );
        //            \dmn\domain\ObjectWatcher::instance()->performOperations();
                    $accounts->markDeleted(); // отмечаем для удаления
                    $this->reloadPage( 0, "dmn.php?cmd=Accounts&page=$_GET[page]" ); // перегружаем страничку
                    // возвращаем статус и переадресацию на messageSuccess
                    return self::statuses( 'CMD_OK' );
               } else {
                   $request->addFeedback( 'Нельзя удалить единственный аккаунт' );
                   return self::statuses( 'CMD_ERROR' ); // возвращаем статус обработки с ошибкой
               }
            }


        }
    }
}
?>