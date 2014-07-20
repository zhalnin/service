<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 13:29
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/command/Command.php" );
require_once( "dmn/base/Registry.php" );
require_once( "dmn/domain/CartOrder.php" );

class CartOrder extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию
    }
}
?>