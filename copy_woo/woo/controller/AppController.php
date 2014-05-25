<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 29/12/13
 * Time: 20:53
 * To change this template use File | Settings | File Templates.
 */

namespace woo\controller;

require_once( 'woo/command/Command.php' );
require_once( 'woo/command/DefaultCommand.php' );


class AppController {
    // to save instance of class Command
    private static $base_cmd;
    // to save instance of class DefaultCommand
    private static $default_cmd;
    // to save parsed parameters from woo_options.xml
    private $controllerMap;
    // Если класс вызывался в предыдущей команде, то выставляем 1,
    // чтобы не было рекурсивного вызова
    private $invoked = array();


    /**
     * Constructor
     * Из Registry ApplicationRegistry::appController()
     * @param ControllerMap $map
     */
    function __construct( ControllerMap $map ) {
        // save to array parsed parameters from woo_options.xml
        $this->controllerMap = $map;
        // check if exist $base_cmd
        if( ! self::$base_cmd ) {
            // if not, save class Command
            self::$base_cmd = new \ReflectionClass( "\woo\command\Command" );
            // if not, save class DefaultCommand
            self::$default_cmd = new \woo\command\DefaultCommand();
        }
    }

    // from Controller.php
    function getView( Request $req ) {
//        echo "<tt><pre> GETVIEW - ".print_r($req, true)."</pre></tt>";

        // take resource = quickadd
        $view = $this->getResource( $req, "View" );
//        echo "<tt><pre> GETVIEW - ".print_r($view, true)."</pre></tt>";
        return $view;
    }


    function getForward( Request $req ) {
//        echo "<tt><pre>".print_r($req, true)."</pre></tt>";
        // if getForward(QuickAddVenue,0) exists
        $forward = $this->getResource( $req, "Forward" );
        if( $forward ) {
            // set property ?cmd
            $req->setProperty('cmd', $forward );
        }
//        echo "<tt><pre> getForward ".print_r($forward, true)."</pre></tt>";
        return $forward;
    }

    // take params - Request with all query string and $res (View || Forward)
    function getResource( Request $req, $res ) {
        // take property cmd=QuickAddVenue
        $cmd_str = $req->getProperty( 'cmd' );
//        echo "<tt><pre> GETRESOURCE CMD_STR - ".print_r($cmd_str, true)."</pre></tt>";
        // check if command exists already
        $previous = $req->getLastCommand();
//        echo "<tt><pre> getResource PREVIOUS - ".print_r($previous, true)."</pre></tt>";
        // initialize $status
        $status = $previous->getStatus();
//        echo "<tt><pre>".print_r($status, true)."</pre></tt>";
        // if not exists, initialize with 0
        if( ! $status ) { $status=0; }
        // getView || getForward
        $acquire = "get$res";
        // from $cmap getForward(QuickAddVenue,0or1or2or3) || getView(AddVenue,0||1||2||3)
        $resource = $this->controllerMap->$acquire( $cmd_str, $status );
//          echo "<tt><pre> PARAMS - ".print_r($cmd_str, true)."</pre></tt>";
//        echo "<tt><pre> PARAMS - ".print_r($this->controllerMap, true)."</pre></tt>";
//        echo "1";
//        echo "<tt><pre> GETRESOURCE RESOURCE - ".print_r($resource, true)."</pre></tt>";
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( $cmd_str, 0 );
//            echo "2";
        }
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( 'default', $status );
//            echo "3";
        }
        if( ! $resource ) {
            $resource = $this->controllerMap->$acquire( 'default', 0 );
//            echo "4";
        }
//        echo "<tt><pre> PARAMS - ".print_r($resource, true)."</pre></tt>";
        return $resource;
    }

    function getCommand( Request $req ) {
        // if last command exists, save it
        $previous = $req->getLastCommand();
//        echo "<tt><pre> getCommand previous - ".print_r($req, true)."</pre></tt>";
        // if not exists - значит скрипт запускался впервые или без параметра "cmd"
        if( ! $previous ) {
            // take property ?cmd=QuickAddVenue
            $cmd = $req->getProperty('cmd');
//            echo "<tt><pre> getCommand Cmd First - ".print_r($cmd, true)."</pre></tt>";
            // if does not exist ?cmd=....
            if( ! $cmd ) {
                // set property equal to ?cmd=default
                $req->setProperty('cmd', 'default' );
                // return new \woo\command\DefaultCommand;
                return self::$default_cmd;
            }
        // if exists - скрипт запускается уже повторно, либо с параметром "cmd", но без значения,
        // либо имеет переадресацию для дальнейшего выполнения сценария
        } else {
            // if exist previous command, take forward command,
            // for example: AddVenue - AddSpace and AddVenue
            $cmd = $this->getForward( $req );
//            echo "<tt><pre> CMD SECOND - ".print_r($cmd, true)."</pre></tt>";
//            echo "<tt><pre> CMD - ".print_r($cmd, true)."</pre></tt>";
            // if AddVenue - если это все еще таже команда, которая не имеет переадресации
            if( ! $cmd ) { return null; }
        }

        // Check if exist alias to $cmd(AddVenue may be QuickAddVenue)
        // return instance of target class - class woo\command\AddVenue
        $cmd_obj = $this->resolveCommand( $cmd );
//        echo "<tt><pre> CMD_OBJ - ".print_r($cmd_obj, true)."</pre></tt>";
        if( ! $cmd_obj ) {
            throw new \woo\base\AppException( "couldn't resolve '$cmd''");
        }
        // take class woo/command/AddVenue
        $cmd_class = get_class( $cmd_obj );
        // if invoked array has value woo/command/AddVenue == 1(true)
        if( isset( $this->invoked[$cmd_class] ) ) {
            throw new \woo\base\AppException( "circular forwarding" );
        }
//        echo "<tt><pre> 0 - ".print_r($this->invoked[$cmd_class], true)."</pre></tt>";
        // add 1 to invoked array
        $this->invoked[$cmd_class] = 1;
//        echo "<tt><pre> 1 - ".print_r($this->invoked[$cmd_class], true)."</pre></tt>";
        // return class AddVenue
        return $cmd_obj;
//        echo "<tt><pre>".print_r($cmd_obj, true)."</pre></tt>";
    }

    function resolveCommand( $cmd ) {
//        echo "<tt><pre> CMD - ".print_r($cmd, true)."</pre></tt>";
        // look for alias for AddVenue. It is QuickAddVenue
        // getClassroot returns classalias, if it doesn't exist, return $cmd
        $classroot = $this->controllerMap->getClassroot( $cmd );
//        echo "<tt><pre> CLASSROOT - ".print_r($classroot, true)."</pre></tt>";
        // path to command - woo/command/AddVenue.php
        $filepath = "woo/command/$classroot.php";
        // class of \woo\command\AddVenue
        $classname = "\\woo\\command\\$classroot";
        // if path exists
        if( file_exists( $filepath ) ) {
            // include this AddVenue.php with class AddVenue
            require_once( "$filepath" );
            // if class exists
            if( class_exists( $classname ) ) {
                // make copy of AddVenue's class
                $cmd_class = new \ReflectionClass($classname);
                // if AddVenue is subclass of Command
                if( $cmd_class->isSubclassOf( self::$base_cmd ) ) {
                    // return new instance of AddVenue's class
                    return $cmd_class->newInstance();
                }
            }
        }
        // if filepath does not exist, return null
        return null;
//        echo "<tt><pre>".print_r($classname, true)."</pre></tt>";
    }
}


/**
 * Class ControllerMap
 * to save parameters from woo_options.xml
 * where is view, alias, forward
 * @package woo\controller
 */
class ControllerMap {
    // array views
    private $viewMap = array();
    // array aliases
    private $classrootMap = array();
    // array forwards
    private $forwardMap = array();

    /**
     * Run from ApplicationHelper->getOptions()
     * @param string $command
     * @param int $status
     * @param $view
     * 'default' --- 0 --- main
     * 'default' --- 1 --- main
     * 'default' --- 2 --- error
     */
    function addView( $command='default', $status=0, $view ) {
        $this->viewMap[$command][$status] = $view;
    }

    /**
     * Return value of view
     * 'default' --- 0 --- main
     * 'default' --- 1 --- main
     * 'default' --- 2 --- error
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
     * Add alias if exist (AddVenue may have alias QuickAddVenue)
     * @param $command
     * @param $classroot
     */
    function addClassroot( $command, $classroot ) {
        $this->classrootMap[$command] = $classroot;
    }

    function getClassroot( $command ) {
//        echo "<tt><pre> getClassroot - ".print_r($this->$command, true)."</pre></tt>";
        if( isset( $this->classrootMap[$command] ) ) {
            return $this->classrootMap[$command];
        }
        return $command;
    }

    /**
     * Add new command which have to execute if
     * invoke certain command (AddSpace --- ListVenues)
     * @param $command
     * @param int $status
     * @param $newCommand
     */
    function addForward( $command, $status=0, $newCommand ) {
        $this->forwardMap[$command][$status] = $newCommand;
    }

    function getForward( $command, $status ) {
//        echo "<tt><pre> getForward - ".print_r($this->, true)."</pre></tt>";
        if( isset( $this->forwardMap[$command][$status] ) ) {
            return $this->forwardMap[$command][$status];
        }
        return null;
    }
}

?>