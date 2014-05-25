<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/12/13
 * Time: 22:40
 * To change this template use File | Settings | File Templates.
 */

namespace woo\base;

/**
 * Class Registry
 * Abstract class for cache
 * @package woo\base
 */
abstract class Registry {
    protected function __construct() {}
    abstract protected function get( $key );
    abstract protected function set( $key, $val );
}

/**
 * Class PDORegistry
 * @package woo\base
 * Создаем дескриптор БД
 */
class PDORegistry extends Registry {
    protected static $instance;
    protected static $pdo;
    protected $value = array();

    /**
     * Singleton
     * @return PDORegistry
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Проверяет наличие дескриптора в кэше или создает его
     * @param $key - 'pdo'
     * @return mixed - дескриптор соединения с БД
     * @throws AppException
     */
    protected function get( $key ) {
        // Если сохранен в кэше, то возвращаем его
        if( isset( $this->value[$key] ) ) {
            return $this->value[$key];
        }
        // Берем dsn в кэше ApplicationRegistry
        $dsn = \woo\base\ApplicationRegistry::getDSN();
        if( is_null( $dsn ) ) {
            throw new \woo\base\AppException("DSN is null in Registry");
        }
        try {
            // Создаем дескпритор
            self::$pdo = new \PDO( $dsn,'root','zhalnin5334',
                array(
                    \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
                )
            );
            // Сохраняем дескриптор БД в кэш
            \woo\base\PDORegistry::setPDO( self::$pdo );
//            return $this->value[$key];

        } catch ( \PDOException $ex ) {
            echo $ex->getMessage();
        }
        // Возвращаем дескриптор БД
        return $this->value[$key];
    }

    /**
     * Делегирует вызов через синглтон методу get()
     * @return mixed
     */
    static function getPDO(){
        return self::instance()->get('pdo');
    }

    /**
     * Сохраняем дескриптор БД в кэш
     * @param $key
     * @param $val
     */
    protected function set( $key, $val ) {
        $this->value[$key] = $val;
    }

    /**
     * Делегирует вызов через синглтон методу set()
     * @param $val
     */
    static function setPDO( $val ) {
        self::instance()->set( 'pdo', $val );
    }
}


/**
 * Class RequestRegistry
 * Cache Request's parameters
 * @package woo\base
 */
class RequestRegistry extends Registry {
    // array for saving $this->values['request'] = $_REQUEST
    private $values = array();
    // for singleton
    private static $instance;

    /**
     * Singleton
     * @return RequestRegistry
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Return array $values over getRequest()
     * @param $val
     * @return mixed
     */
    protected function get( $val ) {
        if( isset( $this->values[$val] ) ) {
            return $this->values[$val];
        }
        return null;
    }

    /**
     * Set 'request' and $val into array $values over setRequest()
     * @param $key
     * @param $val
     */
    protected function set( $key, $val ) {
        $this->values[$key] = $val;
    }

    /**
     * Return array Request, saved in Request.php
     * \woo\controller\Regsitry::setRequest($this) in __construct()
     * @return mixed
     */
    static function getRequest() {
        return self::instance()->get( 'request' );
    }

    /**
     * Set array Request in Request.php
     * \woo\controller\Regsitry::setRequest($this) in __construct()
     * @param \woo\controller\Request $request
     */
    static function setRequest( \woo\controller\Request $request ) {
        self::instance()->set( 'request', $request );
    }
}






/**
 * Class ApplicationRegistry
 * Help to cache parameters from woo_options.xml for future using in application
 * @package woo\base
 */
class ApplicationRegistry extends Registry {
    // for singleton
    private static $instance;
    // target file
    private $freezdir = "woo/tmp/data";
    // values for saving $this->values['dsn'] = 'woo/tmp/data/woo.db' and so on
    private $values = array();
    // array to save time of last write into file
    private $mtimes = array();

//    private $appController;


    /**
     * Singleton
     * @return ApplicationRegistry
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Return parameters ('dsn', 'cmap') from file in woo/tmp/data
     * @param $key
     * @return mixed|null
     */
    protected function get( $key ) {
        // take path to destination file ('dsn' or 'cmap')
        $path = $this->freezdir . DIRECTORY_SEPARATOR . $key;
        // check file existance
        if( file_exists( $path ) ) {
            // clear all information about operations with file
            clearstatcache();
            // save time of last write to file
            $mtime = filemtime( $path );
            // check if records of last write to file exist
            if( ! isset( $this->mtimes[$key] ) ) {
                // if no
                $this->mtimes[$key] = 0;
            }
            // compare current status of last write to file with our array
            if( $mtime > $this->mtimes[$key] ) {
                // take all data from file
                $data = file_get_contents( $path );
                // save status of last write to file
                $this->mtimes[$key] = $mtime;
                // add unserialized data to array and return it
                // where $key is 'dsn' or 'cmap'
                return ( $this->values[$key] = unserialize( $data ) );
            }
        }
        // if array exist, return it
        if( isset( $this->values[$key] ) ) {
            return $this->values[$key];
        }
        // or return null
        return null;
    }

    /**
     * For saving parameters ('dsn', 'cmap') to file woo/tmp/data
     * @param $key
     * @param $val
     */
    protected function set( $key, $val) {
        // add value to array with key of 'dsn' or 'cmap'
        $this->values[$key] = $val;
        // take path to file
        $path = $this->freezdir . DIRECTORY_SEPARATOR . $key;
        // save serialized parameters to file
        file_put_contents( $path, serialize( $val ) );
        // change array of status last write to file by time()
        $this->mtimes[$key] = time();
    }

    /**
     *  Return "dsn"
     * @return mixed|null
     */
    static function getDSN() {
        return self::instance()->get('dsn');
    }

    /**
     * Caches parameters "dsn" for future using
     * @param $dsn
     */
    static function setDSN( $dsn ) {
        self::instance()->set( 'dsn', $dsn );
    }

    /**
     * Caches parameters from woo_options.xml (view,alias,forward)
     * @param \woo\controller\ControllerMap $map
     */
    static function setControllerMap( \woo\controller\ControllerMap $map ) {
        self::instance()->set( 'cmap', $map );
    }

    /**
     * Return parameters (view,alias,forward)
     * @return mixed|null
     */
    static function getControllerMap() {
        return self::instance()->get('cmap');
    }

    static function appController() {
        $obj = self::instance();
        if( ! isset( $obj->appController ) ) {
            $cmap = $obj->getControllerMap();
            $obj->appController = new \woo\controller\AppController( $cmap );
        }
//        echo "<tt><pre>".print_r($obj->appController, true)."</pre></tt>";
        return $obj->appController;
    }
}

class SessionRegistry extends Registry {
    private static $instance;

    protected function __construct() {
        session_start();
    }

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get( $key ) {
        if( isset( $_SESSION[__CLASS__][$key] ) ) {
            return $_SESSION[__CLASS__][$key];
        }
        return null;
    }

    protected function set( $key, $val ) {
        $_SESSION[__CLASS__][$key] = $val;
    }

    function setComplex( Complex $complex ) {
        self::instance()->set( 'complex', $complex );
    }

    function getComplex() {
        return self::instance()->get( 'complex' );
    }
}


class MemApplicationRegistry extends Registry {
    protected static $instance;
    private $values = array();
    private $id;
    const DSN=1;

    protected function __construct() {
        $this->id = @shm_attach( 55, 10000, 0600 );
        if( ! $this->id ) {
            throw new \Exception( "Could not access to share memory" );
        }
    }

    static function instance() {
        if( ! isset( self::$instance ) ) { self::$instance = new self(); }
        return self::$instance;
    }

    protected function get( $key ) {
        return shm_get_var( $this->id, $key );
    }

    protected function set( $key, $val ) {
        return shm_put_var( $this->id, $key, $val );
    }

    static function getDSN() {
        return self::instance()->get( self::DSN );
    }

    static function setDSN( $dsn ) {
        return self::instance()->set( self::DSN, $dsn );
    }
}


?>