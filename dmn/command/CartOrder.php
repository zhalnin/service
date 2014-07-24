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
require_once( "dmn/domain/CartItems.php" );

class CartOrder extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        //        echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
        $action     = $request->getProperty( 'pact' ); // действие над позицией
//        $id_news    = $request->getProperty( 'idn' ); // id новости
        $page       = $request->getProperty( 'page' ); // номер страницы в постраничной навигации

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


        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию
    }
}
?>