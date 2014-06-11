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



class UnlockDetailsObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {

//        echo "<tt><pre>".print_r( $array, true)."</pre></tt>";

        $class = "\\imei_service\\domain\\UnlockDetails";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
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


//        Здесь надо узнать из system_catalog ребенка,
//        у которого id_parent = 6,
//        где 6 - это id корневого элемента Официальный анлок
//        $collection1 = \imei_service\domain\UnlockDetails::find( $request->getProperty( 'id_catalog' ) );
//        $request->setObject( 'unlockDetails', $collection1 );

//        $factory = PersistenceFactory::getFactory( 'imei_service\\domain\\Unlock' );
//        $unlock_assembler = new DomainObjectAssembler( $factory );
//        $unlock_idobj = new UnlockIdentityObject( 'id' );
//        $unlock_idobj->eq( $array['id_catalog'] )->field( 'hide' )->eq( 'show' );
//        $unlock_collection = $unlock_assembler->findOne( $unlock_idobj );
//        $obj->setUnlock( $unlock_collection );
//
//        echo "<tt><pre>".print_r( $unlock_collection, true)."</pre></tt>";

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class UnlockObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\Unlock";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setOrderTitle( $array['order_title'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setAbbreviatura( $array['abbreviatura'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setUrlPict( $array['urlpict'] );
        $obj->setAlt( $array['alt'] );
        $obj->setRoundedFlag( $array['rounded_flag'] );
        $obj->setTitleFlag( $array['title_flag'] );
        $obj->setAltFlag( $array['alt_flag'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
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
//        echo "<tt><pre>".print_r($array['id'], true)."</pre></tt>";
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


        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class UdidObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\Udid";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setOrderTitle( $array['order_title'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setAbbreviatura( $array['abbreviatura'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setUrlPict( $array['urlpict'] );
        $obj->setAlt( $array['alt'] );
        $obj->setRoundedFlag( $array['rounded_flag'] );
        $obj->setTitleFlag( $array['title_flag'] );
        $obj->setAltFlag( $array['alt_flag'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class CarrierCheckObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\CarrierCheck";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setOrderTitle( $array['order_title'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setAbbreviatura( $array['abbreviatura'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setUrlPict( $array['urlpict'] );
        $obj->setAlt( $array['alt'] );
        $obj->setRoundedFlag( $array['rounded_flag'] );
        $obj->setTitleFlag( $array['title_flag'] );
        $obj->setAltFlag( $array['alt_flag'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class FastCheckObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\FastCheck";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setOrderTitle( $array['order_title'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setAbbreviatura( $array['abbreviatura'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setUrlPict( $array['urlpict'] );
        $obj->setAlt( $array['alt'] );
        $obj->setRoundedFlag( $array['rounded_flag'] );
        $obj->setTitleFlag( $array['title_flag'] );
        $obj->setAltFlag( $array['alt_flag'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class BlacklistCheckObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\BlacklistCheck";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setOrderTitle( $array['order_title'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setAbbreviatura( $array['abbreviatura'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setUrlPict( $array['urlpict'] );
        $obj->setAlt( $array['alt'] );
        $obj->setRoundedFlag( $array['rounded_flag'] );
        $obj->setTitleFlag( $array['title_flag'] );
        $obj->setAltFlag( $array['alt_flag'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}



class FaqObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\Faq";
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdParent( $array['id_parent'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}