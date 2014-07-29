<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:38
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/command/Command.php" );
require_once( "dmn/mapper/PersistenceFactory.php" );
require_once( "dmn/mapper/DomainObjectAssembler.php" );
require_once( "dmn/domain/News.php" );
require_once( "dmn/classes/class.PagerMysql.php" );
require_once( "dmn/base/Registry.php" );


class News extends Command {

    function doExecute( \dmn\controller\Request $request ) {

//        echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
        $action     = $request->getProperty( 'pact' ); // действие над позицией
        $position   = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $idn        = $request->getProperty( 'idn' ); // id новости
        $page       = $request->getProperty( 'page' ); // номер страницы в постраничной навигации
        $page_link = 3; // Количество ссылок в постраничной навигации
        $pnumber = 10; // Количество позиций на странице

        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке новостей
        if( ! empty( $position ) ) {
            \dmn\domain\News::position( $idn, $position );
            $this->reloadPage( 0, "dmn.php?cmd=News&page={$page}" );
        }

        if( ! empty( $action ) ) {
            switch( $action ) {
                case 'add':
                    return self::statuses( 'CMD_ADD');
                    break;
                case 'edit':
                    return self::statuses( 'CMD_EDIT');
                    break;
                case 'del':
                    return self::statuses( 'CMD_DELETE');
                    break;
            }
        }


        // Объявляеи объект постраничной навигации
        $news = new \dmn\classes\PagerMysql('system_news',
                                            "",
                                            "ORDER BY pos",
                                            $pnumber,
                                            $page_link,
                                            "&cmd=News");

        if( is_object( $news ) ) {
            $request->setObject( 'news', $news );
        }


        return self::statuses( 'CMD_OK' );
    }
}