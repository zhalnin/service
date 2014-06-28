<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/05/14
 * Time: 22:30
 */

namespace imei_service\mapper;
use imei_service\domain\DomainObject;

error_reporting( E_ALL & ~E_NOTICE );

//require_once( 'imei_service/domain.php' );
require_once( 'imei_service/domain/FaqPosition.php' );
require_once( 'imei_service/domain/FaqParagraphImage.php' );


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
        $class = "\\imei_service\\domain\\UnlockDetails";
        $old = $this->getFromMap( $class, $array['id_catalog'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_catalog'] );
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
        return $obj;
    }
}


class UnlockObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\Unlock";
        $old = $this->getFromMap( $class, $array['id_catalog'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_catalog'] );
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
        $class = "\\imei_service\\domain\\News"; // название класса
        $old = $this->getFromMap( $class, $array['id_news'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_news'] ); // создаем экземпляр класса, в конструктор передаем id
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
        $old = $this->getFromMap( $class, $array['id_catalog'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_catalog'] );
        $obj->setName( $array['name'] );
        $obj->setDescription( $array['description'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdParent( $array['id_parent'] );

//        $collection = \imei_service\domain\FaqPosition::find( $request->getProperty( 'id_position' ) );

        $factory = \imei_service\mapper\PersistenceFactory::getFactory( 'imei_service\\domain\\FaqPosition' );
        $faqPosition_assembler = new DomainObjectAssembler( $factory );
        $faqPosition_idobj = new FaqPositionIdentityObject( 'id_catalog' );
        $faqPosition_idobj->eq( $array['id_catalog'])->field( 'hide' )->eq( 'show' );
        $faqPosition_collection = $faqPosition_assembler->find( $faqPosition_idobj );
        $obj->setFaqPosition( $faqPosition_collection );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}



class FaqPositionObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = '\imei_service\domain\FaqPosition';
        $old = $this->getFromMap( $class, $array['id_position'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_position'] );
        $obj->setName( $array['name'] );
        $obj->setUrl( $array['url'] );
        $obj->setKeywords( $array['keywords'] );
        $obj->setModrewrite( $array['modrewrite'] );
        $obj->setPos( $array['pos'] );
        $obj->setHide( $array['hide'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class FaqParagraphObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
//        echo "<tt><pre>".print_r( $array, true) ."</pre></tt>";
        $class = '\imei_service\domain\FaqParagraph';
//        echo "<tt><pre>".print_r(  $class, true) ."</pre></tt>";
        $old = $this->getFromMap( $class, $array['id_position'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_position'] );
        $obj->setName( $array['name'] );
        $obj->setType( $array['type'] );
        $obj->setAlign( $array['align'] );
        $obj->setHide( $array['hide'] );
        $obj->setPos( $array['pos'] );
        $obj->setIdPosition( $array['id_position'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $factory = \imei_service\mapper\PersistenceFactory::getFactory( 'imei_service\\domain\\FaqParagraphImage' );
        $faqParagraphImage_assembler = new DomainObjectAssembler( $factory );
        $faqParagraphImage_idobj = new FaqParagraphImageIdentityObject('id_catalog' );
        $faqParagraphImage_idobj->eq( $array['id_catalog'] )->field( 'id_position' )->eq( $array['id_position'] )->field( 'id_paragraph' )->eq( $array['id_paragraph'] )->field( 'hide' )->eq( 'show' );
        $faqParagraphImage_collection = $faqParagraphImage_assembler->find( $faqParagraphImage_idobj );

//        echo "<tt><pre>".print_r( $faqParagraphImage_collection , true) ."</pre></tt>";
        $obj->setFaqParagraphImage( $faqParagraphImage_collection );



//        $factory = \imei_service\mapper\PersistenceFactory::getFactory( 'imei_service\\domain\\FaqPosition' );
//        $faqPosition_assembler = new DomainObjectAssembler( $factory );
//        $faqPosition_idobj = new FaqPositionIdentityObject( 'id_catalog' );
//        $faqPosition_idobj->eq( $array['id_catalog'])->field( 'hide' )->eq( 'show' );
//        $faqPosition_collection = $faqPosition_assembler->find( $faqPosition_idobj );
//        $obj->setFaqPosition( $faqPosition_collection );


        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class FaqParagraphImageObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = '\imei_service\domain\FaqParagraphImage';
        $old = $this->getFromMap( $class, $array['id_image'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id_image'] );
        $obj->setName( $array['name'] );
        $obj->setAlt( $array['alt'] );
        $obj->setSmall( $array['small'] );
        $obj->setBig( $array['big'] );
        $obj->setHide( $array['hide'] );
        $obj->setPos( $array['pos'] );
        $obj->setIdParagraph( $array['id_paragraph'] );
        $obj->setIdPosition( $array['id_position'] );
        $obj->setIdCatalog( $array['id_catalog'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class LoginOshibkaObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = '\imei_service\domain\LoginOshibka';
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setIp( $array['ip'] );
        $obj->setDate( $array['date'] );
        $obj->setCol( $array['col'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}


class LoginObjectFactory extends DomainObjectFactory {

    function createObject( array $array ) {
        $class = '\imei_service\domain\Login';
        $old = $this->getFromMap( $class, $array['id'] );
        if( $old ) { return $old; }
        $obj = new $class( $array['id'] );
        $obj->setFio( $array['fio'] );
        $obj->setCity( $array['city'] );
        $obj->setEmail( $array['email'] );
        $obj->setUrl( $array['url'] );
        $obj->setLogin( $array['login'] );
        $obj->setPass( $array['pass'] );
        $obj->setActivation( $array['activation'] );
        $obj->setStatus( $array['status'] );

        $this->addToMap( $obj );
        $obj->markClean();
        return $obj;
    }
}

?>