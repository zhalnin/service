<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/12/13
 * Time: 17:40
 * To change this template use File | Settings | File Templates.
 */

namespace woo\controller;

require_once( 'woo/base/Registry.php' );
require_once( 'woo/base/Exceptions.php' );
require_once( 'woo/controller/AppController.php' );


class ApplicationHelper {
    private static $instance;
    private $config = "woo/tmp/data/woo_options.xml";

    private function __construct() {}

    /**
     * Singelton
     * @return ApplicationHelper (self::$instance)
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Run from Controller
     * to take 'dsn'
     */
    function init() {
        // If file dsn exists in ApplicationRegistry) - take it
        $dsn = \woo\base\ApplicationRegistry::getDSN();
        if( ! is_null( $dsn ) ) {
            return;
        }
        // If dsn does not exist - start getOptions()
        $this->getOptions();
    }

    /**
     * Helper function!!!
     * If "dsn" does not exist start getOptions and take
     * dsn
     * save dsn into cache (ApplicationRegistry)
     * And save additional params from "woo_options.xml" into ApplicationController (ControllerMap)
     */
    private function getOptions() {
        // check file exist
        $this->ensure( file_exists( $this->config ), "File doesn't exist!" );
        // load this xml file
        $options = @simplexml_load_file( $this->config );
        // check if it is instance of SimpleXMLElement
        $this->ensure( $options instanceof \SimpleXMLElement, "Could not resolve woo_options.xml!" );
        // take params: "dsn" from xml file
        $dsn = (string)$options->dsn;
        // check it exist
        $this->ensure( $dsn, "No DSN found!" );
        // save "dsn" into cache
        \woo\base\ApplicationRegistry::setDSN( $dsn );
        // create instance of ApplicationController (ControllerMap)
        $map = new ControllerMap();

        // Add default view for application from woo_options.xml
        foreach ( $options->control->view as $default_view ) {
            // take attributes (status) from elements (view) from woo_options.xml
            $stat_str = trim( $default_view['status'] );
            // run static method "statuses" from Command and
            // take set of class statuses value by its key
            //        'CMD_DEFAULT'           => 0,
            //        'CMD_OK'                => 1,
            //        'CMD_ERROR'             => 2,
            //        'CMD_INSUFFICIENT_DATA' => 3
            $status = \woo\command\Command::statuses( $stat_str );
            // 'default' --- 0 --- main
            // 'default' --- 1 --- main
            // 'default' --- 2 --- error
            // add it to AppController->ControllerMap->addView - viewMap's array
            $map->addView( 'default', $status, (string)$default_view );
        }

        // take view for every command ( alias, forward )
        foreach( $options->control->command as $command_view ) {
            // take attributes "name" from every command's view
            $command = trim( (string)$command_view['name'] );
            // if this view consist alias
            if( $command_view->classalias ) {
                // take name of alias
                $classroot = trim( (string)$command_view->classalias['name'] );
//                echo "<tt><pre>".print_r($command_view,true)."</tt></pre>";
//                echo "<tt><pre>".print_r($classroot,true)."</tt></pre>";
                // add this alias to AppController->ControllerMap->addClassroot - classrootMap's array
                $map->addClassroot( $command, $classroot );
            }
            // if tag command has view
            if( $command_view->view ) {
                // take this view
                $view = trim( (string)$command_view->view );
                // take this forward
                $forward = trim( (string)$command_view->forward );
                // add this view to AppController->ControllerMap->addView - viewMap's array
                // <any command> --- 0 --- <view>
                $map->addView( $command, 0, $view );
                // if $forward exist
                if( $forward ) {
                    // add this forward to AppController->ControllerMap->addForward - forwardMap's array
                    // <any command> --- 0 --- <forward>
                    $map->addForward( $command, 0, $forward );
                }
                foreach ( $command_view->status as $command_view_status ) {
                    $view = trim( (string)$command_view_status->view );
                    $forward = trim( (string)$command_view_status->forward );
                    $stat_str = trim( $command_view_status['value'] );
//                    echo "<tt><pre> STAT_STR - ".print_r($stat_str, true)."</pre></tt>";
                    $status = \woo\command\Command::statuses( $stat_str );
                    if( $view ) {
                        $map->addView( $command, $status, $view );
                    }
                    if( $forward ) {
                        $map->addForward( $command, $status, $forward );
                    }
                }
            }
            // add to cache all available params from woo_options.xml
            \woo\base\ApplicationRegistry::setControllerMap( $map );
        }
//        echo "<tt><pre>".print_r($map,true)."</pre></tt>";
    }

    /**
     * Custom function to check params
     * @param $expr
     * @param $msg
     * @throws \woo\base\AppException
     */
    private function ensure( $expr, $msg ) {
        if( ! $expr ) {
            throw new \woo\base\AppException( $msg );
        }
    }
}

?>