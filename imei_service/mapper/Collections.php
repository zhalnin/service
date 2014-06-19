<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:12
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

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

class LoginCollection
    extends Collection
    implements \imei_service\domain\LoginCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Login";
    }
}


class LoginOshibkaCollection
    extends Collection
    implements \imei_service\domain\LoginOshibkaCollection {

    function targetClass() {
        return "\\imei_service\\domain\\LoginOshibka";
    }
}


class UnlockDetailsCollection
    extends Collection
    implements \imei_service\domain\UnlockDetailsCollection {

    function targetClass() {
        return "\\imei_service\\domain\\UnlockDetails";
    }
}

class UnlockCollection
    extends Collection
    implements \imei_service\domain\UnlockCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Unlock";
    }
}

class ContactsCollection
    extends Collection
    implements \imei_service\domain\ContactsCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Contacts";
    }
}

class GuestbookCollection
    extends Collection
    implements \imei_service\domain\GuestbookCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Guestbook";
    }
}


class UdidCollection
    extends Collection
    implements \imei_service\domain\UdidCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Udid";
    }
}


class CarrierCheckCollection
    extends Collection
    implements \imei_service\domain\CarrierCheckCollection {

    function targetClass() {
        return "\\imei_service\\domain\\CarrierCheck";
    }
}



class FastCheckCollection
    extends Collection
    implements \imei_service\domain\FastCheckCollection {

    function targetClass() {
        return "\\imei_service\\domain\\FastCheck";
    }
}



class BlacklistCheckCollection
    extends Collection
    implements \imei_service\domain\BlacklistCheckCollection {

    function targetClass() {
        return "\\imei_service\\domain\\BlacklistCheck";
    }
}



class FaqCollection
    extends Collection
    implements \imei_service\domain\FaqCollection {

    function targetClass() {
        return "\\imei_service\\domain\\Faq";
    }
}


class FaqPositionCollection
    extends Collection
    implements \imei_service\domain\FaqPositionCollection {

    function targetClass() {
        return "\\imei_service\\domain\\FaqPosition";
    }
}


class FaqParagraphCollection
    extends Collection
    implements \imei_service\domain\FaqParagraphCollection {

    function targetClass() {
        return "\\imei_service\\domain\\FaqParagraph";
    }
}


class FaqParagraphImageCollection
    extends Collection
    implements \imei_service\domain\FaqParagraphImageCollection {

    function targetClass() {
        return "\\imei_service\\domain\\FaqParagraphImage";
    }
}


?>