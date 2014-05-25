<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30/01/14
 * Time: 22:51
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

if( ! isset( $EG_DISABLE_INCLUDES ) ) {
    require_once( "woo/mapper/VenueMapper.php" );
    require_once( "woo/mapper/SpaceMapper.php" );
    require_once( "woo/mapper/EventMapper.php" );
    require_once( "woo/mapper/Collections.php" );
}

require_once( "woo/mapper/DomainObjectAssembler.php" );

class HelperFactory {

    /**
     * Используем эту фабрику для определения подходящего класса Mapper (модель)
     * @param $type - имя класса
     * @return mixed - соответствующий класс Mapper
     * @throws \woo\base\AppException
     */
    static function getFinder( $type ) {
//        // удаляем все символы кроме последних - \\woo\\domain\\Venue - остается Venue
//        $type = preg_replace('|^.*\\\|',"", $type );
//        // собираем имя требуемой модели - класса Mapper
//        $mapper = "\\woo\\mapper\\{$type}Mapper";
//        // если такой класс существует
//        if( class_exists( $mapper ) ) {
//            //  то возвращаем его экземпляр
//            return new $mapper;
//        }
//        // или выбрасывем ошибку
//        throw new \woo\base\AppException( "unknown: $mapper" );

        $factory = \woo\mapper\PersistenceFactory::getFactory( $type );
        return new \woo\mapper\DomainObjectAssembler( $factory );
    }

    static function getCollection( $type, array $array ) {
////        echo "<tt><pre>".print_r($type, true)."</pre></tt>";
//        $type = preg_replace('|^.*\\\|',"", $type );
////        echo "<tt><pre>".print_r($type, true)."</pre></tt>";
//        $collection = "\\woo\\mapper\\{$type}Collection";
//        if( class_exists( $collection ) ) {
//            return new $collection;
//        }
//        throw new  \woo\base\AppException( "unknown: $collection" );

        $factory = \woo\mapper\PersistenceFactory::getFactory( $type );
        return $factory->getCollection( $array );
    }
}
?>