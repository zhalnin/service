<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/06/14
 * Time: 17:18
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class SearchIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
//        parent::__construct( $field, array( 'id_news','name','body','id_position','id_catalog' ) );
        parent::__construct( $field, array( 'id_news','name','body','id_position','id_catalog' ) );
    }
}
?>