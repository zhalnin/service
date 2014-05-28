<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 18:31
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/IdentityObject.php" );

class ContactsIdentityObject extends IdentityObject {

    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id',
                                            'name',
                                            'phone',
                                            'fax',
                                            'email',
                                            'skype',
                                            'vk',
                                            'address',
                                            'photo',
                                            'photo_small',
                                            'alt')
        );
    }
}
?>