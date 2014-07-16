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


?>