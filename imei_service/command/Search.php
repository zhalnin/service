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

        $pagination = \imei_service\domain\Search::paginationSearchMysql( array('system_news',
                                                                                'system_menu_paragraph',
                                                                                'system_position',
                                                                                'system_catalog')
        );
        $request->setObject('search_pagination', $pagination); // сохраняем полученный объект
    }
}

?>