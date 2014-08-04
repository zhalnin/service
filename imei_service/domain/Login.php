<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/06/14
 * Time: 20:27
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/domain/DomainObject.php" );
//require_once( "imei_service/mapper/LoginIdentityObject.php" );


class Login extends DomainObject {
    private $fio;
    private $city;
    private $email;
    private $url;
    private $login;
    private $pass;
    private $activation;
    private $status;
    private $putdate;
    private $lastvisit;
    private $block;
    private $online;
    private $rights;

    function __construct(   $id=null,
                            $fio=null,
                            $city=null,
                            $email=null,
                            $url=null,
                            $login=null,
                            $pass=null,
                            $activation=null,
                            $status=0,
                            $putdate=null,
                            $lastvisit=null,
                            $block='unblock',
                            $online=0,
                            $rights='user' ) {

        $this->fio          = $fio;
        $this->city         = $city;
        $this->email        = $email;
        $this->url          = $url;
        $this->login        = $login;
        $this->pass         = $pass;
        $this->activation   = $activation;
        $this->status       = $status;
        $this->putdate      = $putdate;
        $this->lastvisit    = $lastvisit;
        $this->block        = $block;
        $this->online       = $online;
        $this->rights       = $rights;

        parent::__construct( $id );

    }

    /**
     * Метод, проверяющий на соответствие
     * логина и пароля, если это массив
     * или по id в БД
     * @param $args
     * @return mixed
     */
    static function find( $args ) {
        $finder = self::getFinder( __CLASS__ );
        if( is_array( $args ) ) {
            list( $login, $pass ) = $args;
            $logpassobj = self::getIdentityObject( __CLASS__ );
            return $finder->findOne( $logpassobj->field( 'login' )->eq( $login )->field( 'pass' )->eq( $pass ) );
        } else {
            $logobj = self::getIdentityObject( __CLASS__ );
            return $finder->findOne( $logobj->field( 'id' )->eq( $args ) );
        }
    }

    /**
     * Метод для проверки email в БД
     * на существование
     * @param $email
     * @return mixed
     */
    static function findEmail( $email ) {
        $finder = self::getFinder( __CLASS__ );
        $logpassobj = self::getIdentityObject( __CLASS__ );
        return $finder->findOne( $logpassobj->field( 'email' )->eq( $email ) );
    }


    /**
     * Метод для проверки логина в БД
     * на существование
     * @param $login
     * @return mixed
     */
    static function findLogin( $login ) {
        $finder = self::getFinder( __CLASS__ );
        $logpassobj = self::getIdentityObject( __CLASS__ );
        return $finder->findOne( $logpassobj->field( 'login' )->eq( $login ) );
    }

    function setFio( $fio_s ) {
        $this->fio = $fio_s;
        $this->markDirty();
    }
    function setCity( $city_s ) {
        $this->city = $city_s;
        $this->markDirty();
    }
    function setEmail( $email_s ) {
        $this->email = $email_s;
        $this->markDirty();
    }
    function setUrl( $url_s ) {
        $this->url = $url_s;
        $this->markDirty();
    }
    function setLogin( $login_s ) {
        $this->login = $login_s;
        $this->markDirty();
    }
    function setPass( $pass_s ) {
        $this->pass = $pass_s;
        $this->markDirty();
    }
    function setActivation( $activation_s ) {
        $this->activation = $activation_s;
        $this->markDirty();
    }
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }
    function setLastvisit( $lastvisit_s ) {
        $this->lastvisit = $lastvisit_s;
        $this->markDirty();
    }
    function setBlock( $block_s ) {
        $this->block = $block_s;
        $this->markDirty();
    }
    /**
     * устанавливаем флаг онлайн
     * @param $online_s
     */
    function setOnline( $online_s ) {
        $this->online = $online_s;
        $this->markDirty();
    }

    /**
     * устанавливаем статус юзера
     * @param $rights_s
     */
    function setRights( $rights_s ) {
        $this->rights = $rights_s;
        $this->markDirty();
    }


    function getFio() {
        return $this->fio;
    }
    function getCity() {
        return $this->city;
    }
    function getEmail() {
        return $this->email;
    }
    function getUrl() {
        return $this->url;
    }
    function getLogin() {
        return $this->login;
    }
    function getPass() {
        return $this->pass;
    }
    function getActivation() {
        return $this->activation;
    }
    function getStatus() {
        return $this->status;
    }
    function getPutdate() {
        return $this->putdate;
    }
    function getLastvisit() {
        return $this->lastvisit;
    }
    function getBlock() {
        return $this->block;
    }
    /**
     * получаем флаг онлайн
     * @return null|string
     */
    function getOnline() {
        return $this->online;
    }

    /**
     * получаем статус юзера
     * @return mixed
     */
    function getRights() {
        return $this->rights;
    }
}
?>