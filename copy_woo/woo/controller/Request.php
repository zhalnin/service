<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/12/13
 * Time: 19:42
 * To change this template use File | Settings | File Templates.
 */

namespace woo\controller;


class Request {
    private $appreg;
    private $properties;
    private $lastCommand;
    private $objects = array();
    private $feedback = array();


    function __construct() {
        $this->init();
        \woo\base\RequestRegistry::setRequest( $this );
    }

    /**
     * Initialize array properties with $_REQUEST either from browser or from console
     */
    function init() {
        // If browser
        if( isset( $_SERVER['REQUEST_METHOD'] ) ) {
            if( $_SERVER['REQUEST_METHOD'] ) {
                $this->properties = $_REQUEST;
                return;
                //            echo "<tt><pre>".print_r($this->properties,true)."</pre></tt>";
            }
        }
        // If console
        foreach ( $_SERVER['argv'] as $arg ) {
            // If in $arg exists "=", explode this
            if( strpos( $arg, "=" ) ) {
                list( $key, $val ) = explode( "=", $arg );
                $this->setProperty( $key, $val );
            }
        }

    }

    /**
     * Take array properties
     * @param $key
     * @return mixed
     */
    function getProperty( $key ) {
        if( isset( $this->properties[$key] ) ) {
            return $this->properties[$key];
        }
        return null;
    }


    /**
     * Set array properties
     * @param $key
     * @param $val
     */
    function setProperty( $key, $val ) {
        $this->properties[$key] = $val;
    }



    function __clone() {
        $this->properties = array();
    }

    /**
     * Add feedback to return in any View
     * @param $msg
     */
    function addFeedback( $msg ) {
        array_push( $this->feedback, $msg );
    }

    /**
     * Return message in View
     * @return array
     */
    function getFeedback() {
        return $this->feedback;
    }

    /**
     * Return message in View with separator
     * @param string $separator
     * @return string
     */
    function getFeedbackString( $separator="\n" ) {
        // Implode every string with separator
        return implode( $separator, $this->feedback );
    }

    /**
     * Return object equal nam = new Name()
     * @param $name ('venue')
     * @return null ( new Venue() )
     */
    function getObject( $name ) {
        if( isset( $this->objects[$name] ) ) {
            return $this->objects[$name];
        }
        return null;
    }

    /**
     * Set object equal name = new Name();
     * From (AddVenue.php->doExecute())
     * @param $name ('venue')
     * @param $object ( new Venue() )
     */
    function setObject( $name, $object ) {
        $this->objects[$name] = $object;
    }

    /**
     * Save command from Command->execute()
     * @param \woo\command\Command $command
     */
    function setCommand( \woo\command\Command $command ) {
        $this->lastCommand = $command;
    }

    /**
     * Return lastCommand
     * @return mixed
     */
    function getLastCommand() {
        return $this->lastCommand;
    }
}

?>