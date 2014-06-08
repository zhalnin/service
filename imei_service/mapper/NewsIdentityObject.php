<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 19:17
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/mapper/IdentityObject.php" );

class NewsIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
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
                                        'hidepict')
        );
    }
}
?>