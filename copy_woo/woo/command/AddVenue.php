<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10/01/14
 * Time: 22:34
 * To change this template use File | Settings | File Templates.
 */

namespace woo\command;
//echo "woo\command\AddVenue.php";
require_once( "woo/domain/Venue.php" );

class AddVenue extends Command {
    function doExecute( \woo\controller\Request $request ) {
        // Take "venue_name" from Request(cmd=AddVenue&venue_name=Street)
        $name = $request->getProperty("venue_name");
//        echo "<tt><pre> Name ".print_r($name, true)."</pre></tt>";
        // If name is null
        if( !$name ) {
            // add feedback to Request ($feedback = "no name provided")
            $request->addFeedback( "no name provided" );
            // and return 3 (status 'CMD_INSUFFICIENT_DATA' => 3)
            return self::statuses('CMD_INSUFFICIENT_DATA' );
        // If name is not null
        } else {
            // Create instance of class \woo\domain\Venue
            // with id=null, and name=$name($venue_name=Street)
            $venue_obj = new \woo\domain\Venue( null, $name );
//            echo "<tt><pre>".print_r($venue_obj, true)."</pre></tt>";
            // Add to Request array object['venue'][object $venue_obj] (object['venue'][Venue(id=null,name=Street) and other methods]
            $request->setObject( 'venue', $venue_obj );


            $venue_obj->finder()->insert( $venue_obj );
//            \woo\domain\ObjectWatcher::instance()->performOperations();


            $request->addFeedback("'$name' added to DB ({$venue_obj->getId()})");
            return self::statuses('CMD_OK');
        }
//        return self::statuses( 'CMD_DEFAULT' );
    }
}

?>