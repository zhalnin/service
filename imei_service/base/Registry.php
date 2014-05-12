<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 20:43
 */

namespace imei_service\base;

require_once( "imei_service/controller/AppController.php" );

/**
 * Class Registry
 * @package imei_service\base
 * Абстрактный класс для разных видов кэширования
 */
abstract class Registry {
    protected function __construct() {}
    abstract protected function get( $key );
    abstract protected function set( $key, $val );
}

/**
 * Class RequestRegistry
 * @package imei_service\base
 * Кэширует строку запроса из класса Request
 * по требованию возвращает ее
 */
class RequestRegistry extends Registry {
    private $values = array();
    private static $instance;

    /**
     * Синглтон
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Защищенный метод для возвращения строки запроса по ключу
     * @param $key
     * @return null
     */
    protected function get( $key ) {
        if( isset( $this->values[$key] ) ) {
            return $this->values[$key];
        }
        return null;
    }

    /**
     * Защищенный метод для сохранения строки запроса по паре ключ и значение
     * @param $key
     * @param $val
     */
    protected function set( $key, $val ) {
        $this->values[$key] = $val;
    }

    /**
     * Статический метод для вызова защищенного метода
     * @return null
     */
    static function getRequest() {
        return self::instance()->get( 'request' );
    }

    /**
     * Статический метод для вызова защищенного метода
     * @param \imei_service\controller\Request $request
     */
    static function setRequest( \imei_service\controller\Request $request ) {
        self::instance()->set( 'request', $request );
    }
}

/**
 * Class ApplicationRegistry
 * @package imei_service\base
 * Получаем данные( DSN и CMAP ) из ApplicationHelper при парсинге файла конфигурации
 * и сохраняет их файл
 * DSN - дескриптор базы данных
 * CMAP - карта приложения (команды, вьюшки, статусы и т.д.)
 */
class ApplicationRegistry extends Registry {
    private static $instance;
    private $freeezedir = "imei_service/data";
    private $values = array();
    private $mtimes = array();

    /**
     * Синглтон
     * @return ApplicationRegistry
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Защищенный метод для получения данных из файла
     * @param $key
     * @return mixed|null
     */
    protected function get( $key ) {
        $path = $this->freeezedir . DIRECTORY_SEPARATOR . $key; // "imei_service/data"."/"."dsn"
        if( file_exists( $path ) ) {    // если путь существует
            clearstatcache();   // очищаем кэш состояния файла
            $mtime = filemtime( $path ); // получаем время последнего изменения файла
            if( ! isset( $this->mtimes[$key] ) ) {  // если файл еще не изменяли,
                $this->mtimes[$key] = 0;    // то присваиваем ему значение 0
            }
            if( $mtime > $this->mtimes[$key] ) {    // если текущее время последнего изменения больше предыдущего времени последнего изменения (при сохранении в файл)
                $data = file_get_contents( $path ); // получаем данные из файла
                $this->mtimes[$key] = $mtime;   // обновляем значение времени последнего изменения файла текущим временем
                return ($this->values[$key] = unserialize( $data ) );   // ансериализуем данные, сохраняем и возвращаем массив
            }
        }
        if( isset( $this->values[$key] ) ) {
            return $this->values[$key];
        }
        return null;
    }

    /**
     * Защищенный метод для сохранения данных массив и далее в файл
     * @param $key ('dsn', 'cmap')
     * @param $val
     */
    protected function set( $key, $val ) {
        $this->values[$key] = $val; // кэшируем значение по ключу
        $path = $this->freeezedir . DIRECTORY_SEPARATOR . $key; // "imei_service/data"."/"."dsn"
        file_put_contents( $path, serialize( $val ) ); // сериализуем значение и добавляем в файл
        $this->mtimes[$key] = time();   // обновляем массив с временем изменения файла текущим временем
    }

    /**
     * Статический метод для получения дескриптора базы данных - из файла
     * @return mixed|null
     */
    static function getDSN() {
        return self::instance()->get( 'dsn' );
    }

    /**
     * Статический метод для сохранения дескриптора базы данных - в файл
     * @param $dsn
     */
    static function setDSN( $dsn ) {
        self::instance()->set( 'dsn', $dsn );
    }

    /**
     * Статический метод для получения карты команд и др. приложения - из файла
     * @return mixed|null
     */
    static function getControllerMap() {
        return self::instance()->get( 'cmap' );
    }

    /**
     * Статический метод для сохранения карты команд и др. приложения - в файл
     * @param \imei_service\controller\ControllerMap $map
     */
    static function setControllerMap( \imei_service\controller\ControllerMap $map ) {
        self::instance()->set( 'cmap', $map );
    }

    /**
     * Статичный метод, чтобы вернуть карту приложения и методы класса AppController
     * @return \imei_service\controller\AppController
     */
    static function appController() {
        $obj = self::instance();    // создание экземпляра текущего класса
        if( ! isset( $obj->appController ) ) {  // если объект не существует
            $cmap = $obj->getControllerMap();   // вызываем метод, для получения карты приложения
            $obj->appController = new \imei_service\controller\AppController( $cmap );  // создаем экземпляр AppController и передаем в него карту приложения
        }
        return $obj->appController; // возвращаем объект ( controllerMap - карту приложения, getCommand(), getView(), getResource(), getForward(), resolveCommand() )
    }
}