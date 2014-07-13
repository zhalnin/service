<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 13/07/14
 * Time: 21:37
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class CartItems extends DomainObject {

    private $productId;
    private $orderId;
    private $title;
    private $price;
    private $qty;



    function __construct(   $id         =null,
                            $productId  =null,
                            $orderId    =null,
                            $title      =null,
                            $price      =null,
                            $qty        =null ) {

        $this->productId    = $productId;
        $this->orderId      = $orderId;
        $this->title        = $title;
        $this->price        = $price;
        $this->qty          = $qty;


        parent::__construct( $id );
    }


    function setProductId( $productId_s ) {
        $this->productId = $productId_s;
        $this->markDirty();
    }
    function setOrderId( $orderId_s ) {
        $this->orderId = $orderId_s;
        $this->markDirty();
    }
    function setTitle( $title_s ) {
        $this->title = $title_s;
        $this->markDirty();
    }
    function setAmout( $price_s ) {
        $this->price = $price_s;
        $this->markDirty();
    }
    function setQty( $qty_s ) {
        $this->qty = $qty_s;
        $this->markDirty();
    }


    function getProductId() {
        return $this->productId;
    }
    function getOrderId() {
        return $this->orderId;
    }
    function getTitle() {
        return $this->title;
    }
    function getPrice() {
        return $this->price;
    }
    function getQty() {
        return $this->qty;
    }
}
?>