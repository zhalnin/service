<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09/12/13
 * Time: 21:10
 * To change this template use File | Settings | File Templates.
 */

namespace account\base;

error_reporting(E_ALL & ~E_NOTICE);

abstract class Registry {
    abstract protected function get( $key );
    abstract protected function set( $key, $val );
}

class DataBaseRegistry extends Registry {
    private $properties = array();
    private static $instance;

    private function __construct() {}

    static function instance() {
        if( ! self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        if( isset( $this->properties[$key] ) ) {
            return $this->properties[$key];
        }
    }

    protected function set( $key, $val ) {
        $this->properties[$key] = $val;
    }

    static function getDB() {
        return self::instance()->get('database');
    }

    static function setDB( $db ) {
        self::instance()->set('database', $db );
    }


//Registry::set('db', new PDO("mysql:host=localhost; dbname=base",$name, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")));

//$db = Registry::get('db');

//$db->query(....); // ? ?.?.

//Registry::get('db')->query(...); // ? ?.?.


}

class RequestRegistry extends Registry {
    private $values = array();
    private static $instance;

    private function __construct() {}

    static function instance() {
        if(!self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        if( isset( $this->values[$key] ) ) {
            return $this->values[$key];
        }
        return null;
    }

    protected function set( $key, $val ) {
        $this->values[$key] = $val;
    }

    static function getRequest() {
        return self::instance()->get( 'request' );
    }

    static function setRequest( $request ) {
//        echo "<tt><pre>".print_r($request,true)."</pre></tt>";
        self::instance()->set( 'request', $request );
    }
}


class SessionRegistry extends Registry {
    private static $instance;

    function __construct() {
        session_start();
    }

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        if( isset( $_SESSION[__CLASS__][$key]) ) {
            return $_SESSION[__CLASS__][$key];
        }
        return null;
    }


    protected function set( $key, $val ) {
        $_SESSION[__CLASS__][$key] = $val;

    }

    static function getSession($key) {
        return self::instance()->get($key);
    }

    static function setSession( $key, $val ) {
        self::instance()->set( $key , $val );
    }


}

?>