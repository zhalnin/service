<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 11/05/14
 * Time: 19:43
 */

namespace imei_service\command;

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/base/Registry.php" );
require_once( "imei_service/domain/News.php" );

class News extends Command {
    function doExecute( \imei_service\controller\Request $request ) {

        $id = $request->getProperty( 'id' ); // если передан параметр для просмотра новости детально
        if( ! $id ) {

            $collection = \imei_service\domain\News::findAll(); // работает \imei_service\domain\News - получаем коллекцию

            $request->setObject( 'news', $collection ); // кешируем коллекцию в \imei_service\controller\Request
//            echo "<tt><pre>".print_r($collection, true)."</pre></tt>";

            $request->addFeedback( "Welcome to NEWS IMEI-SERVICE" );
            return self::statuses('CMD_INSUFFICIENT_DATA'); // передаем статус, чтобы не запускать переадресацию
        } else {

            $collection = \imei_service\domain\News::find( $id );
            $request->setObject( 'newsPrint', $collection );
//            $news_obj = new \imei_service\domain\News( $id, null );
            return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию

        }
    }
}
?>