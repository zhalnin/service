<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/FaqParagraph.php" );

class FaqParagraph extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
//        $request->addFeedback( "Welcome to faqParagraph" );
        $collection = \imei_service\domain\FaqParagraph::find( $request->getProperty('idp'), $request->getProperty('idc'));
        $request->setObject( 'faqParagraphCollection', $collection );
    }
}
?>