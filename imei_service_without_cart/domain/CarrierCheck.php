<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 12:05
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class CarrierCheck extends DomainObject {

    private $name;
    private $orderTitle;
    private $description;
    private $keywords;
    private $abbreviatura;
    private $modrewrite;
    private $pos;
    private $hide;
    private $urlPict;
    private $alt;
    private $roundedFlag;
    private $titleFlag;
    private $altFlag;
    private $idParent;


    function __construct(   $id             =null,
                            $name           =null,
                            $orderTitle     =null,
                            $description    =null,
                            $keywords       =null,
                            $abbreviatura   =null,
                            $modrewrite     =null,
                            $pos            =null,
                            $hide           ='show',
                            $urlPict        =null,
                            $alt            =null,
                            $roundedFlag    =null,
                            $titleFlag      =null,
                            $altFlag        =null,
                            $idParent       =0 ) {

        $this->name = $name;
        $this->orderTitle = $orderTitle;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->abbreviatura = $abbreviatura;
        $this->modrewrite = $modrewrite;
        $this->pos = $pos;
        $this->hide = $hide;
        $this->urlPict = $urlPict;
        $this->alt = $alt;
        $this->roundedFlag = $roundedFlag;
        $this->titleFlag = $titleFlag;
        $this->altFlag = $altFlag;
        $this->idParent = $idParent;

        parent::__construct( $id );
    }

    static function findAll() {
        $finder = self::getFinder( __CLASS__ );
        $idobj = self::getIdentityObject( __CLASS__ );
        $unlockIdobj = new \imei_service\mapper\UdidIdentityObject( 'hide' );
        $unlockIdobj->eq( 'show' )->field( 'id_parent' )->gt( 0 );
        return $finder->find( $unlockIdobj );
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\UnlockIdentityObject( 'id_parent' );
        return $finder->findOne( $idobj->eq( $id )->field( 'modrewrite' )->eq( 'CarrierCheck' )->field( 'hide' )->eq( 'show' ) );
    }


    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    function setOrderTitle( $orderTitle_s ) {
        $this->orderTitle = $orderTitle_s;
        $this->markDirty();
    }
    function setDescription( $description_s ) {
        $this->description = $description_s;
        $this->markDirty();
    }
    function setKeywords( $keywords_s ) {
        $this->keywords = $keywords_s;
        $this->markDirty();
    }
    function setAbbreviatura( $abbreviatura_s ) {
        $this->abbreviatura = $abbreviatura_s;
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
    function setUrlPict( $urlPict_s ) {
        $this->urlPict = $urlPict_s;
        $this->markDirty();
    }
    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }
    function setRoundedFlag( $roundedFlag_s ) {
        $this->roundedFlag = $roundedFlag_s;
        $this->markDirty();
    }
    function setTitleFlag( $titleFlag_s ) {
        $this->titleFlag = $titleFlag_s;
        $this->markDirty();
    }
    function setAltFlag( $altFlag_s ) {
        $this->altFlag = $altFlag_s;
        $this->markDirty();
    }
    function setIdParent( $idParent_s ) {
        $this->idParent = $idParent_s;
        $this->markDirty();
    }


    function getName() {
        return $this->name;
    }
    function getOrderTitle() {
        return $this->orderTitle;
    }
    function getDescription() {
        return $this->description;
    }
    function getKeywords() {
        return $this->keywords;
    }
    function getAbbreviatura() {
        return $this->abbreviatura;
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
    function getUrlPict() {
        return $this->urlPict;
    }
    function getAlt() {
        return $this->alt;
    }
    function getRoundedFlag() {
        return $this->roundedFlag;
    }
    function getTitleFlag() {
        return $this->titleFlag;
    }
    function getAltFlag() {
        return $this->altFlag;
    }
    function getIdParent() {
        return $this->idParent;
    }

}


?>