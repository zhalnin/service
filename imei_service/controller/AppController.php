<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 22:19
 */

namespace imei_service\controller;



class AppController {
    private static $base_cmd;
    private static $default_cmd;
    private $controllerMap;
    private $invoked = array();

}

class ControllerMap {
    private $viewMap = array();
    private $forwardMap = array();
    private $classrootMap = array();

//    function addClassroot( $command, $classroot ) {
//        $this->classrootMap[$command] = $classroot;
//    }
//
//    function getClassroot( $command ) {
//        if( isset( $this->classrootMap[$command] ) ) {
//            return $this->classrootMap[$command];
//        }
//        return $command;
//    }

    function addView( $commmand='default', $status=0, $view ) {
        $this->viewMap[$commmand][$status] = $view;
    }

    function getView( $command, $status ) {
        if( isset( $this->viewMap[$command][$status] ) ) {
            return $this->viewMap[$command][$status];
        }
        return null;
    }

//    function addForward( $command, $status=0, $newCommand ) {
//        $this->forwardMap[$command][$status] = $newCommand;
//    }
//
//    function getForward( $command, $status ) {
//        if( isset( $this->forwardMap[$command][$status] ) ) {
//            return $this->forwardMap[$command][$status];
//        }
//        return null;
//    }
}
?>