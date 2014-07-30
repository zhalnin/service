<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 16:34
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/IdentityObject.php" );

class ArtParagraphImgIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array('id_image',
                'name',
                'alt',
                'small',
                'big',
                'hide',
                'pos',
                'id_position',
                'id_catalog',
                'id_paragraph' )
        );
    }
}
?>