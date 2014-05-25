<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/12/13
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */

namespace woo\controller;

require_once( "woo/controller/ApplicationHelper.php" );
require_once( "woo/controller/Request.php" );
require_once( "woo/base/Registry.php" );
require_once( "woo/domain/ObjectWatcher.php" ); // using the real one now


class Controller {
    private $applicationHelper;

    /**
     * Constructor without descendants
     * and params passed in
     */
    private function __construct() {}

    /**
     * Самый первый метод, который выполняется при запуске приложения
     * First of all start IT from index.php
     * Вызывает методы:
     * init()
     * handleRequest()
     */
    static function run() {
        //  Create instance of this class - Controller
        $instance = new Controller();
        // Take data of Application ('dsn') and save 'cmap'
        $instance->init();
        // Handle request
        $instance->handleRequest();
    }

    /**
     * После этого метода мы имеем распарсированный файл .xml (getOptions())
     * Получили два файла "dsn" и "cmap"
     * dsn - содержит путь до базы данных
     * cmap - содержит сериализованные данные объекта ControllerMap (все view,allias,forward)
     * Вызывает метод класса ApplicataionHelper
     * init()
     */
    function init() {
        // Create instance of ApplicationHelper class
        $this->applicationHelper = ApplicationHelper::instance();
        // Invoke method init()
        $this->applicationHelper->init();
    }

    /**
     * To handle request
     */
    function handleRequest() {
        // Take object which have all params from query string
        $request = new Request();
//        echo "<tt><pre> handleRequest Request - ".print_r($request, true)."</pre></tt>";
        // in Registry.php take instance of AppController with values $cmap
        $app_c = \woo\base\ApplicationRegistry::appController();
//        echo "<tt><pre> handleRequest Request - ".print_r($app_c, true)."</pre></tt>";
        // find out command in Request
        // 1. $cmd = new woo\command\AddVenue
        // 2. $cmd = new woo\command\AddSpace
        while( $cmd = $app_c->getCommand( $request ) ) {
//            echo "<tt><pre> HANDLEREQUEST CMD - ".print_r($cmd, true)."</pre></tt>";
            // AddVenue.php class AddVenue execute (doExecute)
            $cmd->execute( $request );
//            echo "<tt><pre> HANDLEREQUEST CMD - ".print_r($cmd, true)."</pre></tt>";
////            echo "<tt><pre>".print_r($app_c, true)."</pre></tt>";
        }
        // invoke view with $cmap
//        echo "<tt><pre> HANDLEREQUEST APP_C - ".print_r($app_c, true)."</pre></tt>";\

        // Пытаемся вполнить действия для добавления/обновления базы данных
        \woo\domain\ObjectWatcher::instance()->performOperations();
        $this->invokeView( $app_c->getView( $request ) );
//        echo "<tt><pre>".print_r($app_c, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
//        echo "<tt><pre>".print_r(\woo\base\RequestRegistry::getRequest()->getProperty('cmd'),true)."</pre></tt>";
    }

    /**
     * Invoke target view
     * @param $target
     */
    function invokeView( $target ) {
//        echo $target;
        include( "woo/view/$target.php" );
        exit;
    }
}

?>