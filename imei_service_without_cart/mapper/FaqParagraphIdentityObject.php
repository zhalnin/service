<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 17:45
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class FaqParagraphIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id_paragraph',
                                            'name',
                                            'type',
                                            'align',
                                            'hide',
                                            'pos',
                                            'id_position',
                                            'id_catalog' )
        );
    }
}
?>