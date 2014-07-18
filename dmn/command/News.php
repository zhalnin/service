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

require_once( "dmn/base/Registry.php" );


class News extends Command {

    function doExecute( \dmn\controller\Request $request ) {

//        echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
        $action     = $request->getProperty( 'act' );
        $id_news    = $request->getProperty( 'id_news' );

        switch( $action ) {
            case 'hide':
                $obj = \dmn\domain\News::showHide( $id_news, 'show' );
                $obj->setHide('hide');
                $this->reloadPage( 0, "dmn.php?cmd=News" );
                break;
            case 'show':
                $obj = \dmn\domain\News::showHide( $id_news, 'hide' );
                $obj->setHide('show');
                $this->reloadPage( 0, "dmn.php?cmd=News" );
                break;
            case 'up':
                \dmn\domain\News::upDown( $id_news, 'up' );
                $this->reloadPage( 0, "dmn.php?cmd=News" );
                break;
            case 'down':
                \dmn\domain\News::upDown( $id_news, 'down' );
                $this->reloadPage( 0, "dmn.php?cmd=News" );
                break;
            case 'uppest':
                \dmn\domain\News::upDown( $id_news, 'uppest' );
                $this->reloadPage( 0, "dmn.php?cmd=News" );
                break;
        }

        return self::statuses( 'CMD_OK' );
    }
}