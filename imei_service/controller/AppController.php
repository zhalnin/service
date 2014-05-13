<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 22:19
 */

namespace imei_service\controller;


require_once( "imei_service/command/DefaultCommand.php" );


/**
 * Class AppController
 * @package imei_service\controller
 * Принимает карту приложения
 * Выполняем действия с командами приложения
 */
class AppController {
    private static $base_cmd;
    private static $default_cmd;
    private $controllerMap;
    private $invoked = array();

    function __construct( ControllerMap $map ) {
        $this->controllerMap = $map;    // карта приложения
//        echo "<tt><pre>".print_r($this->controllerMap, true)."</pre></tt>";
        if( ! self::$base_cmd ) {
            self::$base_cmd = new \ReflectionClass( "\\imei_service\\command\\Command" );   // абстрактный класс Command
            self::$default_cmd = new \imei_service\command\DefaultCommand();    // класс по умолчанию (будет вызываться, если нет параметров)
        }
    }

    /**
     * Принимает объект Request с параметрами запроса и возвращаем команду для выполнения
     * @param Request $req
     * @return \imei_service\command\DefaultCommand|null|object
     * @throws \imei_service\base\AppException
     */
    function getCommand( Request $req ) {
        $previous = $req->getLastCommand(); // проверяем, была ли команда на выполнение
        if( ! $previous ) { // если не было - т.е. приложение запущено в первый раз
            $cmd = $req->getProperty( 'cmd' );  // получаем значение параметра 'cmd' - команду
            if( ! $cmd ) {  // если команды нет
                $req->setProperty( 'cmd', 'default' );  // то, в класс Request добавляем 'cmd' равную 'default'
                return self::$default_cmd;  // возвращаем класс по умолчанию - new \imei_service\command\DefaultCommand();
            }
        } else {    // если приложение было запущено уже не в первый раз
            $cmd = $this->getForward( $req );   // проверяем запрос на наличие перенаправления, т.е. предыдущая команда выполнена и по статусу выполняется команда по перенаправлению
            if( ! $cmd ) { return null; }   // если перенаправления нет, то заканчивается выполнение метода
        }

        $cmd_obj = $this->resolveCommand( $cmd );   // если была команда для перенаправления или просто корневого класса, то возвращаем экземпляр класса этой команды
        if( ! $cmd_obj ) {  // если команда не известная
            throw new \imei_service\base\AppException( "could not resolve '$cmd''" );
        }

        $cmd_class = get_class( $cmd_obj ); // получаем имя класса команды перенаправления или просто корневого класса
        if( isset( $this->invoked[$cmd_class] ) ) { // если флаг выставлен, то приложение вызывается по кругу
            throw new \imei_service\base\AppException( "circular forwarding" );
        }

        $this->invoked[$cmd_obj] = 1; // выставляем флаг
        return $cmd_obj;    // возвращаем объект команда класса перенаправления или просто корневого класса
    }


    /**
     * Если имеется команда перенаправления,
     * то возвращаем её
     * @param Request $req
     * @return mixed
     */
    function getForward( Request $req ) {
        $forward = $this->getResource( $req, "Forward" ); // получаем команду перенаправления
        if( $forward ) {
            $req->setProperty( 'cmd', $forward );   // если команда перенаправления найдена, то добавляем ее в классе Request
        }
        return $forward; // возвращаем команду
    }

    /**
     * Для поиска в карте приложения команды
     * (getForward - перенаправление, getView - вьюшка, getClassroot - псевдоним)
     * ищем по команде и статусу
     * @param Request $req
     * @param $res
     * @return mixed
     */
    function getResource( Request $req, $res ) {
        $cmd_str = $req->getProperty( 'cmd' );  // из строки запроса получаем команду cmd=General
        $previous = $req->getLastCommand(); // получаем команду, которая была выполнена до вызова текущей команды
        $status = $previous->getStatus();   // получаем ее статус выполнения в цифровом виде
        if( ! $status ) { $status = 0; }    // если нет статуса, то присваиваем ему 0
        $acquire = "get$res";   // динамически получаем метод для поиска команды
        $resource = $this->controllerMap->$acquire( $cmd_str, $status );    // получаем по команде и статусу команду
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( $cmd_str, 0 );  // тогда ищем по команде и статусу = 0
        }
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( 'default', $status );   // тогда ищем по дефолтной команде и полученному статусу
        }
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( 'default', 0 ); // тогда ищем по дефолтной команде и статусу = 0
        }
        return $resource;   // тогда просто возвращаем команду
    }

    function getView( Request $req ) {
        $view = $this->getResource( $req, "View" );
        return $view;
    }

    /**
     * @param $cmd
     * @return null|object
     */
    function resolveCommand( $cmd ) {
        $classroot = $this->controllerMap->getClassroot( $cmd ); // если псевдонима нет, метод возвращает саму команду(т.е. он и есть корневая команда)
        $filepath = "imei_service/command/$classroot.php";  // путь до скрипта - он же имя команды
        $classname = "\\imei_service\\command\\$classroot"; // в скрипте должен быть класс такого же названия
        if( file_exists( $filepath ) ) {    // если есть
            require_once( "$filepath" );    // включаем скрипт
            if( class_exists( $classname ) ) {  // если есть класс в нем
                $cmd_class = new \ReflectionClass( $classname );    // берем информацию о классе
                if( $cmd_class->isSubclassOf( self::$base_cmd ) ) { // если он подкласс класса Command (он должен быть им)
                    return $cmd_class->newInstance(); // возвращаем его экземпляр (объект)
                }
            }
        }
        return null;
    }



}

/**
 * Class ControllerMap
 * @package imei_service\controller
 * Кэширует данные из файла конфигурации xml
 */
class ControllerMap {
    private $viewMap = array();
    private $forwardMap = array();
    private $classrootMap = array();

    /**
     * Добавялет к массиву псевдонимов команды: [команда, псевдоним]
     * @param $command
     * @param $classroot
     */
    function addClassroot( $command, $classroot ) {
        $this->classrootMap[$command] = $classroot;
    }

    /**
     * Получаем псевдоним по имени команды
     * @param $command
     * @return mixed
     */
    function getClassroot( $command ) {
        if( isset( $this->classrootMap[$command] ) ) {
            return $this->classrootMap[$command];
        }
        return $command;
    }

    /**
     * Добавляет к массиву вьюшек: [команда, цифровой статус, название вьюшки]
     * @param string $commmand
     * @param int $status
     * @param $view
     */
    function addView( $commmand='default', $status=0, $view ) {
        $this->viewMap[$commmand][$status] = $view;
    }

    /**
     * Получаем имя вьюшки по имени команды и ее статусу
     * @param $command
     * @param $status
     * @return null
     */
    function getView( $command, $status ) {
        if( isset( $this->viewMap[$command][$status] ) ) {
            return $this->viewMap[$command][$status];
        }
        return null;
    }

    /**
     * Добавляем к массиву переадресации имя переадресации: [команда, цифровой статус, переадресация]
     * @param $command
     * @param int $status
     * @param $newCommand
     */
    function addForward( $command, $status=0, $newCommand ) {
        $this->forwardMap[$command][$status] = $newCommand;
    }

    /**
     * Получаем  имя переадресации
     * @param $command
     * @param $status
     * @return null
     */
    function getForward( $command, $status ) {
        if( isset( $this->forwardMap[$command][$status] ) ) {
            return $this->forwardMap[$command][$status];
        }
        return null;
    }
}
?>