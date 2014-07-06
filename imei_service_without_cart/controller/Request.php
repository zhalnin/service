<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 23:34
 */

namespace imei_service\controller;


/**
 * Class Request
 * @package imei_service\controller
 * Получает строку запроса, кэширует ее в классе Registry
 */
class Request {
    private $appreg;
    private $properties; // хранит глобальный массив $_REQUEST
    private $objects = array();
    private $feedback = array(); // хранит сообщение дочернего класса из класса Command
    private $lastCommand; // флаг, выставляемый в классе Command и проверяется в классе AppController->getCommand()
    private $tmp = array(); // промежуточный массив для хранения строки запроса, разбитой по символу "&"

    /**
     * Конструктор для запуска метода парсинга строки запроса
     * и кэширования его
     */
    function __construct() {
        $this->init();  // запускаем метод, парсируем строку запроса в массив
        \imei_service\base\RequestRegistry::setRequest( $this );    // кэшируем ее в классе Registry
    }

    /**
     * Основной метод для парсирования строки запроса и сохранения в массив $properties
     */
    function init() {
        if( isset( $_SERVER['REQUEST_METHOD'] ) ) {
            if( $_SERVER['REQUEST_METHOD'] ) {
                $this->properties = $_REQUEST;
                return;
            }
        }
        foreach ( $_SERVER['argv'] as $args ) { // проходим по аргументам строки запроса
            if( strpos( $args, '=' ) ) {    // если есть =, то значит присутствует нужная нам строка запроса
                if( strpos( $args, '&' ) ) {    // если есть &, значит параметров больше одного
                    $tmp = explode( '&', $args );   // тогда разбиваем строку на пары ключ=значение
                    foreach( $tmp as $arg ) {   // проходим в цикле
                        if( strpos( $arg, '=' ) ) { // находим разделитель пары
                            list( $key, $val ) = explode( "=", $arg );  // разбиваем по знаку = на ключ и значие
                            $this->setProperty( $key, $val );   // для сохранения в массиве
                        }
                    }
                } elseif( strpos( $args, '=' ) ) { // если нет &, то параметр всего один
                    list( $key, $val ) = explode( "=", $args ); // разбиваем его по знаку = на ключ и значение
                    $this->setProperty( $key, $val );   // для сохранения в массиве
                }
            }
        }
    }

    /**
     * Метод для возвращения строки запроса по ключу
     * @param $key
     * @return mixed
     */
    function getProperty( $key ) {
        if( isset( $this->properties[$key] ) ) {
            return $this->properties[$key];
        }
    }

    /**
     * Метод для сохранения пар ключ и значение в массиве
     * @param $key
     * @param $val
     */
    function setProperty( $key, $val ) {
        $this->properties[$key] = $val;
    }

    function __clone() {
        $this->properties = array();
    }

    /**
     * Метод для добавления сообщения к массиву
     * @param $msg
     */
    function addFeedback( $msg ) {
        array_push( $this->feedback, $msg );
    }

    /**
     * Метод для возвращения сообщения, ранее сохраненного
     * @return array
     */
    function getFeedback() {
        return $this->feedback;
    }

    /**
     * Метод для возвращения сообщения, ранее сохраненного
     * с учетом контекста страницы - разделителей
     * @param string $separator
     * @return string
     */
    function getFeedbackString( $separator="\n" ) {
        return implode( $separator, $this->feedback );
    }

    function setObject( $name, $object ) {
        return $this->objects[$name] = $object;
    }

    function getObject( $name ) {
        if( isset( $this->objects[$name] ) ) {
            return $this->objects[$name];
        }
        return null;
    }

    /**
     * Метод для сохранения текущей команды - для предотвращения
     * ее повторного выполнения
     * @param \imei_service\command\Command $command
     */
    function setCommand( \imei_service\command\Command $command ) {
        $this->lastCommand = $command;
    }

    /**
     * Метод для возвращения предыдущей команды - для проверки случая,
     * когда она была выполнена на предыдущем шаге
     * @return mixed
     */
    function getLastCommand() {
        return $this->lastCommand;
    }
}
?>