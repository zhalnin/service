<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 14:37
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'AZ' ) ) die();
define( 'Accounts', true );

require_once( "dmn/command/Command.php" );
require_once( "dmn/classes/class.PagerMysql.php" );

class Accounts extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $page_link  = 3; // Количество ссылок в постраничной навигации
        $pnumber    = 10; // Количество позиций на страниц
        $action     = $request->getProperty( 'pact' ); // действие над позицией аккаунта


        if( ! empty( $action ) ) {
            switch( $action ) {
                case 'add':
                    return self::statuses( 'CMD_ADD');
                    break;
                case 'del':
                    return self::statuses( 'CMD_DELETE');
                    break;
            }
        }

        // Объявляем объект постраничной навигации
        $accounts = new \dmn\classes\PagerMysql('system_accounts',
                                                "",
                                                "ORDER BY name",
                                                $pnumber,
                                                $page_link);


        if( is_object( $accounts ) ) {
            $request->setObject( 'accounts', $accounts );
        }


        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию

    }
}

?>