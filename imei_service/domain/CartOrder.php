<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/07/14
 * Time: 21:23
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );

class CartOrder extends DomainObject {

    private $firstname;
    private $lastname;
    private $email;
    private $country;
    private $address;
    private $city;
    private $zipCode;
    private $state;
    private $status;
    private $amount;
    private $paypalTransId;
    private $createdAt;
    private $data ;


    function __construct(   $id                 =null,
                            $firstname          =null,
                            $lastname           =null,
                            $email              =null,
                            $country            =null,
                            $address            =null,
                            $city               =null,
                            $zipCode            =null,
                            $state              =null,
                            $status             =null,
                            $amount             =null,
                            $paypalTransId      =null,
                            $createdAt          =null,
                            $data               =null ) {

        $this->firstname        = $firstname;
        $this->lastname         = $lastname;
        $this->email            = $email;
        $this->country          = $country;
        $this->address          = $address;
        $this->city             = $city;
        $this->zipCode          = $zipCode;
        $this->state            = $state;
        $this->status           = $status;
        $this->amount           = $amount;
        $this->paypalTransId    = $paypalTransId;
        $this->createdAt        = $createdAt;
        $this->data             = $data;

        parent::__construct( $id );
    }


    function setFirstName( $firstname_s ) {
        $this->firstname = $firstname_s;
        $this->markDirty();
    }
    function setLastName( $lastname_s ) {
        $this->lastname = $lastname_s;
        $this->markDirty();
    }
    function setEmail( $email_s ) {
        $this->email = $email_s;
        $this->markDirty();
    }
    function setCountry( $country_s ) {
        $this->country = $country_s;
        $this->markDirty();
    }
    function setAddress( $address_s ) {
        $this->address = $address_s;
        $this->markDirty();
    }
    function setCity( $city_s ) {
        $this->city = $city_s;
        $this->markDirty();
    }
    function setZipCode( $zipCode_s ) {
        $this->zipCode = $zipCode_s;
        $this->markDirty();
    }
    function setState( $state_s ) {
        $this->state = $state_s;
        $this->markDirty();
    }
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }
    function setAmount( $amount_s ) {
        $this->amount = $amount_s;
        $this->markDirty();
    }
    function setPayPalTransId( $paypalTransId_s ) {
        $this->paypalTransId = $paypalTransId_s;
        $this->markDirty();
    }
    function setCreatedAt( $createdAt_s ) {
        $this->createdAt = $createdAt_s;
        $this->markDirty();
    }
    function setData( $data_s ) {
        $this->data = $data_s;
        $this->markDirty();
    }


    function getFirstName() {
        return $this->firstname;
    }
    function getLastName() {
        return $this->lastname;
    }
    function getEmail() {
        return $this->email;
    }
    function getCountry() {
        return $this->country;
    }
    function getAddress() {
        return $this->address;
    }
    function getCity() {
        return $this->city;
    }
    function getZipCode() {
        return $this->zipCode;
    }
    function getState() {
        return $this->state;
    }
    function getStatus() {
        return $this->status;
    }
    function getAmount() {
        return $this->amount;
    }
    function getPaypalTransId() {
        return $this->paypalTransId;
    }
    function getCreatedAt() {
        return $this->createdAt;
    }
    function getData() {
        return $this->data;
    }

}
?>