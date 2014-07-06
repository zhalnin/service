<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 12/06/14
 * Time: 17:18
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class FaqParagraph extends DomainObject {

    private $id;
    private $name;
    private $type;
    private $align;
    private $hide;
    private $pos;
    private $idPosition;
    private $idCatalog;

    private $faqPositions;
    private $faqParagraphImages;


    function __construct(   $id             =null,
                            $name           =null,
                            $type           =null,
                            $align          =null,
                            $hide           ='show',
                            $pos            =null,
                            $idPosition     =null,
                            $idCatalog      =0 ) {

        $this->name = $name;
        $this->type = $type;
        $this->align = $align;
        $this->hide = $hide;
        $this->pos = $pos;
        $this->idPosition = $idPosition;
        $this->idCatalog = $idCatalog;

        parent::__construct( $id );
    }


    function setFaqParagraphImage( FaqParagraphImageCollection $faqParagraphImage ) {
//        echo "<tt><pre> setVenue - ".print_r($faqParagraphImage,true)."</pre></tt>";
        $this->faqParagraphImages = $faqParagraphImage;
        $this->markDirty();
    }

    function getFaqParagraphImage() {
        if( is_null( $this->faqParagraphImages ) ) {
            return "getFaqParagraphImages() returned null";
        }
        return $this->faqParagraphImages;
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

    static function find( $idPosition, $idCatalog ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\FaqParagraphIdentityObject( 'id_position' );
        return $finder->find( $idobj->eq( $idPosition )->field( 'id_catalog' )->eq( $idCatalog )->field( 'hide' )->eq( 'show' ) );
    }


    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    function setType( $type_s ) {
        $this->type = $type_s;
        $this->markDirty();
    }
    function setAlign( $align_s ) {
        $this->align = $align_s;
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
    function getType() {
        return $this->type;
    }
    function getAlign() {
        return $this->align;
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