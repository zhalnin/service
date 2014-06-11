<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 10/06/14
 * Time: 17:37
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class UdidIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id',
                'name',
                'order_title',
                'description',
                'keywords',
                'abbreviatura',
                'modrewrite',
                'pos',
                'hide',
                'urlpict',
                'alt',
                'rounded_flag',
                'title_flag',
                'alt_flag',
                'id_parent' )
        );
    }
}
?>