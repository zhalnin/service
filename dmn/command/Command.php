<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 19:57
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
require_once( "dmn/view/utils/getNameServer.php" );
//require_once( 'dmn/view/utils/security_mod.php' );
abstract class Command {
    private static $STATUS_STRINGS = array(
        'CMD_DEFAULT'           => 0,
        'CMD_OK'                => 1,
        'CMD_ERROR'             => 2,
        'CMD_INSUFFICIENT_DATA' => 3,
        'CMD_UNLOCK_OK'         => 4,
        'CMD_UDID_OK'           => 5,
        'CMD_CARRIER_OK'        => 6,
        'CMD_BLACKLIST_OK'      => 7,
        'CMD_GUESTBOOK_OK'      => 8,
        'CMD_ADD'               => 9,
        'CMD_EDIT'              => 10,
        'CMD_DELETE'            => 11,
        'CMD_DETAIL'            => 12,
        'CMD_BLOCK'             => 13,
        'CMD_UNBLOCK'           => 14
    );
    private $status = 0;

    final function __construct() {}

    /**
     * Вызываем метод дочернего класса
     * и сохраняем текущую команду в классе Request
     * @param \dmn\controller\Request $request
     */
    function execute( \dmn\controller\Request $request ) {
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
     * Для перезагрузки страницы по таймеру(refresh)
     * @param $delay - задержка
     * @param $resource - путь
     */
    function reloadPage( $delay, $resource ) {
        $server = \dmn\view\utils\getNameServer().$resource;
        $delay = intval( $delay );
        header("Refresh: {$delay}; URL= {$server}");
    }


    /**
     * Реализуется в дочерних классах
     * @param \dmn\controller\Request $request
     * @return mixed
     */
    abstract function doExecute( \dmn\controller\Request $request );
}
?>