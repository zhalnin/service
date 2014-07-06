<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11/06/14
 * Time: 19:12
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class Faq extends DomainObject {

    private $id;
    private $name;
    private $description;
    private $keywords;
    private $modrewrite;
    private $pos;
    private $hide;
    private $idParent;
    private $faqPositions;


    function __construct(   $id             =null,
                            $name           =null,
                            $description    =null,
                            $keywords       =null,
                            $modrewrite     =null,
                            $pos            =null,
                            $hide           ='show',
                            $idParent       =0 ) {

        $this->name = $name;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->modrewrite = $modrewrite;
        $this->pos = $pos;
        $this->hide = $hide;
        $this->idParent = $idParent;

        parent::__construct( $id );
    }





    function setFaqPosition( FaqPositionCollection $faqPosition ) {
//        echo "<tt><pre> setVenue - ".print_r($faqPosition,true)."</pre></tt>";
        $this->faqPositions = $faqPosition;
        $this->markDirty();
    }

    function getFaqPosition() {
        if( is_null( $this->faqPositions ) ) {
            return "getFaqPosition() returned null";
        }
        return $this->faqPositions;
    }








    static function findAll() {
        $finder = self::getFinder( __CLASS__ );
        $idobj = self::getIdentityObject( __CLASS__ );
        $faqIdobj = new \imei_service\mapper\FaqIdentityObject( 'hide' );
        $faqIdobj->eq( 'show' );
        return $finder->find( $faqIdobj );
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\FaqIdentityObject( 'id_parent' );
        return $finder->findOne( $idobj->eq( $id )->field( 'modrewrite' )->eq( 'faq' )->field( 'hide' )->eq( 'show' ) );
    }


    function setName( $name_s ) {
        $this->name = $name_s;
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
    function setIdParent( $idParent_s ) {
        $this->idParent = $idParent_s;
        $this->markDirty();
    }


    function getName() {
        return $this->name;
    }
    function getDescription() {
        return $this->description;
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
    function getIdParent() {
        return $this->idParent;
    }
}
?>