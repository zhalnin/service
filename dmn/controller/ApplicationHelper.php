<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 19:46
 */

namespace dmn\controller;


require_once( "dmn/base/Registry.php" );
require_once( "dmn/base/Exceptions.php" );
require_once( "dmn/controller/AppController.php" );
require_once( "dmn/command/Command.php" );

/**
 * Class ApplicationHelper
 * @package dmn\controller
 * Позволяем парсировать файл конфигурации приложения
 * сохраняет дескриптор базы данных и саму схему приложения(команды/вьюшки/переадресации/статусы)
 */
class ApplicationHelper {
    private static $instance;
    private $config = "dmn/data/dmn_options.xml"; // путь до файла конфигурации

    private function __construct(){}

    /**
     * Синглтон
     * @return ApplicationHelper
     */
    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Стартовый метод для кэширования
     */
    function init() {
        $dsn = \dmn\base\ApplicationRegistry::getDSN();    // пытаемся получить кэшированный дескриптор базы данных
        if( ! is_null( $dsn ) ) {
            return;
        }
        $this->getOptions();                                        // если его нет, то парсируем файл конфигурации
    }

    /**
     * Метод парсирования файла конфигурации приложения
     * и кэширования полученных данных в файлы через класс Registry
     */
    private function getOptions() {
        $this->ensure( file_exists( $this->config ), "Could not find options file 'xml' in ApplicationHelper.php" );
        $options = @SimpleXml_load_file( $this->config );   // получаем корневой элемент xml
        $this->ensure( $options instanceof \SimpleXMLElement, "Could not resolve options file in ApplicationHelper.php" );
        $dsn = (string)$options->dsn;   // получаем значение тега <dsn>
        $this->ensure( $dsn, "No DSN found in ApplicationHelper.php" );
        \dmn\base\ApplicationRegistry::setDSN( $dsn ); // кэшируем дескриптор базы данных
        $map = new ControllerMap(); // создаем экземпляр класса с вспомогательными методами

        foreach( $options->control->view as $default_view ) {   // находим вьюшки по умолчанию <view>
            $stat_str = trim( $default_view['status'] );        // у каждой получаем значение атрибута status <view status="CMD_OK">
            $status = \dmn\command\Command::statuses( $stat_str ); // метод statuses командного класса сохраняет их цифровой эквивалент
//            'CMD_DEFAULT'           => 0,
//            'CMD_OK'                => 1,
//            'CMD_ERROR'             => 2,
//            'CMD_INSUFFICIENT_DATA' => 3
            $map->addView( 'default', $status, (string)$default_view ); // кэшируем: команда, цифровой статус, название вьюшки
        }

        foreach ( $options->control->command as $command_view ) {   // находим все команды <command>
            $command = trim((string)$command_view['name'] );    // у каждой команды берем атрибут name - имя команды <command name="General">
            if( $command_view->classalias ) {   // смотрим наличие псевдонима в теле команды <classalias>
                $classroot = trim((string)$command_view->classalias['name'] );  // у псевдонима берем атрибут name - его имя <classalias name="AddVenue">
                $map->addClassroot( $command, $classroot ); // кэшируем команду и псевдоним
            }
            if( $command_view->view ) { // находим в теле команды вьюшки
                $view = trim((string)$command_view->view ); // получаем вьюшку <view>
                $forward = trim((string)$command_view->forward);    // получаем переадресацию <forward>
                $map->addView( $command, 0, $view );    // кэшируем вьюшку
                if( $forward ) {
                    $map->addForward( $command, 0, $forward );  // кэшируем переадресацию
                }
                foreach ( $command_view->status as $command_view_status ) { // находим в теле комманд статус <status>
                    $view = trim((string)$command_view_status->view );  // в теле стауса находим вьюшку <view>
                    $forward = trim((string)$command_view_status->forward );  // в теле стауса находим переадресацию <forward>
                    $stat_str = trim($command_view_status['value'] );   // получаем атрибут value у статуса <status value="CMD_OK">
                    $status = \dmn\command\Command::statuses( $stat_str ); // получаем цифровой эквивалент статуса
                    if( $view ) {
                        $map->addView( $command, $status, $view );  // кэшируем вьюшку
                    }
                    if( $forward ) {
                        $map->addForward( $command, $status, $forward );    // кэшируем переадресацию
                    }
                }
            }
        }
//        echo "<tt><pre>".print_r($map, true)."</pre></tt>";
        \dmn\base\ApplicationRegistry::setControllerMap( $map );   // кэшируем полученный класс в файл в Registry
    }

    /**
     * Вспомогательный метод - валидатор
     * @param $stmt
     * @param $msg
     * @throws \dmn\base\AppException
     */
    private function ensure( $stmt, $msg ) {
        if( ! $stmt ) {
            throw new \dmn\base\AppException( $msg );
        }
    }
}
?>