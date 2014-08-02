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
            case "dmn\\domain\\ArtCatalog":
                return new ArtCatalogPersistenceFactory();
                break;
            case "dmn\\domain\\ArtUrl":
                return new ArtUrlPersistenceFactory();
                break;
            case "dmn\\domain\\ArtArt":
                return new ArtArtPersistenceFactory();
                break;
            case "dmn\\domain\\ArtParagraph":
                return new ArtParagraphPersistenceFactory();
                break;
            case "dmn\\domain\\ArtParagraphImg":
                return new ArtParagraphImgPersistenceFactory();
                break;
            case "dmn\\domain\\Accounts":
                return new AccountsPersistenceFactory();
                break;
            case "dmn\\domain\\Users":
                return new UsersPersistenceFactory();
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

/**
 * Class ArtCatalogPersistenceFactory
 * для работы с блоком каталогов
 * @package dmn\mapper
 */
class ArtCatalogPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new ArtCatalogMapper();
    }

    function getDomainObjectFactory() {
        return new ArtCatalogObjectFactory();
    }

    function getCollection( array $array ) {
        return new ArtCatalogCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new ArtCatalogSelectionFactory();
    }

    function getUpdateFactory() {
        return new ArtCatalogUpdateFactory();
    }

    function getIdentityObject() {
        return new ArtCatalogIdentityObject();
    }

    function getDeleteFactory() {
        return new ArtCatalogDeleteFactory();
    }

    function getUpDownFactory() {
        return new ArtCatalogUpDownFactory();
    }
}

/**
 * Class ArtUrlPersistenceFactory
 * для работы с блоком каталогов - ссылками
 * @package dmn\mapper
 */
class ArtUrlPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new ArtUrlMapper();
    }

    function getDomainObjectFactory() {
        return new ArtUrlObjectFactory();
    }

    function getCollection( array $array ) {
        return new ArtUrlCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new ArtUrlSelectionFactory();
    }

    function getUpdateFactory() {
        return new ArtUrlUpdateFactory();
    }

    function getIdentityObject() {
        return new ArtUrlIdentityObject();
    }

    function getDeleteFactory() {
        return new ArtUrlDeleteFactory();
    }

    function getUpDownFactory() {
        return new ArtUrlUpDownFactory();
    }
}


/**
 * Class ArtArtPersistenceFactory
 * для работы с блоком каталогов - статьей
 * @package dmn\mapper
 */
class ArtArtPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new ArtArtMapper();
    }

    function getDomainObjectFactory() {
        return new ArtArtObjectFactory();
    }

    function getCollection( array $array ) {
        return new ArtArtCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new ArtArtSelectionFactory();
    }

    function getUpdateFactory() {
        return new ArtArtUpdateFactory();
    }

    function getIdentityObject() {
        return new ArtArtIdentityObject();
    }

    function getDeleteFactory() {
        return new ArtArtDeleteFactory();
    }

    function getUpDownFactory() {
        return new ArtArtUpDownFactory();
    }
}

/**
 * Class ArtParagraphPersistenceFactory
 * для работы с блоком каталогов - статьей
 * @package dmn\mapper
 */
class ArtParagraphPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new ArtParagraphMapper();
    }

    function getDomainObjectFactory() {
        return new ArtParagraphObjectFactory();
    }

    function getCollection( array $array ) {
        return new ArtParagraphCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new ArtParagraphSelectionFactory();
    }

    function getUpdateFactory() {
        return new ArtParagraphUpdateFactory();
    }

    function getIdentityObject() {
        return new ArtParagraphIdentityObject();
    }

    function getDeleteFactory() {
        return new ArtParagraphDeleteFactory();
    }

    function getUpDownFactory() {
        return new ArtParagraphUpDownFactory();
    }
}


/**
 * Class ArtParagraphImgPersistenceFactory
 * для работы с блоком каталогов - статьей
 * @package dmn\mapper
 */
class ArtParagraphImgPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new ArtParagraphImgMapper();
    }

    function getDomainObjectFactory() {
        return new ArtParagraphImgObjectFactory();
    }

    function getCollection( array $array ) {
        return new ArtParagraphImgCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new ArtParagraphImgSelectionFactory();
    }

    function getUpdateFactory() {
        return new ArtParagraphImgUpdateFactory();
    }

    function getIdentityObject() {
        return new ArtParagraphImgIdentityObject();
    }

    function getDeleteFactory() {
        return new ArtParagraphImgDeleteFactory();
    }

    function getUpDownFactory() {
        return new ArtParagraphImgUpDownFactory();
    }
}

/**
 * Class AccountsPersistenceFactory
 * для работы с блоком каталогов - статьей
 * @package dmn\mapper
 */
class AccountsPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new AccountsMapper();
    }

    function getDomainObjectFactory() {
        return new AccountsObjectFactory();
    }

    function getCollection( array $array ) {
        return new AccountsCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new AccountsSelectionFactory();
    }

    function getUpdateFactory() {
        return new AccountsUpdateFactory();
    }

    function getIdentityObject() {
        return new AccountsIdentityObject();
    }

    function getDeleteFactory() {
        return new AccountsDeleteFactory();
    }

    function getUpDownFactory() {
        return new AccountsUpDownFactory();
    }
}

/**
 * Class UsersPersistenceFactory
 * для работы с блоком пользователей
 * @package dmn\mapper
 */
class UsersPersistenceFactory extends PersistenceFactory {

    function getMapper() {
        return new UsersMapper();
    }

    function getDomainObjectFactory() {
        return new UsersObjectFactory();
    }

    function getCollection( array $array ) {
        return new UsersCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
//        echo "<tt><pre>".print_r("ksdjfkdsjfkjds", true)."</pre></tt>";
        return new UsersSelectionFactory();
    }

    function getUpdateFactory() {
        return new UsersUpdateFactory();
    }

    function getIdentityObject() {
        return new UsersIdentityObject();
    }

    function getDeleteFactory() {
        return new UsersDeleteFactory();
    }

    function getUpDownFactory() {
        return new UsersUpDownFactory();
    }
}

?>