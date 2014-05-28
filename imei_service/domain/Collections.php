<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 21:14
 */

namespace imei_service\domain;

interface NewsCollection extends \Iterator {
    function add( DomainObject $news );
}

interface ContactsCollection extends \Iterator {
    function add( DomainObject $contacts );
}

?>