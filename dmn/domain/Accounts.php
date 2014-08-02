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

class Accounts extends DomainObject {
    private $name;
    private $pass;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $pass
     */
    function __construct( $id=null,
                          $name=null,
                          $pass=null ) {

        $this->name     = $name;
        $this->pass     = $pass;

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
        $catalogIdobj = new \dmn\mapper\AccountsIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($catalogIdobj, true)."</pre></tt>";
        return $finder->find( $catalogIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $id
     * @return mixed
     */
    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\AccountsIdentityObject( 'id_account' );
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
            $idobj = new \dmn\mapper\AccountsIdentityObject( 'name' );
            return $finder->findCountPos( $idobj->eq( $name ) );
        } else {
            $idobj = new \dmn\mapper\AccountsIdentityObject();
            return $finder->findCountPos( $idobj );
        }
    }

    /**
     * устанавливем имя
     * @param $name_s
     */
    function setName( $name_s ) {
        $this->name = $name_s;
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
     * получаем имя
     * @return null
     */
    function getName() {
        return $this->name;
    }
    /**
     * получаем пароль
     * @return null
     */
    function getPass() {
        return $this->pass;
    }
}
?>