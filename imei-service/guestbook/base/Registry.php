<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01/04/14
 * Time: 11:36
 * To change this template use File | Settings | File Templates.
 */

namespace guestbook\base;



abstract class Registry {
    protected function __construct(){}
    abstract protected function get( $key );
    abstract protected function set( $key, $val );
    function ensure( $expression, $msg ) {
        if( ! $expression ) {
            throw new \Exception( $msg );
        }
    }
}

class DBRegistry extends Registry {
    private static $instance;
    private $value = array();

    protected function __construct(){}

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        if( isset( $this->value[$key] ) ) {
            return $this->value[$key];
        }
        $dsn = ApplicationRegistry::getDSN( $key );
        if( is_null( $dsn ) ) {
            throw new \Exception( "DSN is NULL in DBRegistry" );
        }
        try {
            $pdo = new \PDO($dsn, 'root', 'zhalnin5334', array(
                \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
            ));
            $this->value[$key] = $pdo;
        } catch ( \PDOException $ex ) {
            print $ex->getMessage();
        }
        return $this->value[$key];
    }

    protected function set( $key, $value ) {
        $this->value[$key] = $value;
    }

    public static function getDSN() {
        return self::instance()->get('dsn');
    }

    public static function setDSN( $value ) {
        self::instance()->set('dsn', $value );
    }
}

class ApplicationRegistry extends Registry {
    private static $instance;
    private $value = array();
    private $dir = "guestbook/data";
    private $mtimes = array();

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        $path = $this->dir . DIRECTORY_SEPARATOR . $key;
        if( file_exists( $path ) ) {
            clearstatcache();
            $mtime = filemtime( $path );
            if( ! isset( $this->mtimes[$key] ) ) {
                $this->mtimes[$key] = 0;
            }
            if( $mtime > $this->mtimes[$key] ) {
                $data = file_get_contents( $path );
                $this->mtimes[$key] = $mtime;
                return ( $this->value[$key] = unserialize( $data ) );
            }
        }
        if( isset( $this->value[$key] ) ) {
            return $this->value[$key];
        }
        return null;
    }

    protected function set( $key, $val ) {
        $this->value[$key] = $val;
        $path = $this->dir . DIRECTORY_SEPARATOR . $key;
        file_put_contents( $path, serialize( $val ) );
        $this->mtimes[$key] = time();
    }

    public static function getDSN() {
        return self::instance()->get( 'dsn' );
    }

    public static function setDSN( $val ) {
        self::instance()->set( 'dsn', $val );
    }
}

//$ar = ApplicationRegistry::getDSN('dsn');
//
//echo "<tt><pre>".print_r($ar, true)."</pre></tt>";