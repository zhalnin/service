<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/07/14
 * Time: 21:23
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем родительский класс
require_once( "imei_service/domain/DomainObject.php" );

/**
 * Class CartOrder
 * Принимаем поля таблицы system_cart_orders
 * в родительский класс передаем id
 * и создаем вспомогательные классы для доступа к частным переменным
 *
 * @package imei_service\domain
 */
class CartOrder extends DomainObject {

    private $firstname;     // имя
    private $lastname;      // фамилия
    private $email;         // электронная почта
    private $country;       // страна
    private $address;       // адрес
    private $city;          // город
    private $zipCode;       // индекс
    private $state;         // штат
    private $status;        // статус операции
    private $amount;        // общая сумма
    private $paypalTransId; // ID транзакции PayPal
    private $createdAt;     // дата и время создания заказа
    private $data ;         // данные из текстового поля для описания заказа


    function __construct(   $id                 =null,
                            $firstname          =null,
                            $lastname           =null,
                            $email              =null,
                            $data               =null,
                            $country            =null,
                            $address            =null,
                            $city               =null,
                            $zipCode            =null,
                            $state              =null,
                            $status             ='Waiting',
                            $amount             =null,
                            $paypalTransId      =null,
                            $createdAt          =null) {

        $this->firstname        = $firstname;
        $this->lastname         = $lastname;
        $this->email            = $email;
        $this->data             = $data;
        $this->country          = $country;
        $this->address          = $address;
        $this->city             = $city;
        $this->zipCode          = $zipCode;
        $this->state            = $state;
        $this->status           = $status;
        $this->amount           = $amount;
        $this->paypalTransId    = $paypalTransId;
        $this->createdAt        = $createdAt;

        parent::__construct( $id );
    }


    /**
     * Добавляем имя и отмечаем как данные для обновления/вставки
     * @param $firstname_s - имя
     */
    function setFirstName( $firstname_s ) {
        $this->firstname = $firstname_s;
        $this->markDirty();
    }

    /**
     * Добавляем фамилию и отмечаем как данные для обновления/вставки
     * @param $lastname_s
     */
    function setLastName( $lastname_s ) {
        $this->lastname = $lastname_s;
        $this->markDirty();
    }

    /**
     * Добавляем email  и отмечаем как данные для обновления/вставки
     * @param $email_s
     */
    function setEmail( $email_s ) {
        $this->email = $email_s;
        $this->markDirty();
    }

    /**
     * Добавляем страну и отмечаем как данные для обновления/вставки
     * @param $country_s
     */
    function setCountry( $country_s ) {
        $this->country = $country_s;
        $this->markDirty();
    }

    /**
     * Добавляем адрес и отмечаем как данные для обновления/вставки
     * @param $address_s
     */
    function setAddress( $address_s ) {
        $this->address = $address_s;
        $this->markDirty();
    }

    /**
     * Добавляем город и отмечаем как данные для обновления/вставки
     * @param $city_s
     */
    function setCity( $city_s ) {
        $this->city = $city_s;
        $this->markDirty();
    }

    /**
     * Добавялем индекс и отмечаем как данные для обновления/вставки
     * @param $zipCode_s
     */
    function setZipCode( $zipCode_s ) {
        $this->zipCode = $zipCode_s;
        $this->markDirty();
    }

    /**
     * Добавляем штат и отмечаем как данные для обновления/вставки
     * @param $state_s
     */
    function setState( $state_s ) {
        $this->state = $state_s;
        $this->markDirty();
    }

    /**
     * Добавляем статус операции и отмечаем как данные для обновления/вставки
     * @param $status_s
     */
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }

    /**
     * Добавляем общую сумму и отмечаем как данные для обновления/вставки
     * @param $amount_s
     */
    function setAmount( $amount_s ) {
        $this->amount = $amount_s;
        $this->markDirty();
    }

    /**
     * Добавляем ID транзакции PayPal и отмечаем как данные для обновления/вставки
     * @param $paypalTransId_s
     */
    function setPayPalTransId( $paypalTransId_s ) {
        $this->paypalTransId = $paypalTransId_s;
        $this->markDirty();
    }

    /**
     * Добавляем дату и время приема заявки и отмечаем как данные для обновления/вставки
     * @param $createdAt_s
     */
    function setCreatedAt( $createdAt_s ) {
        $this->createdAt = $createdAt_s;
        $this->markDirty();
    }

    /**
     * Добавляем текст из тектового поля с уточнением заказа и отмечаем как данные для обновления/вставки
     * @param $data_s
     */
    function setData( $data_s ) {
        $this->data = $data_s;
        $this->markDirty();
    }

    /**
     * Получаем имя и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getFirstName() {
        return $this->firstname;
    }

    /**
     * Получаем фамилию и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getLastName() {
        return $this->lastname;
    }

    /**
     * Получаем email и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getEmail() {
        return $this->email;
    }

    /**
     * Получаем название страны и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getCountry() {
        return $this->country;
    }

    /**
     * Получаем адрес и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getAddress() {
        return $this->address;
    }

    /**
     * Получаем город и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getCity() {
        return $this->city;
    }

    /**
     * Получаем индекс и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getZipCode() {
        return $this->zipCode;
    }

    /**
     * Получаем штат и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getState() {
        return $this->state;
    }

    /**
     * Получаем статус операции и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getStatus() {
        return $this->status;
    }

    /**
     * Получаем общую сумму и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getAmount() {
        return $this->amount;
    }

    /**
     * Получаем ID транзакции PayPal и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getPaypalTransId() {
        return $this->paypalTransId;
    }

    /**
     * Получаем дату и время создания заказа и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Получаем данные из текстового поля для описания заказа
     *  и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getData() {
        return $this->data;
    }

}
?>