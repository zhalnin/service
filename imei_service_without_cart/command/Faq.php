<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 15:14
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/Faq.php" );

class Faq extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

//        $request->addFeedback( 'Welcome to FAQ' );
        $id_position = $request->getProperty('idp');
        if( ! $id_position ) {
            $id = 0;
            $catalogCollection = \imei_service\domain\Faq::find( $id );
//            echo "<tt><pre>".print_r($catalogCollection, true)."</pre></tt>";
            $request->setObject( 'catalogCollection', $catalogCollection );

        } else {
            // Переадресуем для выборки всех позиций - faqPosition
//                    echo "<tt><pre><--- start --->\r\n".print_r( $catalogCollection, true)."\r\n<--- end ---></pre></tt>";
            return self::statuses( 'CMD_OK' );
        }
    }
}
?>