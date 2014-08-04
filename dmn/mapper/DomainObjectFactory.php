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
        return $obj; // возвращаем объект \dmn\domain\CartOrderObjectFactory
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
        return $obj; // возвращаем объект \dmn\domain\CartItemsObjectFactory
    }
}

/**
 * Class CatalogObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class CatalogObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\Catalog"; // название класса
        $old = $this->getFromMap( $class, $array['id_catalog'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_catalog'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setOrderTitle( $array['order_title'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setAbbreviatura( $array['abbreviatura'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setUrlpict( $array['urlpict'] );
        $obj->setAlt( $array['alt'] );
        $obj->setRoundedFlag( $array['rounded_flag'] );
        $obj->setTitleFlag( $array['title_flag'] );
        $obj->setAltFlag( $array['alt_flag'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\CatalogObjectFactory
    }
}


/**
 * Class CatalogPositionObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class CatalogPositionObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\CatalogPosition"; // название класса
        $old = $this->getFromMap( $class, $array['id_position'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_position'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setOperator( $array['operator'] );
        $obj->setCost( $array['cost'] );
        $obj->setTimeconsume( $array['timeconsume'] );
        $obj->setCompatible( $array['compatible'] );
        $obj->setStatus( $array['status'] );
        $obj->setCurrency( $array['currency'] );
        $obj->setHide( $array['hide'] );
        $obj->setPos( $array['pos'] );
        $obj->setPutdate( $array['putdate'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\CatalogPositionObjectFactory
    }

}


/**
 * Class ArtCatalogObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class ArtCatalogObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\ArtCatalog"; // название класса
        $old = $this->getFromMap( $class, $array['id_catalog'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_catalog'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\ArtCatalogObjectFactory
    }
}


/**
 * Class ArtUrlObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class ArtUrlObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\ArtUrl"; // название класса
        $old = $this->getFromMap( $class, $array['id_position'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_position'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setUrl( $array['url'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\ArtUrlObjectFactory
    }
}



/**
 * Class ArtArtObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class ArtArtObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\News
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\ArtArt"; // название класса
        $old = $this->getFromMap( $class, $array['id_position'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_position'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\ArtArtObjectFactory
    }
}

/**
 * Class ArtParagraphObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class ArtParagraphObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\ArtParagraphObjectFactory
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\ArtParagraph"; // название класса
        $old = $this->getFromMap( $class, $array['id_paragraph'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_paragraph'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setType( $array['type'] );
        $obj->setAlign( $array['align'] );
        $obj->setHide( $array['hide'] );
        $obj->setPos( $array['pos'] );
        $obj->setIdPosition( $array['id_position'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}

/**
 * Class ArtParagraphImgObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class ArtParagraphImgObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\ArtParagraphImg
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\ArtParagraphImg"; // название класса
        $old = $this->getFromMap( $class, $array['id_image'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_image'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setAlt( $array['alt'] );
        $obj->setSmall( $array['small'] );
        $obj->setBig( $array['big'] );
        $obj->setHide( $array['hide'] );
        $obj->setPos( $array['pos'] );
        $obj->setIdPosition( $array['id_position'] );
        $obj->setIdCatalog( $array['id_catalog'] );
        $obj->setIdParagraph( $array['id_paragraph'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}

/**
 * Class AccountsObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class AccountsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\Accounts
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\Accounts"; // название класса
        $old = $this->getFromMap( $class, $array['id_account'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_account'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setPass( $array['pass'] );
        $obj->setLastvisit( $array['lastvisit'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}

/**
 * Class UsersObjectFactory
 * @package dmn\mapper
 * Как аргумент в классе PersistenceFactory
 */
class UsersObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \dmn\domain\Users
     */
    function createObject( array $array ) {
        $class = "\\dmn\\domain\\Users"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setFio( $array['fio'] );
        $obj->setCity( $array['city'] );
        $obj->setEmail( $array['email'] );
        $obj->setUrl( $array['url'] );
        $obj->setLogin( $array['login'] );
        $obj->setActivation( $array['activation'] );
        $obj->setStatus( $array['status'] );
        $obj->setPass( $array['pass'] );
        $obj->setPutdate( $array['putdate'] );
        $obj->setLastvisit( $array['lastvisit'] );
        $obj->setBlock( $array['block'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \dmn\domain\News
    }
}

?>