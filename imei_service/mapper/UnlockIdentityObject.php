<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08/06/14
 * Time: 21:04
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class UnlockIdentityObject extends IdentityObject {

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