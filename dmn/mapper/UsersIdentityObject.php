<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 15:53
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/IdentityObject.php" );

class UsersIdentityObject extends IdentityObject {

    /**
     * @param null $field - имя поля для создания условного оператора
     * В родительский класс передаем само поле и массив ($enforce)
     */
    function __construct( $field=null ) {
        parent::__construct( $field, array('id',
                'fio',
                'city',
                'email',
                'url',
                'login',
                'activation',
                'status',
                'pass',
                'putdate',
                'lastvisit',
                'block',
                'online',
                'rights' )
        );
    }
}
?>