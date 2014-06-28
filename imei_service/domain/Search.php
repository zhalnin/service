<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/06/14
 * Time: 17:03
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class Search extends DomainObject {

    private $idPosition;
    private $idCatalog;
    private $name;
    private $link;

    /**
     * Поля из БД - сохраняем из в переменные
     */
    function __construct(   $id=null,
                            $idPosition=null,
                            $idCatalog=null,
                            $name=null,
                            $link=null) {


        $this->idPosition  = $idPosition;
        $this->idCatalog   = $idCatalog;
        $this->name         = $name;
        $this->link         = $link;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }


    static function paginationSearchMysql( $tableName ) {

        return new \imei_service\mapper\SearchPaginationFactory( $tableName ,
            "",
            "",
            10,
            3,
            "" );
    }

    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    function setIdPosition( $idPosition_s ) {
        $this->idPosition = $idPosition_s;
        $this->markDirty();
    }
    function setIdCatalog( $idCatalog_s ) {
        $this->idCatalog = $idCatalog_s;
        $this->markDirty();
    }
    function setLink( $link_s ) {
        $this->link = $link_s;
        $this->markDirty();
    }



    function getName() {
        return $this->name;
    }
    function getIdPosition() {
        return $this->idPosition;
    }
    function getIdCatalog() {
        return $this->idCatalog;
    }
    function getLink() {
        return $this->link;
    }
}
?>