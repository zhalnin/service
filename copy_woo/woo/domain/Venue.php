<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/01/14
 * Time: 19:13
 * To change this template use File | Settings | File Templates.
 */

namespace woo\domain;

require_once( "woo/domain/DomainObject.php" );
require_once( "woo/mapper/SpaceIdentityObject.php" );

class Venue extends DomainObject {
    private $name;
    private $spaces;

    function __construct( $id=null, $name=null ) {
        $this->name = $name;
        // Delete after debugging
//        $this->spaces = self::getCollection( "\\woo\\domain\\Space" );
        parent::__construct( $id );
    }

    function setSpaces( SpaceCollection $spaces ) {
        $this->spaces = $spaces;
    }

    /**
     *
     * @return array|\woo\mapper\EventCollection|\woo\mapper\SpaceCollection|\woo\mapper\VenueCollection
     */
    function getSpaces() {
        if( ! isset( $this->spaces ) ) {
            // Получаем класс woo/mapper/SpaceMapper
            $finder = self::getFinder( 'woo\\domain\\Space' );
//            echo "<tt><pre>  getSpaces() - ".print_r($finder,true)."</pre></tt>";
//            echo "<tt><pre>  getSpaces() - ".print_r($this->getId(),true)."</pre></tt>";
            // Добавляем к переменной результат поиска SpaceMapper->findByVenue()
            // Возвращаем коллекцию
            // Передаем:
            // SpacePersistenceFactory->getDomainObjectFactory() - return new SpaceObjectFactory()(т.е. createObject());
//            $this->spaces = $finder->findByVenue( $this->getId() );
//            $this->spaces = self::getCollection( 'woo\\domain\\Space' );

            $idobj = new \woo\mapper\SpaceIdentityObject( 'venue' );
            $this->spaces = $finder->find( $idobj->eq( $this->getId() ) );
        }
//        echo "<tt><pre> getSpaces() ".print_r($this->spaces,true)."</pre></tt>";

        return $this->spaces;
    }

    /**
     * Вызываем из woo\domain\AddSpace
     * - ($venue->addSpace( $space = new \woo\domain\Space(null, $name ) );)
     * @param Space $space
     */
    function addSpace( Space $space ) {
//        echo "<tt><pre> woo/domain/Space from woo/domain/Venue - ".print_r($space,true)."</pre></tt>";
        // Delete after debugging
//        $this->spaces->add( $space );
//        echo "<tt><pre> getSpaces() - ".print_r($this->getSpaces(),true)."</pre></tt>";
        // Вызываем метод getSpaces() из SpaceMapper и add() из SpaceCollection(Collection)
        $this->getSpaces()->add( $space );
//        echo "<tt><pre> Venue - addSpace() -  ".print_r($this,true)."</pre></tt>";
        // В woo/domain/Space добавляем woo/domain/Venue
        $space->setVenue( $this );
//        echo "<tt><pre> addSpace - setVenue() ".print_r($this,true)."</pre></tt>";
    }

    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }

    /**
     * Возвращает имя, которое необходимо добавить в БД
     * из формы
     * @return null - имя
     */
    function getName() {
        return $this->name;
    }

    static function findAll() {
        $finder = self::getFinder( __CLASS__ );
//        return $finder->findAll();
        $idobj = new \woo\mapper\VenueIdentityObject();
        return $finder->find( $idobj );
    }

    /**
     * Ищем venue по его id
     * @param $id
     * @return mixed - VenueMapper->find($id)
     */
    static function find( $id ) {
        // Ищем подходящий Mapper - VenueMapper
        $finder = self::getFinder( __CLASS__ );
//        return $finder->find( $id );
        $idobj = new \woo\mapper\VenueIdentityObject( 'id' );
        return $finder->findOne( $idobj->eq( $id ) ); // DomainObjectAssembler
//        return $finder->find( $idobj->eq( $id ) );
    }

}

?>