<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/06/14
 * Time: 22:42
 */

namespace imei_service\domain;
namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );
require_once( "imei_service/mapper/LoginOshibkaIdentityObject.php" );

class LoginOshibka extends DomainObject {
    private $ip;
    private $date;
    private $col;

    function __construct(   $id=null,
                            $ip=null,
                            $date=null,
                            $col=null ) {

        $this->ip   = $id;
        $this->date = $date;
        $this->col  = $col;

        parent::__construct( $id );
    }

    /**
     * Метод для определения срока жизни неправильных попыток ввода,
     * если дольше 15 минут, то очищение таблицы
     * Возвращаем коллекцию по IP адресу
     * @param $ip
     * @return mixed
     */
    static function find( $ip ) {
        $finder = self::getFinder( __CLASS__ );
        $dateobj = new \imei_service\mapper\LoginOshibkaIdentityObject( 'date' );
        $finder->deleteEarly( $dateobj->gt( 900 ) ); // удаляем записи из таблицы, которые превышают 3 минуты "жизни"
        $ipobj = new \imei_service\mapper\LoginOshibkaIdentityObject( 'ip' );
        return $finder->findOne( $ipobj->eq( $ip ) );
//        echo "<tt><pre>".print_r( $finder, true ) ."</pre></tt>";
//        return $finder->findOne( $idobj->eq( $ip ) );
    }

    function setIp( $ip_s ) {
        $this->ip = $ip_s;
        $this->markDirty();
    }
    function setDate( $date_s ) {
        $this->date = $date_s;
        $this->markDirty();
    }
    function setCol( $col_s ) {
        $this->col = $col_s;
        $this->markDirty();
    }

    function getIp() {
        return $this->ip;
    }
    function getDate() {
        return $this->date;
    }
    function getCol() {
        return $this->col;
    }
}
?>