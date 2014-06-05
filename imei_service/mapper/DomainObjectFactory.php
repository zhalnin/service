<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 22:30
 */

namespace imei_service\mapper;



abstract class DomainObjectFactory {
    abstract function createObject( array $array );

    protected function getFromMap( $class, $id ) {
        return \imei_service\domain\ObjectWatcher::exists( $class, $id );
    }

    protected function addToMap( \imei_service\domain\DomainObject $obj ) {
        \imei_service\domain\ObjectWatcher::add( $obj );
    }
}

/**
 * Class NewsObjectFactory
 * @package imei_service\mapper
 * Как аргумент в классе PersistenceFactory
 */
class NewsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * getRow()
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \imei_service\domain\News
     */
    function createObject( array $array ) {
//        echo "<tt><pre>".print_r($array, true)."</pre></tt>";
        $class = "\\imei_service\\domain\\News"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setPreview( $array['preview'] );
        $obj->setBody( $array['body'] );
        $obj->setPutdate( $array['putdate'] );
        $obj->setUrlpict_s( $array['urlpict_s'] );
        $obj->setAlt( $array['alt'] );
        $obj->setUrl( $array['url'] );
        $obj->setUrltext( $array['urltext'] );
        $obj->setHidepict( $array['hidepict'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj; // возвращаем объект \imei_service\domain\News
    }
}


class ContactsObjectFactory extends DomainObjectFactory {

    /**
     * Вызываем из класса Collection с итератором из метода
     * @param array $array - результирующий набор данных (после SELECT)
     * @return mixed - возвращаем объект \imei_service\domain\News
     */
    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\Contacts"; // название класса
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] ); // создаем экземпляр класса, в конструктор передаем id
        // используем методы set...( array ) - и добавляем результат запроса в класс, получим их, соответственно методами get...()
        $obj->setName( $array['name'] );
        $obj->setPhone( $array['phone'] );
        $obj->setFax( $array['fax'] );
        $obj->setEmail( $array['email'] );
        $obj->setSkype( $array['skype'] );
        $obj->setVk( $array['vk'] );
        $obj->setAddress( $array['address'] );
        $obj->setPhoto( $array['photo'] );
        $obj->setPhotoSmall( $array['photo_small'] );
        $obj->setAlt( $array['alt'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;  // возвращаем объект \imei_service\domain\Contacts
    }
}


class GuestbookObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
//        echo "<tt><pre>".print_r($array, true)."</pre></tt>";
        $class = "\\imei_service\\domain\\Guestbook";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) {
            return $old;
        }
        $obj = new $class( $array['id'] );

        $obj->setName( $array['name'] );
        $obj->setCity( $array['city'] );
        $obj->setEmail( $array['email'] );
        $obj->setUrl( $array['url'] );
        $obj->setMessage( $array['message'] );
        $obj->setAnswer( $array['answer'] );
        $obj->setPutdate( $array['putdate'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdparent( $array['id_parent'] );
        $obj->setIp( $array['ip'] );
        $obj->setBrowser( $array['browser'] );






        $child_factory = \imei_service\mapper\PersistenceFactory::getFactory( 'imei_service\domain\Guestbook' );
        $child_assembler = new \imei_service\mapper\DomainObjectAssembler( $child_factory );
        $child_idobj = new GuestbookIdentityObject( 'hide' );
        $child_idobj->eq( 'show' )->field( 'id_parent' )->eq( $array['id_parent'] );
        $child_collection = $child_assembler->paginationChildMysql( $child_idobj );
        echo "<tt><pre>".print_r($child_collection, true)."</pre></tt>";






        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}