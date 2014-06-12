<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 12:19
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class FaqPosition extends DomainObject {

    private $id;
    private $name;
    private $url;
    private $keywords;
    private $modrewrite;
    private $pos;
    private $hide;
    private $idCatalog;


    function __construct(   $id             =null,
                            $name           =null,
                            $url            =null,
                            $keywords       =null,
                            $modrewrite     =null,
                            $pos            =null,
                            $hide           ='show',
                            $idCatalog      =0 ) {

        $this->name = $name;
        $this->url = $url;
        $this->keywords = $keywords;
        $this->modrewrite = $modrewrite;
        $this->pos = $pos;
        $this->hide = $hide;
        $this->idCatalog = $idCatalog;

        parent::__construct( $id );
    }



    static function findAll() {
        $finder = self::getFinder( __CLASS__ );
        $idobj = self::getIdentityObject( __CLASS__ );
        $faqIdobj = new \imei_service\mapper\FaqPositionIdentityObject( 'hide' );
        $faqIdobj->eq( 'show' );
        return $finder->find( $faqIdobj );
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\FaqPositionIdentityObject( 'id_position' );
        return $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'show' ) );
    }


    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    function setUrl( $url_s ) {
        $this->url = $url_s;
        $this->markDirty();
    }
    function setKeywords( $keywords_s ) {
        $this->keywords = $keywords_s;
        $this->markDirty();
    }
    function setModrewrite( $modrewrite_s ) {
        $this->modrewrite = $modrewrite_s;
        $this->markDirty();
    }
    function setPos( $pos_s ) {
        $this->pos = $pos_s;
        $this->markDirty();
    }
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
        $this->markDirty();
    }
    function setIdCatalog( $idCatalog_s ) {
        $this->idCatalog = $idCatalog_s;
        $this->markDirty();
    }


    function getName() {
        return $this->name;
    }
    function getUrl() {
        return $this->url;
    }
    function getKeywords() {
        return $this->keywords;
    }
    function getModrewrite() {
        return $this->modrewrite;
    }
    function getPos() {
        return $this->pos;
    }
    function getHide() {
        return $this->hide;
    }
    function getIdCatalog() {
        return $this->idCatalog;
    }
}
?>