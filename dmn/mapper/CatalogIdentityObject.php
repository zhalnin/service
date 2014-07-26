<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 25/07/14
 * Time: 21:51
 */
namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/IdentityObject.php" );

class CatalogIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array('id_catalog',
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