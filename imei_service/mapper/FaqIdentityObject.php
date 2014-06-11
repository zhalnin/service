<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 22:54
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class FaqIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id_catalog',
                'name',
                'description',
                'keywords',
                'modrewrite',
                'pos',
                'hide',
                'id_parent' )
        );
    }
}
?>