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
        return \imei_service\domain\ObjectWatcher::add( $obj );
    }
}

/**
 * Class NewsObjectFactory
 * @package imei_service\mapper
 * Как аргумент в классе PersistenceFactory
 */
class NewsObjectFactory extends DomainObjectFactory {
    function createObject( array $array ) {
        $class = "\\imei_service\\domain\\News";

//        $old = $this->getFromMap( $class, $array['id'] );
//        if( $old ) { return $old; }

        $obj = new $class( $array['id'] );
        $obj->setName( $array['name'] );
        $obj->setPreview( $array['preview'] );
        $obj->setBody( $array['body'] );
        $obj->setPutdate( $array['putdate'] );
        $obj->setUrlpict_s( $array['urlpict_s'] );
        $obj->setAlt( $array['alt'] );
        $obj->setUrl( $array['url'] );
        $obj->setUrltext( $array['urltext'] );
        $obj->setHidepict( $array['hidepict'] );

//        $this->addToMap( $obj );
//        $obj->markClean();
        return $obj;
    }
}