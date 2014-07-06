<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/05/14
 * Time: 18:48
 */

namespace imei_service\mapper;

require_once( "imei_service/mapper/IdentityObject.php" );

class GuestbookIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array( 'id',
                                            'name',
                                            'city',
                                            'email',
                                            'url',
                                            'message',
                                            'answer',
                                            'putdate',
                                            'hide',
                                            'id_parent',
                                            'ip',
                                            'browser' )
        );
    }
}
?>