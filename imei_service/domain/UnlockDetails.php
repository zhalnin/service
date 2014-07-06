<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09/06/14
 * Time: 22:11
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );
//require_once( "imei_service/mapper/UnlockIdentityObject.php" );

class UnlockDetails extends DomainObject {

    private $operator;
    private $cost;
    private $timeconsume;
    private $compatible;
    private $status;
    private $currency;
    private $hide;
    private $pos;
    private $putdate;
    private $idCatalog;
    private $unlock;


    function __construct(   $id             =null,
                            $operator       =null,
                            $cost           =null,
                            $timeconsume    =null,
                            $compatible     =null,
                            $status         =null,
                            $currency       =null,
                            $hide           ='show',
                            $pos            =null,
                            $putdate        =null,
                            $idCatalog      =null ) {

        $this->operator = $operator;
        $this->cost = $cost;
        $this->timeconsume = $timeconsume;
        $this->compatible = $compatible;
        $this->status = $status;
        $this->currency = $currency;
        $this->hide = $hide;
        $this->pos = $pos;
        $this->putdate = $putdate;
        $this->idCatalog = $idCatalog;

        parent::__construct( $id );
    }

    static function findAll( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = self::getIdentityObject( __CLASS__ );
        $unlockIdobj = new \imei_service\mapper\UnlockDetailsIdentityObject( 'id_catalog' );
        $unlockIdobj->eq( $id )->field( 'hide' )->eq( 'show' );
        return $finder->find( $unlockIdobj );
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\UnlockDetailsIdentityObject( 'id_catalog' );
        return $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'show' ) );
    }

    /**
     * Выборка для корзины по каталогу и позиции
     * @param $pos
     * @param $id_catalog
     * @return mixed
     */
    static function findByPosAndCat( $pos, $id_catalog ) {
        $finder = self::getFinder( __CLASS__ );
        $unlockIdobjCart = new \imei_service\mapper\UnlockDetailsIdentityObject( 'id_catalog' );
        $unlockIdobjCart->eq( $id_catalog )->field( 'pos' )->eq( $pos );
        return $finder->find( $unlockIdobjCart );
    }
//    function setUnlock( UnlockCollection $unlock ) {
//        $this->unlock = $unlock;
//    }
//
//    function getUnlock() {
//        if( is_null( $this->unlock ) ) {
//            $idobj = new \imei_service\mapper\UnlockIdentityObject( 'id_catalog' );
//            $this->unlock = self::getFinder( 'imei_service\\domain\\Unlock' )->find( $idobj->eq( 0 )->field( 'hide' )->eq( 'show' ) );
//        }
//        return $this->unlock;
//    }

    function setOperator( $operator_s ) {
        $this->operator = $operator_s;
        $this->markDirty();
    }
    function setCost( $cost_s ) {
        $this->cost = $cost_s;
        $this->markDirty();
    }
    function setTimeconsume( $timeconsume_s ) {
        $this->timeconsume = $timeconsume_s;
        $this->markDirty();
    }
    function setCompatible( $compatible_s ) {
        $this->compatible = $compatible_s;
        $this->markDirty();
    }
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }
    function setCurrency( $currency_s ) {
        $this->currency = $currency_s;
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
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }
    function setIdCatalog( $idCatalog_s ) {
        $this->idCatalog = $idCatalog_s;
        $this->markDirty();
    }


    function getOperator() {
        return $this->operator;
    }
    function getCost() {
        return $this->cost;
    }
    function getTimeconsume() {
        return $this->timeconsume;
    }
    function getCompatible() {
        return $this->compatible;
    }
    function getStatus() {
        return $this->status;
    }
    function getCurrency() {
        return $this->currency;
    }
    function getHide() {
        return $this->hide;
    }
    function getPos() {
        return $this->pos;
    }
    function getPutdate() {
        return $this->putdate;
    }
    function getIdCatalog() {
        return $this->idCatalog;
    }
}


?>