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

class News extends Command {

    function doExecute( \dmn\controller\Request $request ) {

//        echo "<tt><pre>".print_r( $request, true )."</pre></tt>";
        $action = $request->getProperty( 'act' );
        $id_news = $request->getProperty( 'id_news' );
        $factory = \dmn\mapper\PersistenceFactory::getFactory( 'dmn\domain\News' );
        $finder = new \dmn\mapper\DomainObjectAssembler( $factory );
        $idobj = $factory->getIdentityObject()->field('id_news')->eq($id_news);
        $obj = new \dmn\domain\NewsDomain('id_news');


//        echo "<tt><pre>".print_r( $collection, true )."</pre></tt>";


        switch( $action ) {
            case 'hide':
                print 'hide';
                $obj->setHide('hide');
                break;
            case 'show':
                print 'show';
                break;
            case 'up':
                print 'up';
                break;
            case 'down':
                print 'down';
                break;
            case 'uppest':
                print 'uppest';
                break;
        }
//        return self::statuses( 'CMD_OK' );
    }
}