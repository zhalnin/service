<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 22:19
 */

namespace imei_service\controller;


require_once( "imei_service/command/DefaultCommand.php" );

class AppController {
    private static $base_cmd;
    private static $default_cmd;
    private $controllerMap;
    private $invoked = array();

    function __construct( ControllerMap $map ) {
        $this->controllerMap = $map;
        if( ! self::$base_cmd ) {
            self::$base_cmd = new \ReflectionClass( "\\imei_service\\command\\Command" );
            self::$default_cmd = new \imei_service\command\DefaultCommand();
        }
    }

    function getCommand( Request $req ) {
        $previous = $req->getLastCommand();
        if( ! $previous ) {
            $cmd = $req->getProperty( 'cmd' );
            if( ! $cmd ) {
                $req->setProperty( 'cmd', 'default' );
                return self::$default_cmd;
            }
        } else {
            $cmd = $this->getForward( $req );
            if( ! $cmd ) { return null; }
        }

        $cmd_obj = $this->resolveCommand( $cmd );
        if( ! $cmd_obj ) {
            throw new \imei_service\base\AppException( "could not resolve '$cmd''" );
        }

        $cmd_class = get_class( $cmd_obj );
        if( isset( $this->invoked[$cmd_class] ) ) {
            throw new \imei_service\base\AppException( "circular forwarding" );
        }

        $this->invoked[$cmd_obj] = 1;
        return $cmd_obj;
    }


    function getForward( Request $req ) {
        $forward = $this->getResource( $req, "Forward" );
        if( $forward ) {
            $req->setProperty( 'cmd', $forward );
        }
        return $forward;
    }

    function getResource( Request $req, $res ) {
        $cmd_str = $req->getProperty( 'cmd' );
        $previous = $req->getLastCommand();
        $status = $previous->getStatus();
        if( ! $status ) { $status = 0; }
        $acquire = "get$res";
        $resource = $this->controllerMap->$acquire( $cmd_str, $status );
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( $cmd_str, 0 );
        }
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( 'default', $status );
        }
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( 'default', 0 );
        }
        return $resource;
    }

    function getView( Request $req ) {
        $view = $this->getResource( $req, "View" );
        return $view;
    }

    function resolveCommand( $cmd ) {
        $classroot = $this->controllerMap->getClassroot( $cmd );
        $filepath = "imei_service/command/$classroot.php";
        $classname = "\\imei_service\\command\\$classroot";
        if( file_exists( $filepath ) ) {
            require_once( "$filepath" );
            if( class_exists( $classname ) ) {
                $cmd_class = new \ReflectionClass( $classname );
                if( $cmd_class->isSubclassOf( self::$base_cmd ) ) {
                    return $cmd_class->newInstance();
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