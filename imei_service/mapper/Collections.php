<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:12
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/Collection.php" );

class NewsCollection
    extends Collection
    implements \imei_service\domain\NewsCollection {

    function targetClass() {
        return "\\imei_service\\domain\\News";
    }
}

class DefferredNewsCollection extends NewsCollection {
    private $stmt;
    private $valueArray;
    private $run=false;

    function __construct( DomainObjectFactory $dofact, \PDOStatement $stmt_handle, array $valueArray ) {
        parent::__construct( null, $dofact );
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


class ContactsCollection
    extends Collection
    implements \imei_service\domain\ContactsCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Contacts";
    }
}


?>