<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/06/14
 * Time: 11:43
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем командный класс
require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/Search.php" );
require_once( "imei_service/domain/News.php" );

require_once( "imei_service/mapper/PaginationFactory.php" );
require_once( "imei_service/mapper/IdentityObject.php" );




class Search extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
//        echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
//
//        $q = $request->getProperty( 'q' );
//
//        $qSearch = preg_split("|[\s]+|", $q  );
//
//        foreach( $qSearch as $qLine ) {
//            // system_news
//            $search_news[] = "( ( LCASE( system_news.name ) RLIKE '".$qLine."') OR ( LCASE( system_news.body ) RLIKE '".$qLine."' ) )";
//            // system_menu_paragraph
//            $search_faq[] = "( LCASE( system_menu_paragraph.name ) RLIKE '".$qLine."')";
//            // system_position
//            $search_service[] = "( LCASE( system_position.operator ) RLIKE '".$qLine."')";
//            // system_catalog
//            $search_catalog_service[] = "( LCASE( system_catalog.name ) RLIKE '".$qLine."')";
//        }
//
//        $pagination = \imei_service\domain\Search::paginationSearchMysql( array('system_news',
//                                                                                'system_menu_paragraph',
//                                                                                'system_position',
//                                                                                'system_catalog'),
//                                                                        array( $search_news,
//                                                                                $search_faq,
//                                                                                $search_service,
//                                                                                $search_catalog_service)
//        );
        $pagination = \imei_service\domain\Search::paginationSearchMysql( array('system_news',
                                                                                'system_menu_paragraph',
                                                                                'system_position',
                                                                                'system_catalog')
        );
        $request->setObject('search_pagination', $pagination); // сохраняем полученный объект
    }
}

?>