<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/07/14
 * Time: 22:14
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper.php" );

abstract class PersistenceFactory {

    abstract function getMapper();
    abstract function getDomainObjectFactory();
    abstract function getCollection( array $array );
    abstract function getSelectionFactory();
    abstract function getUpdateFactory();
    abstract function getDeleteFactory();
    abstract function getUpDownFactory();

    static function getFactory( $target_class ) {
        switch( $target_class ) {
            case "dmn\\domain\\News":
                return new NewsPersistenceFactory();
                break;
            case "dmn\\domain\\CartOrder":
                return new CartOrderPersistenceFactory();
                break;
            case "dmn\\domain\\CartItems":
                return new CartItemsPersistenceFactory();
                break;
            case "dmn\\domain\\Catalog":
                return new CatalogPersistenceFactory();
                break;
            case "dmn\\domain\\CatalogPosition":
                return new CatalogPositionPersistenceFactory();
                break;
        }
    }
}

/**
 * Class NewsPersistenceFactory
 * Для работы с блоком новостей
 * @package dmn\mapper
 */
class NewsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new NewsMapper();
    }

    function getDomainObjectFactory() {
        return new NewsObjectFactory();
    }

    function getCollection( array $array ) {
        return new NewsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new NewsSelectionFactory();
    }

    function getUpdateFactory() {
        return new NewsUpdateFactory();
    }

    function getIdentityObject() {
        return new NewsIdentityObject();
    }

    function getDeleteFactory() {
        return new NewsDeleteFactory();
    }

    function getUpDownFactory() {
        return new NewsUpDownFactory();
    }
}


/**
 * Class CartOrderPersistenceFactory
 * для работы с блоком заказов
 * @package dmn\mapper
 */
class CartOrderPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new CartOrderMapper();
    }

    function getDomainObjectFactory() {
        return new CartOrderObjectFactory();
    }

    function getCollection( array $array ) {
        return new CartOrderCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new CartOrderSelectionFactory();
    }

    function getUpdateFactory() {
        return new CartOrderUpdateFactory();
    }

    function getIdentityObject() {
        return new CartOrderIdentityObject();
    }

    function getDeleteFactory() {
        return new CartOrderDeleteFactory();
    }

    function getUpDownFactory() {
        return new CartOrderUpDownFactory();
    }
}


/**
 * Class CartOrderPersistenceFactory
 * для работы с блоком заказов
 * @package dmn\mapper
 */
class CartItemsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new CartItemsMapper();
    }

    function getDomainObjectFactory() {
        return new CartItemsObjectFactory();
    }

    function getCollection( array $array ) {
        return new CartItemsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new CartItemsSelectionFactory();
    }

    function getUpdateFactory() {
        return new CartItemsUpdateFactory();
    }

    function getIdentityObject() {
        return new CartItemsIdentityObject();
    }

    function getDeleteFactory() {
        return new CartItemsDeleteFactory();
    }

    function getUpDownFactory() {
        return new CartItemsUpDownFactory();
    }
}


/**
 * Class CatalogPersistenceFactory
 * для работы с блоком каталогов
 * @package dmn\mapper
 */
class CatalogPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new CatalogMapper();
    }

    function getDomainObjectFactory() {
        return new CatalogObjectFactory();
    }

    function getCollection( array $array ) {
        return new CatalogCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new CatalogSelectionFactory();
    }

    function getUpdateFactory() {
        return new CatalogUpdateFactory();
    }

    function getIdentityObject() {
        return new CatalogIdentityObject();
    }

    function getDeleteFactory() {
        return new CatalogDeleteFactory();
    }

    function getUpDownFactory() {
        return new CatalogUpDownFactory();
    }
}


/**
 * Class CatalogPersistenceFactory
 * для работы с блоком каталогов
 * @package dmn\mapper
 */
class CatalogPositionPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new CatalogPositionMapper();
    }

    function getDomainObjectFactory() {
        return new CatalogPositionObjectFactory();
    }

    function getCollection( array $array ) {
        return new CatalogPositionCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new CatalogPositionSelectionFactory();
    }

    function getUpdateFactory() {
        return new CatalogPositionUpdateFactory();
    }

    function getIdentityObject() {
        return new CatalogPositionIdentityObject();
    }

    function getDeleteFactory() {
        return new CatalogPositionDeleteFactory();
    }

    function getUpDownFactory() {
        return new CatalogPositionUpDownFactory();
    }
}
?>