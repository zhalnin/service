<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 17:53
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/IdentityObject.php" );

class ArtUrlIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array('id_position',
                'name',
                'url',
                'keywords',
                'modrewrite',
                'pos',
                'hide',
                'id_catalog' )
        );
    }
}
?>