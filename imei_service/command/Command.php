<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 22:39
 */

namespace imei_service\command;

abstract class Command {
    private static $STATUS_STRINGS = array(
        'CMD_DEFAULT'           => 0,
        'CMD_OK'                => 1,
        'CMD_ERROR'             => 2,
        'CMD_INSUFFICIENT_DATA' => 3
    );
    private $status = 0;

    final function __construct() {}

    /**
     * Вызываем метод дочернего класса
     * и сохраняем текущую команду в классе Request
     * @param \imei_service\controller\Request $request
     */
    function execute( \imei_service\controller\Request $request ) {
        $this->status = $this->doExecute( $request ); // получаем результат выполнения метода
        $request->setCommand( $this ); // сохраняем команду в объект Request
    }

    /**
     * Получаем статус выполненной команды
     * @return int
     */
    function getStatus() {
        return $this->status;
    }

    /**
     * Возвращаем цифровой аналог статуса выполнения команды
     * @param string $str
     * @return mixed
     */
    static function statuses( $str='CMD_DEFAULT' ) {
        if( empty( $str ) ) { $str = 'CMD_DEFAULT'; }
        return self::$STATUS_STRINGS[$str];
    }

    /**
     * Реализуется в дочерних классах
     * @param \imei_service\controller\Request $request
     * @return mixed
     */
    abstract function doExecute( \imei_service\controller\Request $request );
}
?>