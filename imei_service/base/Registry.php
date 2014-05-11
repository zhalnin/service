<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 20:43
 */

namespace imei_service\base;

require_once( "imei_service/controller/AppController.php" );

abstract class Registry {
    protected function __construct() {}
    abstract protected function get( $key );
    abstract protected function set( $key, $val );
}

class RequestRegistry extends Registry {
    private $values = array();
    private static $instance;

    static function instance() {
        if( ! isset( self::$instance ) ) {
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

    static function setRequest( \imei_service\controller\Request $request ) {
        return self::instance()->set( 'request', $request );
    }
}

class ApplicationRegistry extends Registry {
    private static $instance;
    private $freeezedir = "imei_service/data";
    private $values = array();
    private $mtimes = array();

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        $path = $this->freeezedir . DIRECTORY_SEPARATOR . $key;
        if( file_exists( $path ) ) {
            clearstatcache();
            $mtime = filemtime( $path );
            if( ! isset( $this->mtimes[$key] ) ) {
                $this->mtimes[$key] = 0;
            }
            if( $mtime > $this->mtimes[$key] ) {
                $data = file_get_contents( $path );
                $this->mtimes[$key] = $mtime;
                return ($this->values[$key] = unserialize( $data ) );
            }
        }
        if( isset( $this->values[$key] ) ) {
            return $this->values[$key];
        }
        return null;
    }

    protected function set( $key, $val ) {
        $this->values[$key] = $val;
        $path = $this->freeezedir . DIRECTORY_SEPARATOR . $key;
        file_put_contents( $path, serialize( $val ) );
        $this->mtimes[$key] = time();
    }

    static function getDSN() {
        return self::instance()->get( 'dsn' );
    }

    static function setDSN( $dsn ) {
        self::instance()->set( 'dsn', $dsn );
    }

    static function getControllerMap() {
        return self::instance()->get( 'cmap' );
    }

    static function setControllerMap( \imei_service\controller\ControllerMap $map ) {
        self::instance()->set( 'cmap', $map );
    }

    static function appController() {
        $obj = self::instance();
        if( ! isset( $obj->appController ) ) {
            $cmap = $obj->getControllerMap();
            $obj->appController = new \imei_service\controller\AppController( $cmap );
        }
        return $obj->appController;
    }
}