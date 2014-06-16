<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09/06/14
 * Time: 22:47
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class UnlockDetailsIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id_catalog',
                'operator',
                'cost',
                'timeconsume',
                'compatible',
                'status',
                'currency',
                'hide',
                'pos',
                'putdate',
                'id_catalog' )
        );
    }
}
?>