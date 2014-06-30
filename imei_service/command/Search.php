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
// подключаем класс с методами
require_once( "imei_service/domain/Search.php" );
// подключаем класс с постраничной навигацией
require_once( "imei_service/mapper/PaginationFactory.php" );
//require_once( "imei_service/domain/News.php" );
//require_once( "imei_service/mapper/IdentityObject.php" );


/**
 * Class Search
 * Осуществляет поиск по сайту
 * по таблицам:
 * system_news,
 * system_catalog,
 * system_position,
 * system_menu_catalog,
 * system_menu_position,
 * system_menu_paragraph
 * @package imei_service\command
 */
class Search extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
        $query = $request->getProperty( 'q' ); // строка поиска в строке запроса
        if( empty( $query ) ) { // если параметра нет
            return self::statuses( 'CMD_ERROR' ); // переадресуем на страницу с ошибкой
        }
        // выполняем поиск и создаем объект с getPage() и printNavPage()
        $pagination = \imei_service\domain\Search::paginationSearchMysql( array('system_news',
                                                                                'system_menu_paragraph',
                                                                                'system_position',
                                                                                'system_catalog')
        );
        $getPage = $pagination->getPage(); // временная
        if( empty( $getPage ) ) { // если результат поиска пустой
            return self::statuses( 'CMD_ERROR' ); // переадресуем на страницу с ошибкой
        }
        if( is_object( $pagination ) ) { // если результат поиска не нулевой
            $request->setObject('search_pagination', $pagination); // сохраняем полученный объект
            return self::statuses( 'CMD_OK' ); // и переадресуем на страницу с результатом поиска
        }
    }
}

?>