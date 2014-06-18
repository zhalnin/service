<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/06/14
 * Time: 20:27
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );
require_once( "imei_service/mapper/LoginIdentityObject.php" );


class Login extends DomainObject {
    private $fio;
    private $city;
    private $email;
    private $login;
    private $pass;
    private $activation;
    private $status;

    function __construct(   $id=null,
                            $fio=null,
                            $city=null,
                            $email=null,
                            $login=null,
                            $pass=null,
                            $activation=null,
                            $status=null ) {

        $this->fio          = $fio;
        $this->city         = $city;
        $this->email        = $email;
        $this->login        = $login;
        $this->pass         = $pass;
        $this->activation   = $activation;
        $this->status       = $status;

        parent::__construct( $id );

    }




    function setFio( $fio_s ) {
        $this->fio = $fio_s;
        $this->markDirty();
    }
    function setCity( $city_s ) {
        $this->city = $city_s;
        $this->markDirty();
    }
    function setEmail( $email_s ) {
        $this->email = $email_s;
        $this->markDirty();
    }
    function setLogin( $login_s ) {
        $this->login = $login_s;
        $this->markDirty();
    }
    function setPass( $pass_s ) {
        $this->pass = $pass_s;
        $this->markDirty();
    }
    function setActivation( $activation_s ) {
        $this->activation = $activation_s;
        $this->markDirty();
    }
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }


    function getFio() {
        return $this->fio;
    }
    function getCity() {
        return $this->city;
    }
    function getEmail() {
        return $this->email;
    }
    function getLogin() {
        return $this->login;
    }
    function getPass() {
        return $this->pass;
    }
    function getActivation() {
        return $this->activation;
    }
    function getStatus() {
        return $this->status;
    }
} 