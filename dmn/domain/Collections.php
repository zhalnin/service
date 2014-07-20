<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:11
 */

namespace dmn\domain;


interface NewsCollection extends \Iterator {
    function add( DomainObject $news );
}

interface ContactsCollection extends \Iterator {
    function add( DomainObject $contacts );
}

interface GuestbookCollection extends \Iterator {
    function add( DomainObject $guestbook );
}

interface UnlockCollection extends \Iterator {
    function add( DomainObject $unlock );
}

interface UnlockDetailsCollection extends \Iterator {
    function add( DomainObject $unlock );
}

interface UdidCollection extends \Iterator {
    function add( DomainObject $udid );
}

interface CarrierCheckCollection extends \Iterator {
    function add( DomainObject $carrierCheck );
}

interface FastCheckCollection extends \Iterator {
    function add( DomainObject $fastCheck );
}

interface BlacklistCheckCollection extends \Iterator {
    function add( DomainObject $blacklistCheck );
}

interface FaqCollection extends \Iterator {
    function add( DomainObject $faq );
}

interface FaqPositionCollection extends \Iterator {
    function add( DomainObject $faq );
}

interface FaqParagraphCollection extends \Iterator {
    function add( DomainObject $faq );
}

interface FaqParagraphImageCollection extends \Iterator {
    function add( DomainObject $faq );
}

interface CartOrderCollection extends \Iterator {
    function add( DomainObject $faq );
}

?>