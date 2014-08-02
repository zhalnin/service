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
    function add( DomainObject $faq_position );
}

interface FaqParagraphCollection extends \Iterator {
    function add( DomainObject $faq_paragraph );
}

interface FaqParagraphImageCollection extends \Iterator {
    function add( DomainObject $faq_paragraph_image );
}

interface CartOrderCollection extends \Iterator {
    function add( DomainObject $cart_order );
}

interface CartItemsCollection extends \Iterator {
    function add( DomainObject $cart_items );
}

interface CatalogCollection extends \Iterator {
    function add( DomainObject $catalog );
}

interface CatalogPositionCollection extends \Iterator {
    function add( DomainObject $catalogPosition );
}

interface ArtCatalogCollection extends \Iterator {
    function add( DomainObject $artCatalog );
}

interface ArtUrlCollection extends \Iterator {
    function add( DomainObject $artUrl );
}

interface ArtArtCollection extends \Iterator {
    function add( DomainObject $artUrl );
}

interface ArtParagraphCollection extends \Iterator {
    function add( DomainObject $artParagraph );
}

interface ArtParagraphImgCollection extends \Iterator {
    function add( DomainObject $artParagraphImg );
}

interface AccountsCollection extends \Iterator {
    function add( DomainObject $accounts );
}

interface UsersCollection extends \Iterator {
    function add( DomainObject $users );
}
?>