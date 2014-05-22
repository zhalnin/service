<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/05/14
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;

require_once( "imei_service/domain/DomainObject.php" );

class News extends DomainObject {
    private $name;

    /**
     * Конструктор принимаем id - идентификатор для БД и name - имя
     * @param null $id
     * @param null $name
     */
    function __construct( $id=null, $name=null ) {
        $this->name = $name;
        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    static function findAll() {
        return "ooo";
    }
}