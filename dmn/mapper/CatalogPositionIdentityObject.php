<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 20:38
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/IdentityObject.php" );

class CatalogPositionIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array('id_position',
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