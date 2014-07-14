<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 13/07/14
 * Time: 21:37
 */

namespace imei_service\domain;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем родительский класс
require_once( "imei_service/domain/DomainObject.php" );

/**
 * Class CartItems
 * Принимаем поля таблицы system_cart_items
 * в родительский класс передаем id
 * и создаем вспомогательные классы для доступа к частным переменным
 * @package imei_service\domain
 */
class CartItems extends DomainObject {

    private $productId; // номер заказа (для поиска его по конкретным позициям)
    private $orderId;   // ID заказа из таблицы system_cart_orders
    private $title;     // наименование единицы
    private $price;     // стоимость единицы
    private $qty;       // количество наименования конкретной единицы



    function __construct(   $id         =null,
                            $productId  =null,
                            $orderId    =null,
                            $title      =null,
                            $price      =null,
                            $qty        =null ) {

        $this->productId    = $productId;
        $this->orderId      = $orderId;
        $this->title        = $title;
        $this->price        = $price;
        $this->qty          = $qty;


        parent::__construct( $id );
    }

    /**
     * Добавляем номера заказа  и отмечаем как данные для обновления/вставки
     * @param $productId_s
     */
    function setProductId( $productId_s ) {
        $this->productId = $productId_s;
        $this->markDirty();
    }

    /**
     * Добавляем ID заказа из таблицы system_cart_orders и отмечаем как данные для обновления/вставки
     * @param $orderId_s
     */
    function setOrderId( $orderId_s ) {
        $this->orderId = $orderId_s;
        $this->markDirty();
    }

    /**
     * Добавляем наименование и отмечаем как данные для обновления/вставки
     * @param $title_s
     */
    function setTitle( $title_s ) {
        $this->title = $title_s;
        $this->markDirty();
    }

    /**
     * Добавляем стоимость и отмечаем как данные для обновления/вставки
     * @param $price_s
     */
    function setAmout( $price_s ) {
        $this->price = $price_s;
        $this->markDirty();
    }

    /**
     * Добавляем количество и отмечаем как данные для обновления/вставки
     * @param $qty_s
     */
    function setQty( $qty_s ) {
        $this->qty = $qty_s;
        $this->markDirty();
    }


    /**
     * Получаем номера заказа и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getProductId() {
        return $this->productId;
    }

    /**
     * Получаем ID из таблицы system_cart_orders и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getOrderId() {
        return $this->orderId;
    }

    /**
     * Получаем наименование и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getTitle() {
        return $this->title;
    }

    /**
     * Получаем стоимость и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getPrice() {
        return $this->price;
    }

    /**
     * Получаем количество и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getQty() {
        return $this->qty;
    }
}
?>