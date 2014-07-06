<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 22:14
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class FaqParagraphImage extends DomainObject {

    private $id;
    private $name;
    private $alt;
    private $small;
    private $big;
    private $hide;
    private $pos;
    private $idParagraph;
    private $idPosition;
    private $idCatalog;

    private $faqPositions;


    function __construct(   $id             =null,
                            $name           =null,
                            $alt            =null,
                            $small          =null,
                            $big            =null,
                            $idParagraph    =null,
                            $hide           ='show',
                            $pos            =null,
                            $idPosition     =null,
                            $idCatalog      =0 ) {

        $this->name = $name;
        $this->alt = $alt;
        $this->small = $small;
        $this->big = $big;
        $this->hide = $hide;
        $this->pos = $pos;
        $this->idPosition = $idPosition;
        $this->idCatalog = $idCatalog;
        $this->idParagraph = $idParagraph;

        parent::__construct( $id );
    }

//
//
//
//
//    function setFaqPosition( FaqPositionCollection $faqPosition ) {
////        echo "<tt><pre> setVenue - ".print_r($venue,true)."</pre></tt>";
//        $this->faqPositions = $faqPosition;
//        $this->markDirty();
//    }
//
//    function getFaqPosition() {
//        if( is_null( $this->faqPositions ) ) {
//            return "getFaqPosition() returned null";
//        }
//        return $this->faqPositions;
//    }
//
//
//
//




//    static function findAll() {
//        $finder = self::getFinder( __CLASS__ );
//        $idobj = self::getIdentityObject( __CLASS__ );
//        $FaqParagraphIdobj = new \imei_service\mapper\FaqParagraphIdentityObject( 'hide' );
//        $FaqParagraphIdobj->eq( 'show' );
//        return $finder->find( $FaqParagraphIdobj );
//    }

//    static function find( $idPosition, $idCatalog ) {
//        $finder = self::getFinder( __CLASS__ );
//        $idobj = new \imei_service\mapper\FaqParagraphIdentityObject( 'id_position' );
//        return $finder->find( $idobj->eq( $idPosition )->field( 'id_catalog' )->eq( $idCatalog )->field( 'hide' )->eq( 'show' ) );
//    }


    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }
    function setSmall( $small_s ) {
        $this->small = $small_s;
        $this->markDirty();
    }
    function setBig( $big_s ) {
        $this->big = $big_s;
        $this->markDirty();
    }
    function setIdParagraph( $idParagraph_s ) {
        $this->idParagraph = $idParagraph_s;
        $this->markDirty();
    }
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
        $this->markDirty();
    }
    function setPos( $pos_s ) {
        $this->pos = $pos_s;
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


    function getName() {
        return $this->name;
    }
    function getAlt() {
        return $this->alt;
    }
    function getSmall() {
        return $this->small;
    }
    function getBig() {
        return $this->big;
    }
    function getIdParagraph() {
        return $this->idParagraph;
    }
    function getHide() {
        return $this->hide;
    }
    function getPos() {
        return $this->pos;
    }
    function getIdPosition() {
        return $this->idPosition;
    }
    function getIdCatalog() {
        return $this->idCatalog;
    }
}
?>