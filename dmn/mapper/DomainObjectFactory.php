<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:12
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );


abstract class DomainObjectFactory {
    abstract function createObject( array $array );

    protected function getFromMap( $class, $id ) {
        return \dmn\domain\ObjectWatcher::exists( $class, $id );
    }

    protected function addToMap( \dmn\domain\DomainObject $obj ) {
        \dmn\domain\ObjectWatcher::add( $obj );
    }
}


/**
 * Class NewsObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class NewsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\News"; // название класса
        $old = $this->getFromMap( $class, $array['id_news'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_news'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setPreview( $array['preview'] );
        $obj->setBody( $array['body'] );
        $obj->setPutdate( $array['putdate'] );
        $obj->setHideDate( $array['hidedate'] );
        $obj->setUrl( $array['url'] );
        $obj->setUrltext( $array['urltext'] );
        $obj->setAlt( $array['alt'] );
        $obj->setUrlpict( $array['urlpict'] );
        $obj->setUrlpict_s( $array['urlpict_s'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setHidepict( $array['hidepict'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}


/**
 * Class CartOrderObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class CartOrderObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\CartOrder"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setFirstName( $array['firstname'] );
        $obj->setLastName( $array['lastname'] );
        $obj->setEmail( $array['email'] );
        $obj->setData( $array['data'] );
        $obj->setCountry( $array['country'] );
        $obj->setAddress( $array['address'] );
        $obj->setCity( $array['city'] );
        $obj->setZipCode( $array['zip_code'] );
        $obj->setState( $array['state'] );
        $obj->setStatus( $array['status'] );
        $obj->setAmount( $array['amount'] );
        $obj->setPayPalTransId( $array['paypal_trans_id'] );
        $obj->setCreatedAt( $array['created_at'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}


/**
 * Class CartItemsObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class CartItemsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\CartItems"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setProductId( $array['product_id'] );
        $obj->setOrderId( $array['order_id'] );
        $obj->setTitle( $array['title'] );
        $obj->setPrice( $array['price'] );
        $obj->setQty( $array['qty'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}


?>