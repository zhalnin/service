<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 19:17
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/IdentityObject.php" );

class NewsIdentityObject extends IdentityObject {
    function __construct( $field=null ) {
        parent::__construct( $field, array('id',
                                        'name',
                                        'preview',
                                        'body',
                                        'putdate',
                                        'hidedate',
                                        'urltext',
                                        'url',
                                        'alt',
                                        'urlpict',
                                        'urlpict_s',
                                        'pos',
                                        'hide',
                                        'hidepict') );
    }
}
?>