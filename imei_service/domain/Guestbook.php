<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/05/14
 * Time: 18:13
 */

namespace imei_service\domain;

require_once( "imei_service/domain/DomainObject.php" );

class Guestbook extends DomainObject {

    private $name;
    private $city;
    private $email;
    private $url;
    private $message;
    private $answer;
    private $putdate;
    private $hide;
    private $id_parent;
    private $ip;
    private $browser;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $city
     * @param null $email
     * @param null $url
     * @param null $message
     * @param null $answer
     * @param null $putdate
     * @param string $hide
     * @param null $id_parent
     * @param null $ip
     * @param null $browser
     */
    function __construct(   $id=null,
                            $name=null,
                            $city=null,
                            $email=null,
                            $url=null,
                            $message=null,
                            $answer=null,
                            $putdate=null,
                            $hide='show',
                            $id_parent=null,
                            $ip=null,
                            $browser=null ) {

        $this->name         = $name;
        $this->city         = $city;
        $this->email        = $email;
        $this->url          = $url;
        $this->message      = $message;
        $this->answer       = $answer;
        $this->putdate      = $putdate;
        $this->hide         = $hide;
        $this->id_parent    = $id_parent;
        $this->ip           = $ip;
        $this->browser      = $browser;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     * @return mixed
     * - tableName
     * - where
     * - order
     * - pageNumber
     * - pageLink
     * - parameters
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $guest_book = new \imei_service\mapper\GuestbookIdentityObject( 'hide' ); // GuestbookIdentityObject
        $guest_book->eq( 'show' )->field('id_parent')->eq( 0 ); // условный оператор
        return $finder->find( $guest_book ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    static function paginationMysql( $page ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\GuestbookIdentityObject( 'hide' );
        $idobj->eq( 'show' )->field( 'id_parent' )->eq( 0 );



        return $finder->findPagination( "system_guestbook",
                                        $idobj,
                                        " ORDER BY putdate DESC ",
                                        10,
                                        3,
                                        "",
                                        $page);
    }






//    static function paginationChildMysql( $id_parent ) {
//        $finder = self::getFinder( __CLASS__ );
//        $idobj = new \imei_service\mapper\GuestbookIdentityObject( 'hide' );
//        $idobj->eq( 'show' )->field( 'id_parent' )->eq( $id_parent );
//
//
//
//        return $finder->findPagination( "system_guestbook",
//            $idobj,
//            " ORDER BY putdate DESC ",
//            "",
//            "",
//            "",
//            "");
//    }







    function setName( $name_s ) {
        $this->name = $name_s;
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
    function setMessage( $message_s ) {
        $this->message = $message_s;
        $this->markDirty();
    }
    function setAnswer( $answer_s ) {
        $this->answer = $answer_s;
        $this->markDirty();
    }
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
        $this->markDirty();
    }
    function setIdparent( $id_parent_s ) {
        $this->id_parent = $id_parent_s;
        $this->markDirty();
    }
    function setIp( $ip_s ) {
        $this->ip = $ip_s;
        $this->markDirty();
    }
    function setBrowser( $browser_s ) {
        $this->browser = $browser_s;
        $this->markDirty();
    }

    function getName() {
        return $this->name;
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
    function getMessage() {
        return $this->message;
    }
    function getAnswer() {
        return $this->answer;
    }
    function getPutdate() {
        return $this->putdate;
    }
    function getHide() {
        return $this->hide;
    }
    function getIdparent() {
        $this->id_parent;
    }
    function getIp() {
        $this->ip;
    }
    function getBrowser() {
        $this->browser;
    }
}
?>