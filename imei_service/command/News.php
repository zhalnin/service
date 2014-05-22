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

        $id_news = $request->getProperty( 'id_news' ); // если передан параметр для просмотра новости детально
        if( ! $id_news ) {

            $collection = \imei_service\domain\News::findAll();
            echo "<tt><pre>".print_r($collection, true)."</pre></tt>";


            $request->addFeedback( "Welcome to NEWS IMEI-SERVICE" );
            return self::statuses('CMD_INSUFFICIENT_DATA'); // передаем статус, чтобы не запускать переадресацию
        } else {

            $news_obj = new \imei_service\domain\News( $id_news, null );
            return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию

        }
    }
}
?>