<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23/03/14
 * Time: 16:02
 * To change this template use File | Settings | File Templates.
 */
namespace woo\mapper;

require_once( "woo/mapper/IdentityObject.php" );

class SpaceIdentityObject extends IdentityObject {
    function __construct( $field=null ) {
        parent::__construct( $field, array( 'name', 'id', 'venue' ) );
    }
}
?>