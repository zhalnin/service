<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 29/12/13
 * Time: 21:05
 * To change this template use File | Settings | File Templates.
 */
namespace woo\command;

abstract class Command {
    private static $STATUS_STRINGS = array(
        'CMD_DEFAULT'           => 0,
        'CMD_OK'                => 1,
        'CMD_ERROR'             => 2,
        'CMD_INSUFFICIENT_DATA' => 3
    );

    private $status = 0;

    final function __construct(){}

    /**
     * Execute in Controller
     * @param \woo\controller\Request $request
     */
    function execute( \woo\controller\Request $request ) {
        // Возвращаем статус выполнения запроса
        $this->status = $this->doExecute( $request );
        // добавляем в текущую команду(cmd=AddVenue) в переменную lastCommand
        $request->setCommand( $this );
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
    }

    /**
     * Take $status from external scripts
     * @return int
     */
    function getStatus() {
        return $this->status;
    }

    /**
     * Return integer $STATUS_STRINGS value
     * @param string $str
     * @return mixed
     */
    static function statuses( $str='CMD_DEFAULT' ) {
        if( empty( $str ) ) { $str= 'CMD_DEFAULT'; }
        return self::$STATUS_STRINGS[$str];
    }

    /**
     * Abstract function executes in child classes
     * to add parameters to Request and so on
     * @param \woo\controller\Request $request
     * @return mixed
     */
    abstract function doExecute( \woo\controller\Request $request );
}

?>