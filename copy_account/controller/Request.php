<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09/12/13
 * Time: 23:45
 * To change this template use File | Settings | File Templates.
 */

namespace account\controller;

class Request {
    private $properties;
    private $feedback = array();

    function __construct() {
        $this->init();
        \account\base\RequestRegistry::setRequest( $this );
    }

    function init() {
        if( isset( $_SERVER['REQUEST_METHOD'] ) ) {
            if( $_SERVER['REQUEST_METHOD'] ) {
                $this->properties = $_REQUEST;
                return;
            }
        }
        foreach( $_SERVER['argv'] as $arg ) {
            if( strpos( $arg, '=') ) {
                list( $key, $val ) = explode( '=', $arg );
                $this->properties[$key] = $val;
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

    function addFeedback( $msg ) {
        array_push( $this->feedback, $msg );
    }

    function getFeedback() {
        return $this->feedback;
    }

    function getFeedbackString( $separator="\n" ) {
        return implode( $separator, $this->feedback );
    }
}
?>