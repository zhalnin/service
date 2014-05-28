<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 23:32
 */

namespace imei_service\command;

require_once( "imei_service/domain/Contacts.php" );

class Contacts extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $collection = \imei_service\domain\Contacts::findAll();
        $request->setObject( 'contacts', $collection );
        $request->addFeedback( "Welcome to Contacts" );
        return self::statuses( "CMD_OK" );
    }
}

?>