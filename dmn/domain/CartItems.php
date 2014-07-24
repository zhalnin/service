<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 12:04
 */

namespace dmn\domain;
error_reporting( E_ALL & ~E_NOTICE );

// подключаем родительский класс
require_once( "dmn/domain/DomainObject.php" );

/**
 * Class CartItems
 * Принимаем поля таблицы system_cart_items
 * в родительский класс передаем id
 * и создаем вспомогательные классы для доступа к частным переменным
 *
 * @package dmn\domain
 */
class CartItems extends DomainObject {

    private $product_id;    // id заказываемого продукта
    private $order_id;      // id из таблицы system_cart_orders
    private $title;         // название продукта
    private $price;         // стоимость продукта
    private $qty;           // количество наименований


    function __construct(   $id         =null,
                            $product_id =null,
                            $order_id   =null,
                            $title      =null,
                            $price      =null,
                            $qty        =null ) {

        $this->product_id   = $product_id;
        $this->order_id     = $order_id;
        $this->title        = $title;
        $this->price        = $price;
        $this->qty          = $qty;

        parent::__construct( $id );
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // NewsIdentityObject
        $newsIdobj = new \dmn\mapper\CartItemsIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($newsIdobj, true)."</pre></tt>";
        return $finder->find( $newsIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $id
     * @return mixed
     */
    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CartItemsIdentityObject( 'id' );
        return $finder->findOne( $idobj->eq( $id ) );
    }

    /**
     * Метод для поиска по номеру заказа
     * @param $id
     * @return mixed
     */
    static function findByOrderId( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CartItemsIdentityObject( 'order_id' );
        return $finder->findOne( $idobj->eq( $id ) );
    }


    /**
     * Добавляем id заказываемого продукта и отмечаем как данные для обновления/вставки
     * @param $firstname_s - имя
     */
    function setProductId( $product_id_s ) {
        $this->product_id = $product_id_s;
        $this->markDirty();
    }

    /**
     * Добавляем id заказа и отмечаем как данные для обновления/вставки
     * @param $lastname_s
     */
    function setOrderId( $order_id_s ) {
        $this->order_id = $order_id_s;
        $this->markDirty();
    }

    /**
     * Добавляем название  и отмечаем как данные для обновления/вставки
     * @param $email_s
     */
    function setTitle( $title_s ) {
        $this->title = $title_s;
        $this->markDirty();
    }

    /**
     * Добавляем стоимость и отмечаем как данные для обновления/вставки
     * @param $data_s
     */
    function setPrice( $price_s ) {
        $this->price = $price_s;
        $this->markDirty();
    }

    /**
     * Добавляем количество и отмечаем как данные для обновления/вставки
     * @param $country_s
     */
    function setQty( $qty_s ) {
        $this->qty = $qty_s;
        $this->markDirty();
    }


    /**
     * Получаем id заказываемого продукта и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getProductId() {
        return $this->product_id;
    }

    /**
     * Получаем id заказа и отмечаем как данные для обновления/вставки
     * @return null
     */
    function getOrderId() {
        return $this->order_id;
    }

    /**
     * Получаем название и отмечаем как данные для обновления/вставки
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