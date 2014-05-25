<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/01/14
 * Time: 22:00
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

require_once( "woo/domain/Collections.php" );
require_once( "woo/mapper/Collection.php" );
require_once( "woo/domain/Venue.php" );

class VenueCollection extends Collection implements \woo\domain\VenueCollection {
    function targetClass() {
        return 'woo\domain\Venue';
    }
}

class SpaceCollection extends Collection implements \woo\domain\SpaceCollection {
    function targetClass() {
        return 'woo\domain\Space';
    }
}

class EventCollection extends Collection implements \woo\domain\EventCollection {
    function targetClass() {
        return 'woo\domain\Event';
    }
}

class DeferredEventCollection extends EventCollection {
    private $stmt;
    private $valueArray;
    private $run=false;

//    function __construct( Mapper $mapper, \PDOStatement $stmt_handle, array $valueArray ) {
    /**
     * @param DomainObjectFactory $dofact - EventObjectFactory
     * @param \PDOStatement $stmt_handle - selectBySpaceStmt
     * @param array $valueArray - array
     */
    function __construct( DomainObjectFactory $dofact, \PDOStatement $stmt_handle, array $valueArray ) {
        parent::__construct( null, $dofact );
//                echo "<tt><pre> DeferredEventCollection - ".print_r($stmt_handle,true)."</pre></tt>";
        $this->stmt = $stmt_handle;
        $this->valueArray = $valueArray;

    }

    function notifyAccess() {
        if( ! $this->run ) {
            $this->stmt->execute( $this->valueArray );
            $this->raw = $this->stmt->fetchAll();
            $this->total = count( $this->raw );
        }
        $this->run = true;
    }
}

?>