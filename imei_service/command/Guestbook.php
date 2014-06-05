<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 21:12
 */

namespace imei_service\command;

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Guestbook.php" );


class Guestbook extends Command {
    function doExecute( \imei_service\controller\Request $request ) {

        $page = $request->getProperty( 'page' );
        if( ! $page ) {
            $page = 1;
        }


        $pagination = \imei_service\domain\Guestbook::paginationMysql( $page );
        $request->addFeedback( "Welcome to Guestbook IMEI-SERVICE");

        $guestbook = $request->setObject('guestbook', $pagination );
        // Здесь получаем коллекцию:
        // делаем запрос в static function domain/Guestbook/PagerMysql()
        // из него в mapper/DomainObjectAssembler/PagerMysql()
        //


//        echo "<tt><pre>".print_r($pagination, true)."</pre></tt>";

//        echo "<tt><pre>".print_r($pagination->printPageNav(), true)."</pre></tt>";

        return self::statuses( 'CMD_OK' );
    }
}

//$tableName = system_guestbook,
//$where = "",
//$order = "",
//$pageNumber = 10,
//$pageLink = 3,
//$parameters = ""
//$page = ""