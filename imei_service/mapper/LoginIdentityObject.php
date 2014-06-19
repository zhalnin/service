<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/06/14
 * Time: 14:05
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class LoginIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id',
                                            'fio',
                                            'city',
                                            'email',
                                            'login',
                                            'pass',
                                            'activation',
                                            'status' )
        );
    }
}
?>