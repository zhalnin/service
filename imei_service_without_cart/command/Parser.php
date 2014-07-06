<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 18:39
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/view/utils/utils.curl.php" );

class Parser  extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        //устанавливаем переменные...
//    $host		=	explode('/', substr($url, 7));
//    $host		=	substr($url, 0, 7).$host[0].'/';

        $url = $request->getProperty( 'url' );
        $urlParser = $request->getProperty( 'urlParser' );
        $ime_i = $request->getProperty( 'ime_i' );
        $types = $request->getProperty( 'types' );
        $maxPages = $request->getProperty( 'max' );
        $hostPost = $url.$urlParser;
        $hostGet = $url;

        $user_agents = array(
            "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)",
            "Mozilla/5.0 (compatible; Mail.RU/2.0)",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22 (KHTML, like Gecko) Ubuntu Chromium/25.0.1364.160 Chrome/25.0.1364.160 Safari/537.22",
            "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
            "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)",
            "Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5",
            "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_2 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B146 Safari/8536.25"
        );
        $id         = rand(0,count($user_agents)-1);
        $user_agent = $user_agents[$id];
        //получаем html-код исходной страницы
        $page	=	\imei_service\view\utils\curlGet($hostGet,$user_agent);
        $request->setObject( 'pageCurl', $page );

        return self::statuses( 'CMD_OK' );
    }
}
?>