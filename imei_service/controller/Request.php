<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 23:34
 */

namespace imei_service\controller;



class Request {
    private $appreg;
    private $properties;
    private $objects = array();
    private $feedback = array();
    private $lastCommand;
    private $tmp = array();

    function __construct() {
        $this->init();
        \imei_service\base\RequestRegistry::setRequest( $this );
    }

    function init() {
        if( isset( $_SERVER['REQUEST_METHOD'] ) ) {
            if( $_REQUEST['REQUEST_METHOD'] ) {
                $this->properties = $_REQUEST;
                return;
            }
        }
        foreach ( $_SERVER['argv'] as $args ) {
            if( strpos( $args, '&' ) ) {
                $tmp = explode( '&', $args );
                foreach( $tmp as $arg ) {
                    if( strpos( $arg, '=' ) ) {
                        list( $key, $val ) = explode( "=", $arg );
                        $this->setProperty( $key, $val );
                    }
                }
            }
        }
    }

    function getProperty( $key ) {
        if( isset( $this->properties[$key] ) ) {
            return $this->properties[$key];
        }
    }

    function setProperty( $key, $val ) {
        $this->properties[$key] = $val;
    }

    function __clone() {
        $this->properties = array();
    }
}