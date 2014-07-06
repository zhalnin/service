<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28/05/14
 * Time: 15:35
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;

require_once( "imei_service/domain/DomainObject.php" );

class Contacts extends DomainObject {
    private $name;
    private $phone;
    private $fax;
    private $email;
    private $skype;
    private $vk;
    private $address;
    private $photo;
    private $photo_small;
    private $alt;

    /**
     *  Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $phone
     * @param null $fax
     * @param null $email
     * @param null $skype
     * @param null $vk
     * @param null $address
     * @param null $photo
     * @param null $photo_small
     * @param null $alt
     */
    function __construct(   $id=null,
                            $name=null,
                            $phone=null,
                            $fax=null,
                            $email=null,
                            $skype=null,
                            $vk=null,
                            $address=null,
                            $photo=null,
                            $photoSmall=null,
                            $alt=null ) {

        $this->name         = $name;
        $this->phone        = $phone;
        $this->fax          = $fax;
        $this->email        = $email;
        $this->skype        = $skype;
        $this->vk           = $vk;
        $this->address      = $address;
        $this->photo        = $photo;
        $this->photoSmall   = $photoSmall;
        $this->alt          = $alt;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    static function findAll() {
        $finder = self::getFinder( __CLASS__ );
//        $contacts_idobj = new \imei_service\mapper\ContactsIdentityObject();
        $idobj = self::getIdentityObject( __CLASS__ );
        return $finder->find( $idobj );
    }

    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }

    function setPhone( $phone_s ) {
        $this->phone = $phone_s;
        $this->markDirty();
    }

    function setFax( $fax_s ) {
        $this->fax = $fax_s;
        $this->markDirty();
    }

    function setEmail( $email_s ) {
        $this->email = $email_s;
        $this->markDirty();
    }

    function setSkype( $skype_s ) {
        $this->skype = $skype_s;
        $this->markDirty();
    }

    function setVk( $vk_s ) {
        $this->vk = $vk_s;
        $this->markDirty();
    }

    function setAddress( $address_s ) {
        $this->address = $address_s;
        $this->markDirty();
    }

    function setPhoto( $photo_s ) {
        $this->photo = $photo_s;
        $this->markDirty();
    }

    function setPhotoSmall( $photoSmall_s ) {
        $this->photoSmall = $photoSmall_s;
        $this->markDirty();
    }

    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }


    function getName() {
        return $this->name;
    }

    function getPhone() {
        return $this->phone;
    }

    function getFax() {
        return $this->fax;
    }

    function getEmail() {
        return $this->email;
    }

    function getSkype() {
        return $this->skype;
    }

    function getVk() {
        return $this->vk;
    }

    function getAddress() {
        return $this->address;
    }

    function getPhoto() {
        return $this->photo;
    }

    function getPhotoSmall() {
        return $this->photoSmall;
    }

    function getAlt() {
        return $this->alt;
    }
}