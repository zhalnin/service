<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 19:25
 */
namespace imei_service\controller;

require_once( "imei_service/controller/ApplicationHelper.php" );
require_once( "imei_service/controller/Request.php" );

class Controller {
    private $applicationHelper;

    private function __construct() {}

    static function run() {
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }

    function init() {
        $applicationHelper = ApplicationHelper::instance();
        $applicationHelper->init();
    }

    function handleRequest() {
        $request = new Request();
        $app_c = \imei_service\base\ApplicationRegistry::appController();
        while( $cmd = $app_c->getCommand( $request ) ) {
            $cmd->execute( $request );
//            echo "<tt><pre>".print_r($cmd, true)."</pre></tt>";
        }
//        \imei_service\domain\ObjectWatcher::instance()->preformOperations();
        $this->invokeView( $app_c->getView( $request ) );
    }

    function invokeView( $target ) {
        print $target;
        include( "imei_service/view/$target.php" );
        exit;
    }
}
?>