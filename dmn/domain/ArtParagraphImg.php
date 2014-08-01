<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 16:14
 */

namespace dmn\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
require_once( "dmn/mapper/ArtParagraphImgIdentityObject.php" );
//require_once( "dmn/mapper/ArtParagraphImgUpDownFactory.php" );
//require_once( "dmn/domain/ArtArtPosition.php" );
require_once( "dmn/base/Registry.php" );

class ArtParagraphImg extends DomainObject {
    private $name;
    private $alt;
    private $small;
    private $big;
    private $hide;
    private $pos;
    private $idPosition;
    private $idCatalog;
    private $idParagraph;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $alt
     * @param null $small
     * @param null $big
     * @param string $hide
     * @param null $pos
     * @param null $idPosition
     * @param null $idCatalog
     * @param null $idParagraph
     */
    function __construct( $id=null,
                          $name=null,
                          $alt=null,
                          $small=null,
                          $big=null,
                          $hide='hide',
                          $pos=null,
                          $idPosition=null,
                          $idCatalog=null,
                          $idParagraph=null ) {

        $this->name         = $name;
        $this->alt          = $alt;
        $this->small        = $small;
        $this->big          = $big;
        $this->hide         = $hide;
        $this->pos          = $pos;
        $this->idPosition   = $idPosition;
        $this->idCatalog    = $idCatalog;
        $this->idParagraph  = $idParagraph;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // catalogIdentityObject
        $catalogIdobj = new \dmn\mapper\ArtParagraphImgIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($catalogIdobj, true)."</pre></tt>";
        return $finder->find( $catalogIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $idph - id параграфа
     * @param $idc - id каталога
     * @param $idp - id позиции
     * @return mixed
     */
    static function find( $idph, $idc, $idp=null ) {
//        echo "<tt><pre>".print_r($idc, true)."</pre></tt>";
        $finder = self::getFinder( __CLASS__ );
        if( ! is_null( $idp ) ) {
            $idobj = new \dmn\mapper\ArtParagraphImgIdentityObject( 'id_paragraph' );
            return $finder->findOne( $idobj->eq( $idph )->field( 'id_catalog' )->eq( $idc )->field( 'id_position' )->eq( $idp ) );
        } else {
            $idobj = new \dmn\mapper\ArtParagraphImgIdentityObject( 'id_position' );
            return $finder->find( $idobj->eq( $idph )->field( 'id_catalog' )->eq( $idc ) );
        }
    }

    /**
     * Метод для поиска родительского каталога
     * @param $id
     * @return mixed
     */
    static function findParent( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtParagraphImgIdentityObject( 'id_catalog' );
        return $finder->find( $idobj->eq( $id ) );
    }

    /**
     * Метод для поиска родительского каталога
     * @param $id
     * @return mixed
     */
    static function findCatalog( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtParagraphImgIdentityObject( 'id_position' );
        return $finder->find( $idobj->eq( $id ) );
    }


    /**
     * Метод для получения максимальной позиции
     * @param $id - id каталога
     * @param $idp - id позиции
     * @return mixed
     */
    static function findMaxPos( $id, $idp ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtParagraphImgIdentityObject( 'id_catalog' );
        return $finder->findMaxPos( $idobj->eq( $id )->field( 'id_position' )->eq( $idp ) );
    }

    /**
     * Метод для получения настроек параметров изображений
     * @return mixed
     */
    static function findPhotoSetting() {
        $finder = self::getFinder( __CLASS__ );
        return $finder->findPhotoSetting();
    }

    /**
     * Метод для получения количество позиций по id каталога
     * @param $idp - id параграфа
     * @param $idpos - id позиции
     * @param $idc - id каталога
     * @return mixed
     */
    static function findCountPos( $idp, $idpos, $idc  ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtParagraphImgIdentityObject( 'id_paragraph' );
        return $finder->findCountPos( $idobj->eq( $idp )->field( 'id_position' )->eq( $idpos )->field( 'id_catalog' )->eq( $idc ) );
    }


    /**
     * устанавливаем имя
     * @param $name_s
     */
    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    /**
     * устанавливаем название изображения
     * @param $alt_s
     */
    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }
    /**
     * устанавливаем маленькое изображение
     * @param $small_s
     */
    function setSmall( $small_s ) {
        $this->small = $small_s;
        $this->markDirty();
    }
    /**
     * устанавливаем большое изображение
     * @param $align_s
     */
    function setBig( $big_s ) {
        $this->big = $big_s;
        $this->markDirty();
    }
    /**
     * устанавливаем сокрытие/отображение
     * @param $hide_s
     */
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
        $this->markDirty();
    }
    /**
     * устанавливаем позицию
     * @param $pos_s
     */
    function setPos( $pos_s ) {
        $this->pos = $pos_s;
        $this->markDirty();
    }
    /**
     * устанавливаем id позиции
     * @param $idPosition_s
     */
    function setIdPosition( $idPosition_s ) {
        $this->idPosition = $idPosition_s;
        $this->markDirty();
    }
    /**
     * устанавливаем id каталога
     * @param $idCatalog_s
     */
    function setIdCatalog( $idCatalog_s ) {
        $this->idCatalog = $idCatalog_s;
        $this->markDirty();
    }
    /**
     * устанавливаем id параграфа
     * @param $idParagraph_s
     */
    function setIdParagraph( $idParagraph_s ) {
        $this->idParagraph = $idParagraph_s;
        $this->markDirty();
    }

    /**
     * получаем имя
     * @return null
     */
    function getName() {
        return $this->name;
    }
    /**
     * получаем название изображения
     * @return null
     */
    function getAlt() {
        return $this->alt;
    }
    /**
     * получаем маленькое изображение
     * @return null
     */
    function getSmall() {
        return $this->small;
    }
    /**
     * получаем большое изображение
     * @return null
     */
    function getBig() {
        return $this->big;
    }
    /**
     * получаем сокрытие/отображение
     * @return null
     */
    function getHide() {
        return $this->hide;
    }
    /**
     * получаем позицию
     * @return null
     */
    function getPos() {
        return $this->pos;
    }
    /**
     * Получаем id позиции
     * @return null
     */
    function getIdPosition() {
        return $this->idPosition;
    }
    /**
     * Получаем id каталога
     * @return null
     */
    function getIdCatalog() {
        return $this->idCatalog;
    }
    /**
     * Получаем id параграфа
     * @return null
     */
    function getIdParagraph() {
        return $this->idParagraph;
    }
}
?>