<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 18/06/14
 * Time: 13:48
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class LoginOshibkaIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id',
                                            'ip',
                                            'date',
                                            'col' )
        );
    }
}
?>