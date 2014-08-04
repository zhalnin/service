<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 15:41
 */

namespace dmn\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
//require_once( "dmn/mapper/ArtArtIdentityObject.php" );
//require_once( "dmn/mapper/ArtArtUpDownFactory.php" );
//require_once( "dmn/domain/ArtArtPosition.php" );
require_once( "dmn/base/Registry.php" );

class Users extends DomainObject {

    private $fio;
    private $city;
    private $email;
    private $url;
    private $login;
    private $activation;
    private $status;
    private $pass;
    private $putdate;
    private $lastvisit;
    private $block;
    private $online;
    private $rights;


    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $fio
     * @param null $city
     * @param null $email
     * @param null $url
     * @param null $login
     * @param null $activation
     * @param int $status
     * @param null $pass
     * @param null $putdate
     * @param null $lastvisit
     * @param string $block
     * @param int $online
     * @param string $rights
     */
    function __construct( $id=null,
                          $fio=null,
                          $city=null,
                          $email=null,
                          $url=null,
                          $login=null,
                          $activation=null,
                          $status=0,
                          $pass=null,
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
        $this->activation   = $activation;
        $this->status       = $status;
        $this->pass         = $pass;
        $this->putdate      = $putdate;
        $this->lastvisit    = $lastvisit;
        $this->block        = $block;
        $this->online       = $online;
        $this->rights       = $rights;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // catalogIdentityObject
        $usersIdobj = new \dmn\mapper\UsersIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($catalogIdobj, true)."</pre></tt>";
        return $finder->find( $usersIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $id
     * @return mixed
     */
    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\UsersIdentityObject( 'id' );
        return $finder->findOne( $idobj->eq( $id ) );
    }

    /**
     * Метод для получения количество позиций по id аккаунта
     * @param $name - имя аккаунта
     * @return mixed
     */
    static function findCountPos( $name=null ) {
        $finder = self::getFinder( __CLASS__ );
        if( ! is_null( $name ) ) {
            switch( $name ) {
                case 'login':
                    $idobj = new \dmn\mapper\UsersIdentityObject( 'login' );
                    return $finder->findCountPos( $idobj->eq( $name ) );
                    break;
                case 'email':
                    $idobj = new \dmn\mapper\UsersIdentityObject( 'email' );
                    return $finder->findCountPos( $idobj->eq( $name ) );
                    break;
            }
        } else {
            $idobj = new \dmn\mapper\UsersIdentityObject();
            return $finder->findCountPos( $idobj );
        }
    }


    /**
     * Метод для смены,
     * сокрытия или отображения позиции в блоке
     * @param $id
     * @param $action
     */
    static function position( $id, $action ) {
        $result = array();
        $finder = self::getFinder( __CLASS__ );
        $curobj = self::getIdentityObject( __CLASS__ );
        $prevobj = self::getIdentityObject( __CLASS__ );
        switch( $action ) {
            case 'block': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\UsersIdentityObject( 'id' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'block' )->eq( 'unblock' ));
//                echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setBlock( $action );
                break;
            case 'unblock': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\UsersIdentityObject( 'id' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'block' )->eq( 'block' ));
//                echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setBlock( $action );
                break;
            case 'activate': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\UsersIdentityObject( 'id' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'status' )->eq( 0 ));
//                echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setStatus( 1 );
                break;
            case 'deactivate': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\UsersIdentityObject( 'id' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'status' )->eq( 1 ));
//                echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setStatus( 0 );
                break;
        }
    }


    /**
     * устанавливаем имя
     * @param $fio_s
     */
    function setFio( $fio_s ) {
        $this->fio = $fio_s;
        $this->markDirty();
    }

    /**
     * устанавливаем город
     * @param $city_s
     */
    function setCity( $city_s ) {
        $this->city = $city_s;
        $this->markDirty();
    }

    /**
     * устанавливаем email
     * @param $email_s
     */
    function setEmail( $email_s ) {
        $this->email = $email_s;
        $this->markDirty();
    }

    /**
     * устанавливаем сайт
     * @param $url_s
     */
    function setUrl( $url_s ) {
        $this->url = $url_s;
        $this->markDirty();
    }

    /**
     * устанавливаем логин
     * @param $login_s
     */
    function setLogin( $login_s ) {
        $this->login = $login_s;
        $this->markDirty();
    }

    /**
     * устанавливаем код активации
     * @param $activation_s
     */
    function setActivation( $activation_s ) {
        $this->activation = $activation_s;
        $this->markDirty();
    }

    /**
     * устанавливаем статус
     * @param $status_s
     */
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }
    /**
     * устанавливаем пароль
     * @param $pass_s
     */
    function setPass( $pass_s ) {
        $this->pass = $pass_s;
        $this->markDirty();
    }

    /**
     * устанавливаем дату регистрации
     * @param $putdate_s
     */
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }

    /**
     * устанавливаем дату последнего визита
     * @param $lastvisit_s
     */
    function setLastvisit( $lastvisit_s ) {
        $this->lastvisit = $lastvisit_s;
        $this->markDirty();
    }

    /**
     * устанавливаем флаг блокировки
     * @param $block_s
     */
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


    /**
     * получаем имя
     * @return null
     */
    function getFio() {
        return $this->fio;
    }

    /**
     * получаем город
     * @return null
     */
    function getCity() {
        return $this->city;
    }

    /**
     * получаем email
     * @return null
     */
    function getEmail() {
        return $this->email;
    }

    /**
     * получаем сайт
     * @return null
     */
    function getUrl() {
        return $this->url;
    }

    /**
     * получаем логин
     * @return null
     */
    function getLogin() {
        return $this->login;
    }

    /**
     * получаем код активации
     * @return null
     */
    function getActivation() {
        return $this->activation;
    }

    /**
     * получаем статус
     * @return null
     */
    function getStatus() {
        return $this->status;
    }
    /**
     * получаем пароль
     * @return null
     */
    function getPass() {
        return $this->pass;
    }

    /**
     * получаем дату регистрации
     * @return null
     */
    function getPutdate() {
        return $this->putdate;
    }

    /**
     * получаем дату последнего визита
     * @return null
     */
    function getLastvisit() {
        return $this->lastvisit;
    }

    /**
     * получаем флаг блокировки
     * @return null
     */
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