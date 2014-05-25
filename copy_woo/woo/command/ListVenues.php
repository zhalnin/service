<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/01/14
 * Time: 17:52
 * To change this template use File | Settings | File Templates.
 */

namespace woo\command;

require_once( "woo/domain/Venue.php" );

class ListVenues extends Command {
    function doExecute( \woo\controller\Request $request ) {
        $collection = \woo\domain\Venue::findAll();
//        echo "<tt><pre> ListVenues - ".print_r($collection, true)."</pre></tt>";
        $request->setObject( 'venues', $collection );


        $factory = \woo\mapper\PersistenceFactory::getFactory( 'woo\domain\Venue' );
        $finder = new \woo\mapper\DomainObjectAssembler( $factory );
        $idobj = $factory->getIdentityObject()->field('name')->eq('The EyeBall Inn');
        $collection = $finder->find( $idobj );
//        foreach ( $collection as $venue ) {
//            print_r( $venue );
//        }


//        // Удалить после debugging
        $request->addFeedback("Here are all list of venues");
        return self::statuses( 'CMD_OK' );
    }
}
?>